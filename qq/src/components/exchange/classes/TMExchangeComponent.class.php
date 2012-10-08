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
 * TMExchangeComponent 积分兑换奖品接口
 *
 * @package components.exchange.classes
 * @author  lynkli <lynkli@tencent.com>
 * @version TMExchangeComponent.class.php 2010-03-01 by lynkli
 */
class TMExchangeComponent
{
    private $config = array();
    private static $instances = array();
    private $configPath;
    private $componentDir;
    private $historyTable = 'Tbl_ExchangeHistory';
    
    /**
     * 获取兑换句柄
     *
     * @param string $app 应用标致
     * @return TMExchangeComponent
     */
    public static function getInstance($app='exchange')
    {
        if (!isset(self::$instances[$app]))
        {
            $class = __CLASS__;
            self::$instances[$app] = new $class($app);
        }

        return self::$instances[$app];
    }

    /**
     * get the exchange settings
     *
     * @param string $path 配置文件地址
     */
    private function __construct($app)
    {
        $this->componentDir = TMDispatcher::getComponentsDir('exchange');
        $this->configPath = $this->componentDir . 'config/' . $app . '.yml';
    }

    /**
     * 获取兑换的配置
     * @param string $path 配置文件的路径
     */
    private function _getConfig($path)
    {
        if (!file_exists($path))
       {
            $content = json_encode(array("code"=> 99, "message"=>"component app config file ($path) does not exist"));
            throw new TMExchangeComponentException($content);
        }
        $this->config = TMBasicConfigHandle::getInstance()->execute($path);
    }

    /**
     * 设置兑换明细表的表名
     * 默认的表名为 Tbl_VoteHistory
     *
     * @param string $tbl 表名
     */
    public function setHistoryTable($tbl)
    {
        $this->historyTable = $tbl;
    }

    /**
     * 根据错误码抛出对应的异常
     *
     * @param int $code 错误代码
     */
    private function throwException($code, $message='')
    {
        $config = $this->config;
        $message = !empty($message) ? $message : $config['messages'][$code];
        $content = json_encode(array("code"=>$code, "message"=>$message));
        throw new TMExchangeComponentException($content);
    }

    /**
     * 判断兑换是否过期
     *
     * @param array $range [datestart, dateend]
     * @param array $config 兑换配置
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
                $this->throwException($config['code']['EXG_NOT_BEGIN']);
            }
        }

        if (!empty($dateMax))
        {
            $timeMax = strtotime($dateMax);
            if ($timeNow > $timeMax)
            {
                $this->throwException($config['code']['EXG_EXPIRED']);
            }
        }
    }
    
    /**
     * 判断兑换是否过期
     *
     * @param array $ranges [{start, end},...]
     * @param array $config 兑换配置 
     */
    private function checkInTime($ranges, $config)
    {
        $timeNow = time();
        $date = date("Y-m-d ");
        foreach ($ranges as $range) {
            $start = empty($range['start']) ? "00:00:00" : $range['start'];
            $end = empty($range['end']) ? "23:59:59" : $range['end'];

            $timeStart = strtotime($date.$start);
            $timeEnd = strtotime($date.$end);

            if ($timeNow >= $timeStart && $timeNow <= $timeEnd)
            {
                return true;
            }
        }
        
        $this->throwException($config['code']['EXG_NOT_IN_TIME']);
    }

    /**
     * 判断是否超出兑换限制
     *
     * @param string $qq 兑换者QQ号码
     * @param int $vid 被兑换的Id或者QQ
     * @param array $limits 兑换限制数，包括oneday:每天的兑换限制数，
     * @param array $config 兑换配置数组
     */
    private function checkLimited($qq, $exgId)
    {
        $service = new TMService();
        
        $config = $this->config;
        $limits = $config['items'][$exgId]['limits'];
        $date = date("Y-m-d");
        
        if (!empty($limits['times']))
        {
            $count = $service->getCount(array("FQQ"=>$qq, 'FItem'=>$exgId), $this->historyTable, true);
            if ($count >= $limits['times'])
            {
                $code = $config['code']['EXG_EXCEED_TIMES'];
                $message = sprintf($config['messages'][$code], $limits['times']);
                $this->throwException($code, $message);
            }
        }

        if (!empty($limits['timesOneday']))
        {
            $count = $service->getCount(array("FQQ"=>$qq, 'FItem'=>$exgId, 'FDate'=>$date), $this->historyTable, true);
            if ($count >= $limits['timesOneday'])
            {
                $code = $config['code']['EXG_EXCEED_TIMES_ONEDAY'];
                $message = sprintf($config['messages'][$code], $limits['timesOneday']);
                $this->throwException($code, $message);
            }
        }

        if (!empty($limits['items']))
        {
            $count = $service->getCount(array('FItem'=>$exgId), $this->historyTable, true);
            if ($count >= $limits['items'])
            {
                $code = $config['code']['EXG_EXCEED_ITEMS'];
                $message = sprintf($config['messages'][$code], $limits['items']);
                $this->throwException($code, $message);
            }
        }

        if (!empty($limits['itemsOneday']))
        {
            $count = $service->getCount(array('FItem'=>$exgId, 'FDate'=>$date), $this->historyTable, true);
            if ($count >= $limits['itemsOneday'])
            {
                $code = $config['code']['EXG_EXCEED_ITEMS_ONEDAY'];
                $message = sprintf($config['messages'][$code], $limits['itemsOneday']);
                $this->throwException($code, $message);
            }
        }
    }

