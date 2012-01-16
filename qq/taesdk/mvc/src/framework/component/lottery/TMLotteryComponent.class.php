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
 * 抽奖接口组件
 *
 * Usage：
 * <ul>
 *   <li>配置components/lottery/config/lottery.yml</li>
 *   <li>调用jslib1.4里面的抽奖方法</li>
 * </ul>
 *
 * @package components.lottery.classes
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMLotteryComponent.class.php 2011-08-22 by ianzhang
 */
class TMLotteryComponent
{
    const NOT_SEND = 1;
    const HAS_SEND = 2;
    const SEND_FAILED = 3;
    const AWARD_TYPE_MP = "mp";
    const AWARD_TYPE_SCORE = "score";
    const AWARD_TYPE_NONE = "none";
    const AWARD_TYPE_OTHER = "other";
    const AWARD_TYPE_ERROR = "error";
    
    const NOT_AWARD = 0;

    private $config = array();
    private $app = 'lottery';   
    
    private $taeCounterType = 104;
    
    protected $counterStack = array();
    
    /**
     * @var TMService $service
     */
    private $service = null;
    private $lotteryHistoryTbl = "Tbl_LotteryHistory";
    private static $instances = array();
    private $componentDir;

    /**
     * 获取TMLotteryComponent当前对象
     *
     * @param string $app draw application name, default with 'lottery'
     * @return TMLotteryComponent
     */
    public static function getInstance($app='lottery')
    {
        if(!isset(self::$instances[$app]))
        {
            $class = __CLASS__;
            self::$instances[$app] = new $class($app);
        }

        return self::$instances[$app];
    }
    
    /**
     * get the draw settings
     *
     * @param string $app draw application name
     */
    private function __construct($app)
    {
        $this->componentDir = TMDispatcher::getComponentsDir('lottery');
        $this->app = $app;
        $this->_getConfig($app);
        $this->service = new TMService();
    }

    /**
     * 根据设置随机抽取奖品
     *
     * @return array 抽奖结果数组
     */
    public function getAward()
    {
        try
        {            
            //获取抽奖用户的QQ号码，如果用户未登录则抛出TMNoLoginException
            $qq = TMAuthUtils::getUin();
            
            $this->handleVerifyCode();
            
            //根据概率随机抽取奖品，并检查奖品的合法性
            $item = $this->getAwardAndCheck($qq);

            //发送奖品
            if($item != self::NOT_AWARD){
                $this->sendAward($qq, $item);
            }
        
            return $this->formatResult($item);
        } catch(TMNoLoginException $nle) {
            return $this->formatResult(null, 'NOT_LOGIN');
        } catch (TMLotteryComponentException $ce) {
            $code = $ce->getCode();
            $code = !empty($code) ? $code : 'SYSTEM_BUSY';
            return $this->formatResult(null,  $code , $ce->getMessage());
        } catch (TMException $te) {
            return $this->formatResult(null, 'SYSTEM_BUSY');
        }
    }
    
    /**
     * 处理验证码
     * @throw TMLotteryComponentException
     */
    public function handleVerifyCode()
    {
        $needVerifyCode = $this->config["verifyCode"]["need"];
        if($needVerifyCode)
        {
            $key = $this->config["verifyCode"]["key"];
            $vKey = TMDispatcher::getInstance()->getRequest()->getParameter($key);
            if(!TMAuthUtils::verifyVkey($vKey))
            {
                $code = $this->config["code"]['ERROR_VERIFYCODE'];
                throw new TMLotteryComponentException($this->config["messages"][$code], $code);
            }
        }
    }
    
