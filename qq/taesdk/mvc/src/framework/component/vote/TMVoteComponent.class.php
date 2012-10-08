<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2008 BY  TENCENT  CORPORATION.  ALL RIGHTS
 *     RESERVED.   NO  PART  OF THIS PROGRAM  OR  PUBLICATION  MAY
 *     BE  REPRODUCED,   TRANSMITTED,   TRANSCRIBED,   STORED  IN  A
 *     RETRIEVAL SYSTEM, OR TRANSLATED INTO ANY LANGUAGE OR COMPUTER
 *     LANGUAGE IN ANY FORM OR BY ANY MEANS, ELECTRONIC, MECHANICAL,
 *     MAGNETIC,  OPTICAL,  CHEMICAL, MANUAL, OR OTHERWISE,  WITHOUT
 *     THE PRIOR WRITTEN PERMISSION OF :
 *
 *                        TENCENT  CORPORATION
 *
 *       Advertising Platform R&D Team, Advertising Platform & Products
 *       Tencent Ltd.
 *---------------------------------------------------------------------------
 */

/**
 * TMVoteComponent 投票接口
 *
 * @package components.vote.classes
 * @author  lynkli <lynkli@tencent.com>
 * @version TMVoteComponent.class.php 2009-10-30 by lynkli
 */
class TMVoteComponent
{
    private $config = array();
    private static $instances = array();
    private $configPath;
    private $componentDir;
    private $historyTable = 'Tbl_VoteHistory';
    private $voteType = 1;
    
    protected $taeCounterType = 102;

    protected $counterStack = array();
    
    public static function getInstance($app='vote')
    {
        if (!isset(self::$instances[$app]))
        {
            $class = __CLASS__;
            self::$instances[$app] = new $class($app);
        }

        return self::$instances[$app];
    }

    /**
     * get the vote settings
     *
     * @param string $path 配置文件地址
     */
    public function __construct($app)
    {
        $this->componentDir = TMDispatcher::getComponentsDir('vote');
        $this->configPath = $this->componentDir . 'config/' . $app . '.yml';
    }

    /**
     * 获取投票的配置
     * @param string $path 配置文件的路径
     */
    private function _getConfig($path)
    {
        if (!file_exists($path))
       {
            $content = json_encode(array("code"=> 99, "message"=>"component app config file ($path) does not exist"));
            throw new TMVoteComponentException($content);
        }
        $this->config = TMBasicConfigHandle::getInstance()->execute($path);
    }

    /**
     * 设置投票明细表的表名
     * 默认的表名为 Tbl_VoteHistory
     *
     * @param string $tbl 表名
     */
    public function setHistoryTable($tbl)
    {
        $this->historyTable = $tbl;
    }

    /**
     * 设置投票类型
     * 默认的投票类型为 1
     *
     * @param int $type 投票类型 1:普通投票，2:手机投票
     */
    public function setVoteType($type)
    {
        $this->voteType = $type;
    }

    /**
     * 根据错误码抛出对应的异常
     *
     * @param int $code 错误代码
     */
    private function throwException($code)
    {
        $config = $this->config;
        $content = json_encode(array("code"=> $code, "message"=>$config['messages'][$code]));
        throw new TMVoteComponentException($content);
    }

    /**
     * 判断投票是否过期
     *
     * @param array $range [datestart, dateend]
     * @param array $config 投票配置
     * @return int 0: in time, 1: expired, 2: not begin
     */
    private function checkExpired($range, $config)
    {
        $timeNow = time();
        $dateMin = $range['start'];
        $dateMax = $range['end'];

        if (!empty($dateMin))
        {
            $timeMin = strtotime($dateMin);
            if ($timeNow < $timeMin)
            {
                $this->throwException($config['code']['VOTE_NOT_BEGIN']);
            }
        }

        if (!empty($dateMax))
        {
            $timeMax = strtotime($dateMax);
            if ($timeNow > $timeMax)
            {
                $this->throwException($config['code']['VOTE_EXPIRED']);
            }
        }
    }

    /**
     * 判断当前投票用户是否特殊用户，即可以刷票的用户（返回一次投票增加的票数，以及投票的限制配置）
     *
     * @param string $qq 用户QQ号码
     * @param array $config 投票配置
     * @return array array("count"=>$count, "limits"=>$limits)
     */
    private function isSpecialUser($qq, $config)
    {
        $count = 0;
        $limits = array();
        $isSpecial = false;
        if (!empty($config['sQQs']) && is_array($config['sQQs']) && in_array($qq, $config['sQQs']))
        {
            if (is_array($config['sCount']))
            {
                $min = (int) $config['sCount'][0];
                $max = (int) $config['sCount'][1];
                $count = rand($min, $max);
            }
            else
            {
                $count = (int) $config['sCount'];
            }

            $limits['oneday'] = (int) $config['sOneDayLimit'];
            $limits['onedayPerObject'] = (int) $config['sOneDayPerObject'];
            $limits['total'] = (int) $config['sTotalLimit'];
            $limits['totalPerObject'] = (int) $config['sTotalPerObject'];
            $isSpecial = true;
        }
        else
        {
            $count = (int) $config['count'];
            $limits['oneday'] = (int) $config['oneDayLimit'];
            $limits['onedayPerObject'] = (int) $config['oneDayPerObject'];
            $limits['total'] = (int) $config['totalLimit'];
            $limits['totalPerObject'] = (int) $config['totalPerObject'];
        }

        return array("count"=>$count, "limits"=>$limits, "isSpecial" => $isSpecial);
    }

