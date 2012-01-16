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
 * 操作积分的类，包括增加和扣除积分
 *
 * @package sdk.mvc.src.framework.service
 * @author  ianzhang <ianzhang@tencent.com>
 * @version UserService.class.php 2008-9-8 by ianzhang
 */
class ScoreTaeService
{
    
    protected $taeCounterType = 103;
    
    protected $counterStack = array();
    
    /**
     * 根据策略获取yml的配置
     *
     * @param array $configs 配置的引用传值
     */
    private function _getScoreConfig(& $configs)
    {
        //解析yml
        $configScore = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH . 'config/score.yml');

        //如果策略名不为空，则获取策略设置
        if (!empty($configs['strategy']))
        {
            //如果积分为0，或者为空，则必须有策略配置并且策略积分不为0或空
            if (empty($configs['score']) && (empty($configScore['strategies']) || empty($configScore['strategies'][$configs['strategy']]) || (is_array($configScore['strategies'][$configs['strategy']]) && empty($configScore['strategies'][$configs['strategy']]['value']))))
            {
                TMDebugUtils::debugLog('The strategy ' . $configs['strategy'] . ' is not definded in config/score.yml');
                throw new TMConfigException('The strategy ' . $configs['strategy'] . ' is not definded in config/score.yml');
            }

            if (!empty($configScore['strategies']) && isset($configScore['strategies'][$configs['strategy']]))
            {
                $strategy = $configScore['strategies'][$configs['strategy']];
                //如果策略配置不是一个数值
                if (!is_numeric($strategy))
                {
                    //如果还没有设置积分，则应用策略的积分
                    $configs['score'] = empty($configs['score']) ? intval($strategy['value']) : $configs['score'];
                    //判断策略的限制
                    if (isset($strategy['limit'])) {
                        if (!empty($strategy['limit']['oneday']))
                        {
                            $configs['limitOneday'] = $strategy['limit']['oneday'];
                        }
                        if (!empty($strategy['limit']['total']))
                        {
                            $configs['limitTotal'] = $strategy['limit']['total'];
                        }
                        //TODO 增加更多的限制……
                    }
                }
                else
                {
                    $configs['score'] = empty($configs['score']) ? intval($strategy) : $configs['score'];
                }
            }
        }