    /**
     * 执行积分兑换
     *
     * @param TMWebRequest $request
     * @param string $qq 兑换者的QQ号码，如果传入的兑换者的QQ号码不为空，本程序将不再获取兑换用户的QQ
     * @return string json result
     */
    public function exchangeByScore($request, $qq='')
    {
        //获取配置
        if (empty($this->config))
        {
            $this->_getConfig($this->configPath);
        }

        $config = $this->config;
        
        //设置兑换记录表
        if (!empty($config['historyTable']))
        {
            $this->setHistoryTable($config['historyTable']);
        }

        //判断整体兑换日期
        if (!empty($config['date']))
        {
            $this->checkExpired($config['date'], $config);
        }
        
        //判断是否在兑换的时段内
        if (!empty($config['time']))
        {
            $this->checkInTime($config['time'], $config);
        }

        //获取兑换用户QQ号
        if (empty($qq))
        {
            try
            {
                $qq = TMAuthUtils::getUin(TMConfig::get("appid"));
            }
            catch (TMException $te)
            {
                $this->throwException($config['code']['EXG_NOLOGIN']);
            }
        }

        //获取兑换码
        $vKeyParameterName = empty($config['parameterNames']['verifycode']) ? 'verifycode' : $config['parameterNames']['verifycode'];
        $vkey = $request->getPostParameter($vKeyParameterName, '');
        if (TMAuthUtils::verifyVkey($vkey, TMConfig::get("appid")) == false)
        {
            $this->throwException($config['code']['EXG_ERROR_VERIFY']);
        }
        
        //获取兑换物品id
        $parameterName = empty($config['parameterNames']['des']) ? 'exgid' : $config['parameterNames']['des'];
        $exgId = $request->getPostParameter($parameterName, '');
        //判断兑换物品是否存在
        if (empty($exgId) || empty($config['items'][$exgId]))
        {
            $this->throwException($config['code']['EXG_ERROR_ITEM']);
        }

        if (!empty($config['items'][$exgId]['date']))
        {
            $this->checkExpired($config['items'][$exgId]['date'], $config);
        }
        
        if (!empty($config['items'][$exgId]['time']))
        {
            $this->checkInTime($config['items'][$exgId]['time'], $config);
        }
        
        $tmService = new TMService();
        
        //开启事务
        TransactionService::start();
        
        try {        
            //判断是否超出限制
            $this->checkLimited($qq, $exgId);

            if (!empty($config['items'][$exgId]['need']))
            {
                $scoreService = new ScoreService();
                $dataAlias = $config['dataAlias'];
                $r = $scoreService->add($qq, $dataAlias, $config['items'][$exgId]['need']);
                if (!$r['success'])
                {
                    $this->throwException($config['code']['EXG_NOT_ENOUGH_SCORE']);
                }
            }
            
            //额外处理
            if (!empty($config['hooks']) && !empty($config['hooks']['afterExchange']))
            {
                $hook = $config['hooks']['afterExchange'];
                $handler = new $hook['className'];
                call_user_func_array(array($handler, $hook['functionName']), array($qq, $exgId, $config));
            }
    
            //更新vote history
            $exgHistoryFields = array(
                'FQQ'   => $qq,
                'FItem' => $exgId,
                'FItemName' => empty($config['items'][$exgId]['name']) ? '' : $config['items'][$exgId]['name'],
                'FCode' => empty($config['items'][$exgId]['itemcode']) ? '' : $config['items'][$exgId]['itemcode'],
                'FIp'   => TMUtil::getClientIp()
                );
            TMService::setTimeForUpdateOrInsert($exgHistoryFields);
            TMService::setDateForUpdateOrInsert($exgHistoryFields);
            $tmService->insert($exgHistoryFields, $this->historyTable);
    
            TransactionService::commit();
            $code = $config['code']['EXG_SUCCESS'];
            return json_encode(array("code"=>$code, "message"=>$config['messages'][$code], "qq" => $qq, "exgid" => $exgId));
        } catch (TMExchangeComponentException $exge) {
            TransactionService::commit();
            return $exge->getMessage();
        } catch (TMException $te) {
            TransactionService::rollback();
            $code = $config['code']['EXG_SYSTEM_BUSY'];
            $message = $config['messages'][$code];
            return json_encode(array("code"=>$code,"message"=>$message));
        }
    }
}