    /**
     * 判断是否超出投票限制
     *
     * @param string $qq 投票者QQ号码
     * @param int $vid 被投票的Id或者QQ
     * @param array $limits 投票限制数，包括oneday:每天的投票限制数，
     * @param array $config 投票配置数组
     */
    private function checkLimited($qq, $vid, $limits, $config)
    {
        if ($limits['oneday'] != 0)
        {
            $taeCounterResult = TaeCounterService::dayCounterAddExt($this->taeCounterType
            , $qq, 1, "_".$this->voteType, 1, $limits['oneday']);
            
            if ($taeCounterResult["retcode"] != 0){
                $this->throwException($config['code']['VOTE_HAVE_VOTED']);
            }else{
                $this->counterStack[] = array("type" => "day", "strkey" => "_".$this->voteType);
            }
        }

        if ($limits['onedayPerObject'] != 0)
        {
            $taeCounterResult = TaeCounterService::dayCounterAddExt($this->taeCounterType
            , $qq, 1, "_".$this->voteType."_".$vid, 1,  $limits['onedayPerObject']);

            if ($taeCounterResult["retcode"] != 0){
                $this->throwException($config['code']['VOTE_HAVE_VOTED']);
            }else{
                $this->counterStack[] = array("type" => "day", "strkey" => "_".$this->voteType."_".$vid);
            }
        }

        if ($limits['total'] != 0)
        {
            $taeCounterResult = TaeCounterService::counterAddExt($this->taeCounterType
            , $qq, 1, 0, "_".$this->voteType, 1,  $limits['total']);

            if ($taeCounterResult["retcode"] != 0){
                $this->throwException($config['code']['VOTE_HAVE_VOTED']);
            }else{
                $this->counterStack[] = array("type" => "all", "strkey" => "_".$this->voteType);
            }
        }

        if ($limits['totalPerObject'] != 0)
        {
            $taeCounterResult = TaeCounterService::counterAddExt($this->taeCounterType
            , $qq, 1, 0, "_".$this->voteType."_".$vid, 1,  $limits['totalPerObject']);
            if ($taeCounterResult["retcode"] != 0){
                $this->throwException($config['code']['VOTE_HAVE_VOTED']);
            }else{
                $this->counterStack[] = array("type" => "all", "strkey" => "_".$this->voteType."_".$vid);
            }
        }
    }

    public function handleEvilCheck($qq)
    {
        if (empty($this->config))
        {
            $this->_getConfig($this->configPath);
        }
        $evilEnable = $this->config["evilCheck"]["enable"];
        if($evilEnable)
        {
            $result = TaeEvilCheck::check();
            
            if($result['detail_code'] != 0){
                $this->throwException($config['code']['VOTE_EVIL_CHECK']);
            }
        }
    }
    