        //获取数据存储别名设置
        if (!empty($configScore['dataAlias']) && isset($configScore['dataAlias'][$configs['dataAlias']]))
        {
            $dataAlias = $configScore['dataAlias'][$configs['dataAlias']];
            if (empty($dataAlias['target']) || empty($dataAlias['detail']))
            {
                TMDebugUtils::debugLog('The target or detail of data alias ' . $configs['dataAlias'] . ' is empty in config/score.yml');
                throw new TMConfigException('The target or detail of data alias ' . $configs['dataAlias'] . ' is empty in config/score.yml');
            }
            $configs['alias']['target'] = $dataAlias['target'];
            $configs['alias']['keyField'] = empty($dataAlias['keyField']) ? 'FQQ' : $dataAlias['keyField'];
            $configs['alias']['desType'] = empty($dataAlias['desType']) ? 'qq' : $dataAlias['desType'];
            $configs['alias']['detail'] = $dataAlias['detail'];
            $configs['alias']['scoreField'] = empty($dataAlias['scoreField']) ? 'FScoreDetail' : $dataAlias['scoreField'];
        }
        else
        {
            TMDebugUtils::debugLog('The data alias ' . $configs['dataAlias'] . ' is not definded in config/score.yml');
            throw new TMConfigException('The data alias ' . $configs['dataAlias'] . ' is not definded in config/score.yml');
        }
    }

    /**
     * 检查添加积分次数是否超过限制
     *
     * @param string|int $des 被加分对象的逻辑主键值 qq or id
     * @param array $configs 加分配置
     * @return boolean|string boolean: true 不受限制，string：加分失败，返回失败原因
     */
    private function _checkLimits($des, $configs)
    {
        if (!empty($configs['strategy']) && !empty($configs['limitOneday']))
        {
            $taeCounterResult = TaeCounterService::dayCounterAddExt($this->taeCounterType
            , $des, 1, "_".$configs['strategy'], 1, (int)$configs['limitOneday']/$configs["value"]);
            if ($taeCounterResult["retcode"] != 0){
                return "out of oneday limit";
            }else{
                $this->counterStack[] = array("type" => "day", "key" => $des, "strkey" => "_".$configs['strategy']);
            }
        }

        if (!empty($configs['strategy']) && !empty($configs['limitTotal']))
        {
            $taeCounterResult = TaeCounterService::counterAddExt($this->taeCounterType
            , $des, 1, "_".$configs['strategy'], 1, (int)$configs['limitTotal']/$configs["value"]);
            
            if ($taeCounterResult["retcode"] != 0){
                $this->rollBackCounter();
                return "out of total limit";
            }else{
                $this->counterStack[] = array("type" => "all", "key" => $des, "strkey" => "_".$configs['strategy']);
            }
        }

        return true;
    }

    /**
     * 回滚计数
     */
    protected function rollBackCounter()
    {
        $rollBackArray = $this->counterStack;
        foreach($rollBackArray as $rollBack)
        {
            $type = $rollBack["type"];
            if($type == "day")
            {
                TaeCounterService::dayCounterAddExt($this->taeCounterType
            , $rollBack["key"], -1, $rollBack["strkey"], TaeCounterService::STRICT_MAX, 0);
            }else if($type == "all")
            {
                TaeCounterService::counterAddExt($this->taeCounterType
            , $rollBack["key"], -1, 0, $rollBack["strkey"], TaeCounterService::STRICT_MAX, 0);
            }
        }
    }
    
    /**
     * 积分变动
     * 返回值为true表示正常加减积分
     * 返回值为false表示积分不足
     *
     * @param string|int $des 被加分对象的逻辑主键值 qq or id
     * @param string $dataAlias 保存数据使用的别名
     * @param int|string|array $strategy 分数|策略|分数及策略数组，如果是整型则为分数，如果是字符串则为config/score.yml中配置的策略，如果是数组，则格式为array("strategy"=>"xxx","score"=>123)
     * @param array $options 数据库字段的键值对
     * @param string|array $detailOptions 如果是字符串则为FMemo，数组则是数据库字段的键值对
     * @return array 成功 array("success"=>true,"num"=>$num) 失败 array("success"=>false,"message"=>$message);
     * @throws TMConfigException
     */
    public function add($des, $dataAlias, $strategy, $options=array(), $detailOptions = array())
    {
        //默认配置
        $configs = array(
            "score"         => 0,
            "strategy"      => '',
            "limitOneday"   => 0,
            "limitTotal"    => 0,
            "dataAlias"     => $dataAlias
            );

        //添加积分前的hook
        TMHook::call("before_addScore", array($des, $strategy, $options, $detailOptions));

        //设置积分策略
        if (is_array($strategy))
        {
            $configs['strategy'] = $strategy['strategy'];
            $configs['score'] = (int) $strategy['score'];
        }
        elseif (!is_numeric($strategy))
        {
            $configs['strategy'] = $strategy;
        }
        else
        {
            $configs['score'] = (int) $strategy;
        }

        $this->_getScoreConfig($configs);

        $time = date("Y-m-d H:i:s");
        $date = substr($time, 0, 10);

        //检查是否超出加分限制
        $check = $this->_checkLimits($des, $configs);
        if ($check !== true)
        {
            TMDebugUtils::debugLog('Add score failed: '. $check);
            return array("success"=>false, "message"=>$check);
        }
        
        $targetObj = new TMObject($configs['alias']['target']);
        //积分操作
        $targetObj->set("scoreVal", $configs['score']);
        $targetObj->set("scoreType", $configs['alias']['scoreField']);
        $targetObj->set("toUin", $des);
        foreach($options as $key => $value)
        {
            $targetObj->set($key, $value);
        }
        TMHook::call("before_saveScore", array($des, $configs, $targetObj, $dataAlias, $strategy, $options, $detailOptions));
        try{
            $targetObj->save();
        }catch(TMDAOException $tde)
        {
            if($tde->getCode() == -2046)
            {
                TMDebugUtils::debugLog('Add score failed: not enough score.');
                return array("success"=>false,"message"=> "not enough score", "code" => $tde->getCode());
            }
            return array("success"=>false,"message"=> $tde->getMessage(), "code" => $tde->getCode());
        }

        $desField = $configs['alias']['desType'] == 'qq' ? 'FDesQQ' : 'FDesId';

        //记录明细
        $detailData = array(
            $desField       => $des,
            "FStrategy"     => $configs['strategy'],
            "FScore"        => $configs['score'],
            "FStatus"       => 2,
            "FTime"         => $time,
            "FDate"         => $date,
            "FIp"           => TMUtil::getClientIp()
            );
        if (is_string($detailOptions)) {
            $detailData['FMemo'] = $detailOptions;
        } elseif (is_array($detailOptions)) {
            foreach ($detailOptions as $fieldName => $fieldValue) {
                $detailData[$fieldName] = $fieldValue;
            }
        }
        $detailObj = new TMObject($configs['alias']['detail']);
        $detailObj->setAll($detailData);
        $detailObj->save();

        return array("success"=>true,"num"=>$configs['score']);
    }
}