    /**
     * 根据概率随机抽取奖品，并检查奖品的有效性
     *
     * @param string|int $qq 用户QQ号码
     * @param array $config 抽奖配置
     * @param array $resultArr 抽奖结果，引用传值
     * @return int 0表示没中奖
     */
    public function getAwardAndCheck($qq)
    { 
        //判断是否在抽奖时间范围以内
        $datecheck = empty($this->config['date']) ? 0 : $this->_inDate($this->config['date']);
        if ($datecheck != 0) {
            if($datecheck == 1){
                $code = $this->config["code"]['HAS_ENDED'];
                throw new TMLotteryComponentException($this->config["messages"][$code], $code);
            }else{
                $code = $this->config["code"]['NOT_BEGIN'];
                throw new TMLotteryComponentException($this->config["messages"][$code], $code);
            }
        }
        
        //如果超过每天、总共可抽奖的次数
        if (!empty($this->config['limit'])) {
            try{
                $limit = $this->config['limit'];
                if(isset($limit)){
                    if (!empty($limit['day']))
                    {
                        $taeCounterResult = TaeCounterService::dayCounterAddExt($this->taeCounterType
                            , $qq, 1, "_".$this->config["lotteryId"]."_draw", TaeCounterService::STRICT_MAX, $limit['day']);
                        
                        if ($taeCounterResult["retcode"] != 0){
                            $code = $this->config["code"]['OUTOF_DRAW_ONEDAY_LIMIT'];
                            throw new TMLotteryComponentException($this->config["messages"][$code], $code);
                        }else{
                            $this->counterStack[] = array("type" => "day", "strkey" => "_".$this->config["lotteryId"]."_draw");
                        }
                    }
            
                    if (!empty($limit['total']))
                    {
                        $taeCounterResult = TaeCounterService::counterAddExt($this->taeCounterType
                            , $qq, 1, "_".$this->config["lotteryId"]."_draw", TaeCounterService::STRICT_MAX, $limit['total']);
                        
                        if ($taeCounterResult["retcode"] != 0){
                            $code = $this->config["code"]['OUTOF_DRAW_TOTAL_LIMIT'];
                            throw new TMLotteryComponentException($this->config["messages"][$code], $code);
                        }else{
                            $this->counterStack[] = array("type" => "total", "strkey" => "_".$this->config["lotteryId"]."_draw");
                        }
                    }
                }
            }catch(TMLotteryComponentException $lce)
            {
                $this->rollBackCounter($qq);
                throw $lce;
            }
        }
        
        //扣除积分，如果积分不够则抛出异常
        if (isset($this->config['score']['need'])
            && $this->config['score']['need'] == TRUE
            && !$this->_deductScore($qq, $this->config['score'])) {
            $code = $this->config["code"]["NOT_ENOUGH_SCORE"];
            throw new TMLotteryComponentException($this->config["messages"][$code], $code);
        }
        
        //随机获取一个奖项，如果没有则用默认奖项

        //TaeCore::taeInit(TaeConstants::SERVER_LIST, array(array("host" =>  "10.6.208.189", "port" =>  26000)));
        $taeLotteryResult = TaeDrawService::draw($this->config["lotteryId"]);

        if($taeLotteryResult["retcode"] == 0 && $taeLotteryResult['award_type'] != self::NOT_AWARD){
            //记录到抽奖记录表
            $historyId = $this->_recordAward($qq, $taeLotteryResult['award_type']);
            return $taeLotteryResult['award_type'];
        }

        return self::NOT_AWARD;
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
            }else if($type == "total")
            {
                TaeCounterService::counterAddExt($this->taeCounterType
            , $qq, -1, 0, $rollBack["strkey"], TaeCounterService::STRICT_MAX, 0);
            }
        }
    }
    
    /**
     * 扣除用户抽奖所需积分
     *
     * @param string $qq 用户QQ号码
     * @param array $needArray 抽奖所需积分
     * @return boolean
     */
    private function _deductScore($qq, $need)
    {
        if (is_array($need))
        {
            if(!isset($need["deductStrategy"]))
            {
                throw new TMLotteryComponentException("积分策略未配置");
            }
            $strategy = $need['deductStrategy'];
            if(!isset($need["dataAlias"]))
            {
                throw new TMLotteryComponentException("积分数据对象未配置");
            }
            $dataAlias = $need['dataAlias'];
        }else{
            throw new TMLotteryComponentException("积分策略未配置");
        }
        
        try{
            if(isset($need["transaction"]) && $need["transaction"] == TRUE){
                TransactionService::start();
            }
            $scoreService = new ScoreService();
            $result = $scoreService->add($qq, $dataAlias, $strategy);
            if(isset($need["transaction"]) && $need["transaction"] == TRUE){
                TransactionService::commit();
            }
        }catch(TMException $te)
        {
            TransactionService::rollback();
            return false;
        }
        
        return $result['success'];
    }
    
    /**
     * 插入获奖记录
     *
     * @param string $qq 中奖QQ
     * @param array $item 奖项信息。
     * @param return int, record id
     */
    private function _recordAward($qq, $item)
    {
        $code = $item;
        $type = $this->config["awardInfo"][$item]["type"];
        $group = $this->config["lotteryId"];
        $value = $this->config["awardInfo"][$item]["value"];
        $name = $this->config["awardInfo"][$item]["name"];

        //插入获奖记录
        $history = array(
            "FQQ"           => $qq,
            "FType"         => $type,
            "FCode"         => $code, //实际返回的奖品code
            "FGroup"        => $group,
            "FName"         => $name,
            "FValue"        => $value,
            "FIp"           => TMUtil::getClientIp()
            );
        TMService::setTimeForUpdateOrInsert($history);
        TMService::setDateForUpdateOrInsert($history);
        $this->service->insert($history, $this->lotteryHistoryTbl);
        return $this->service->getInsertId();
    }
    
    /**
     * 判断时间是否在奖品的限制时间内
     *
     * @param array $dateRange
     * @return bool 0: 在发放时间范围内，-1：还没有开始，1：已经结束
     */
    private function _inDate($dateRange)
    {
        $now = time();
        if (!empty($dateRange['start']))
        {
            $start = strtotime($dateRange['start']);
            if ($now < $start) {
                return -1;
            }
        }

        if (!empty($dateRange['end']))
        {
            $end = strtotime($dateRange['end']);
            if ($now > $end)
            {
                return 1;
            }
        }
        return 0;
    }
    
    /**
     * 发送奖品
     *
     * @param string|int $qq 用户QQ号码
     * @param int $item 抽奖配置
     * @param array $resultArr 抽奖结果
     * @return boolean
     */
    function sendAward($qq, $item)
    {
        if (!empty($item))
        {
            $type = $this->config["awardInfo"][$item]["type"];
            $value = $this->config["awardInfo"][$item]["value"];
            //如果默认奖项是积分，立即发送
            if ($type == self::AWARD_TYPE_SCORE)
            {
                $score = $value;
                return $this->_addScore($qq, $score);
            }
            //如果是QQ虚拟物品，记录到Tbl_QQShow，则判断是否需要实时发送
            else if ($type == self::AWARD_TYPE_MP)
            {
                if (empty($value))
                {
                    return false;
                }

                $sendStatus = self::NOT_SEND;
                if(isset($this->config["mp"]["send"]) && $this->config["mp"]["send"] == TRUE)
                {
                    $actId = $this->config["awardInfo"][$item]["actid"];
                    $itemId = $value;
                    $sendResult = TaeMPService::sendItem($qq, $actId, $itemId);
                    if($sendResult["retCode"] == 0)
                    {
                        $sendStatus = self::HAS_SEND;
                    }else{
                        $sendStatus = self::NOT_SEND;
                    }
                }

                //记录QQ虚拟物品到Tbl_QQshow表
                $this->_recordQQItem($qq, $value, $item, $sendStatus);
                
                return true;
            }
        }

        return false;
    }

    /**
     * 给用户添加所中积分
     *
     * @param string $qq 用户QQ号码
     * @param int|strategy $score 积分
     */
    private function _addScore($qq, $score)
    {
        try{
            $need = $this->config["score"];
            $dataAlias = $need["dataAlias"];
            
            if(isset($need["transaction"]) && $need["transaction"] == TRUE){
                TransactionService::start();
            }
            $scoreService = new ScoreService();
            $result = $scoreService->add($qq, $dataAlias, $score);
            
            if(isset($need["transaction"]) && $need["transaction"] == TRUE){
                TransactionService::commit();
            }
        }catch (TMException $ce)
        {   
            TransactionService::rollback();
            return false;
        }

        return $result['success'];
    }

    /**
     * 获取当前抽奖的配置
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * 设置抽奖历史记录表
     *
     * @param string $tbl 抽奖历史记录表名
     */
    public function setHistoryTbl($tbl)
    {
        $this->lotteryHistoryTbl = $tbl;
    }

    /**
     * 获取抽奖的配置
     * @param string $app 抽奖应用名
     */
    private function _getConfig($app)
    {
        $path = 'config/' . $app . '.yml';
        if(!is_file($this->componentDir . $path))
        {
            throw new TMLotteryComponentException("配置错误");
        }
        $this->config = TMBasicConfigHandle::getInstance()->execute($this->componentDir . $path);
        //如果配置为空，也算做异常行为
        if(empty($this->config))
        {
            throw new TMLotteryComponentException("配置错误"); 
        }
    }

    /**
     * 记录QQ虚拟物品到Tbl_QQshow表
     *
     * @param string $qq 用户QQ号码
     * @param string $itemToSend 虚拟物品item id
     * @param int $code 奖品id
     * @param int $hasSendQQItem 是否已经发送
     */
    private function _recordQQItem($qq, $itemToSend, $code, $hasSendQQItem)
    {
        $qqshow = array(
            "FQQ"        => $qq,
            "FItemNo"    => $itemToSend,
            "FCode"      => $code,
            "FStatus"    => $hasSendQQItem,
            "FActId"     => $this->config["awardInfo"][$code]["actid"]
            );
        if ($hasSendQQItem == self::HAS_SEND)
        {
            TMService::setTimeForUpdateOrInsert($qqshow, "FDealTime");
        }
        TMService::setTimeForUpdateOrInsert($qqshow);
        TMService::setDateForUpdateOrInsert($qqshow);
        $this->service->insert($qqshow, "Tbl_QQshow");
    }
    
    protected function formatResult($item, $error=0, $message='')
    {
        $config = $this->config;
        
        $errorCode = is_numeric($error) ? $error : $config['code'][$error];
        
        if (empty($message)) {
            if(isset($this->config["awardInfo"][$item]))
            {
                if(isset($this->config["awardInfo"][$item]["message"])){
                    $message = $this->config["awardInfo"][$item]["message"];
                }else{
                    $name = $this->config["awardInfo"][$item]["name"];
                    $message = $name;
                }
            }else{
                if(isset($config['messages'][$errorCode])){
                    $message = $config['messages'][$errorCode];
                }
            }
        }

        return array(
            'item'      => $item,
            'error'    => $errorCode,
            'message'  => $message,
        );
    }
}