    /**
     * 执行投票
     *
     * usage:
     * <code>
     * $r = TMVoteComponent::getInstance()->vote($request); //使用配置文件 ROOT_PATH . "components/vote/config/vote.yml"
     * $r = TMVoteComponent::getInstance('vote2')->vote($request); //使用配置文件 ROOT_PATH . "components/vote/config/vote2.yml"
     * $r = TMVoteComponent::getInstance()->vote($request, $qq);
     * $r = TMVoteComponent::getInstance('vote2')->vote($request, $qq);
     * </code>
     *
     * @param TMWebRequest $request
     * @param string $qq 投票者的QQ号码，如果传入的投票者的QQ号码不为空，本程序将不再获取投票用户的QQ
     * @return string json result
     */
    public function vote($request, $qq='')
    {
        if (empty($this->config))
        {
            $this->_getConfig($this->configPath);
        }

        $config = $this->config;
        
        if (!empty($config['historyTable']))
        {
            $this->setHistoryTable($config['historyTable']);
        }
        
        if (!empty($config['voteType']))
        {
            $this->setVoteType((int)$config['voteType']);
        }

        if (!empty($config['date']))
        {
            $this->checkExpired($config['date'], $config);
        }

        if (empty($qq))
        {
            try
            {
                $qq = TMAuthUtils::getUin(TMConfig::get("appid"));
            }
            catch (TMException $te)
            {
                $this->throwException($config['code']['VOTE_NOLOGIN']);
            }
        }

        //开始投票
        TMTrackUtils::trackAction($qq, 6000121);       
        
        if (!isset($config['needVerifyCode']) || $config['needVerifyCode']) {
            $vKeyParameterName = empty($config['parameterNames']['verifycode']) ? 'verifycode' : $config['parameterNames']['verifycode'];
            $vkey = $request->getPostParameter($vKeyParameterName, '');
            if (TMAuthUtils::verifyVkey($vkey, TMConfig::get("appid")) == false)
            {
                $this->throwException($config['code']['VOTE_ERROR_VERIFY']);
            }
        }
        
        $parameterName = empty($config['parameterNames']['des']) ? 'vid' : $config['parameterNames']['des'];
        $vid = $request->getPostParameter($parameterName, '');
        if ($config['voteDesType'] == "qq")
        {
            $checkFun = 'checkQQ';
        }
        else
        {
            $checkFun = 'checkId';
        }
        if (empty($vid) || !TMFilterUtils::$checkFun($vid))
        {
            $this->throwException($config['code']['VOTE_ERROR_ID']);
        }

        $tmService = new TMService();

        //不允许给自己投票
        if (isset($config['voteSelf']) && $config['voteSelf'] == 0)
        {
            if ($config['conditionField'] == "FQQ")
            {
                if ($qq == $vid)
                {
                    $this->throwException($config['code']['VOTE_SELF_NOT_ALLOWED']);
                }
            }
            else
            {
                $rows = $tmService->select(array($config['conditionField']=>$vid), 'FQQ', $config['table'], 0, 1);
                if (!empty($rows) && !empty($rows[0]['FQQ']) && $rows[0]['FQQ'] == $qq)
                {
                    $this->throwException($config['code']['VOTE_SELF_NOT_ALLOWED']);
                }
            }
        }

        $arr = $this->isSpecialUser($qq, $config);
        $count = $arr['count'];
        $limits = $arr['limits'];
        if(!$arr['isSpecial'])
        {
            $this->handleEvilCheck($qq);
        }
        try{
            $this->checkLimited($qq, $vid, $limits, $config);
        }catch(TMVoteComponentException $ve)
        {
            $this->rollBackCounter($qq);
            throw $ve;
        }

        if (empty($count))
        {
            $this->throwException($config['code']['VOTE_ERROR_COUNT']);
        }

        //更新投票域
        $quote = "'";
        if (in_array(strtolower($config['conditionType']), array("int","float","double")))
        {
            $quote = "";
        }
        $update_where_str = $config['conditionField'] . "=" . $quote . $vid . $quote;
        $update_array[$config['countField']] = "+" . $count;
        $tmService->operateState($update_array, $config['table'], $update_where_str);

        //额外处理
        if (!empty($config['hooks']) && !empty($config['hooks']['afterVote']))
        {
            $hook = $config['hooks']['afterVote'];
            $handler = new $hook['className'];
            call_user_func_array(array($handler, $hook['functionName']), array($qq, $vid, $count));
        }

        //更新vote history
        $voteHistoryFields = array('FType'=>$this->voteType, 'FSrcQQ'=>$qq, 'FSrcId'=>$qq, 'FVoteCounts'=>$count, 'FIp'=>TMUtil::getClientIp());
        if ($config['voteDesType'] == "qq")
        {
            $voteHistoryFields['FDesQQ'] = $vid;
        }
        else
        {
            $voteHistoryFields['FDesId'] = $vid;
        }
        TMService::setTimeForUpdateOrInsert($voteHistoryFields);
        TMService::setDateForUpdateOrInsert($voteHistoryFields);
        $tmService->insert($voteHistoryFields, $this->historyTable);
        
        //投票成功
        TMTrackUtils::trackAction($qq, 6000105);
        
        $code = $config['code']['VOTE_SUCCESS'];
        return json_encode(array("code"=>$code, "message"=>$config['messages'][$code], "qq" => $qq, "vid" => $vid, "count" => $count));
    }

    /**
     * 回滚计数
     */
    protected function rollBackCounter($qq)
    {
        $rollBackArray = $this->counterStack;
        foreach($rollBackArray as $rollBack)
        {
            $type = $rollBack["type"];
            if($type == "day")
            {
                TaeCounterService::dayCounterAddExt($this->taeCounterType
            , $qq, -1, $rollBack["strkey"], TaeCounterService::STRICT_MAX, 0);
            }else if($type == "all")
            {
                TaeCounterService::counterAddExt($this->taeCounterType
            , $qq, -1, 0, $rollBack["strkey"], TaeCounterService::STRICT_MAX, 0);
            }
        }
    }
}
