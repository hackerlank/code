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
 * The service for user database
 *
 * @package sdk.mvc.src.framework.service
 * @author  ianzhang <ianzhang@tencent.com>
 * @version UserService.class.php 2008-9-8 by ianzhang
 */
class UserService
{
    /**
     * 根据策略获取yml的配置
     *
     * @param array $configs 配置的引用传值
     */
    private function _getScoreConfig(& $configs)
    {
        //解析yml
        $configScore = TMBasicConfigHandle::getInstance()->execute(ROOT_PATH . 'config/score.yml');
        if (isset($configScore[$configs['category']]))
        {
            $setting = $configScore[$configs['category']];
            if (!is_numeric($setting))
            {
                $configs['score'] = intval($setting['value']);
                if (isset($setting['limit'])) {
                    if (!empty($setting['limit']['oneday']))
                    {
                        $configs['limitOneday'] = $setting['limit']['oneday'];
                    }
                    if (!empty($setting['limit']['total']))
                    {
                        $configs['limitTotal'] = $setting['limit']['total'];
                    }
                }
                if (isset($setting['db']))
                {
                    if (!empty($setting['db']['table']))
                    {
                        $configs['targetTable'] = $setting['db']['table'];
                    }
                    if (!empty($setting['db']['field']))
                    {
                        $configs['targetField'] = $setting['db']['field'];
                    }
                    if (!empty($setting['db']['detailTable']))
                    {
                        $configs['detailTable'] = $setting['db']['detailTable'];
                    }
                }
            }
            else
            {
                $configs['score'] = (int) $setting;
            }
        }
        else
        {
            throw new TMConfigException('The strategy '.$num.' is not definded in config/score.yml');
        }
    }

    /**
     * 检查添加积分次数是否超过限制
     *
     * @param TMService tmService
     * @param string|ing $qq 被加分人的QQ号码
     * @param string $date 当前日期
     * @param array $configs 加分配置
     * @return boolean|string boolean: true 不受限制，string：加分失败，返回失败原因
     */
    private function _checkLimits($tmService, $qq, $date, $configs)
    {
        if (!empty($configs['category']) && !empty($configs['limitOneday']))
        {
            $count = $tmService->getCount(array("FDesQQ"=>$qq, "FCategory"=>$configs['category'], "FDate"=>$date), $configs['detailTable'], true);
            if ($count >= (int)$configs['limitOneday'])
            {
                return "outof oneday limit";
            }
        }

        if (!empty($configs['category']) && !empty($configs['limitTotal']))
        {
            $count = $tmService->getCount(array("FDesQQ"=>$qq, "FCategory"=>$configs['category']), $configs['detailTable'], true);
            if ($count >= (int)$configs['limitTotal'])
            {
                return "outof total limit";
            }
        }

        return true;
    }

    /**
     * 积分变动
     * 返回值为true表示正常加减积分
     * 返回值为false表示积分不足
     *
     * @param string $qq 被加分用户的QQ
     * @param int|string|array $num 分数 或者 策略，如果是整型则为分数，如果是字符串则为config/score.yml中配置的策略，如果是数组，则格式为array("category"=>"xxx","score"=>123)
     * @param string $memo 设置memo字段
     * @return array 成功 array("success"=>true,"num"=>$num) 失败 array("success"=>false,"message"=>$message);
     * @throws TMConfigException
     */
    public function addScore($qq, $num, $memo='')
    {
        //默认配置
        $configs = array(
            "score"         => 0,
            "category"      => '',
            "limitOneday"   => 0,
            "limitTotal"    => 0,
            "targetTable"   => "Tbl_User",
            "targetField"   => "FScoreCount",
            "detailTable"   => "Tbl_ScoreDetail"
            );

        //添加积分前的hook
        TMHook::call("before_addScore", array($qq, $num, $memo));

        //设置积分策略
        if (is_array($num))
        {
            $configs['category'] = $num['category'];
            $configs['score'] = (int) $num['score'];
        }
        elseif (!is_numeric($num))
        {
            $configs['category'] = $num;
            $this->_getScoreConfig($configs);
        }
        else
        {
            $configs['score'] = (int) $num;
        }

        $tmService = new TMService();
        $time = date("Y-m-d H:i:s");
        $date = substr($time, 0, 10);

        //检查是否超出加分限制
        $check = $this->_checkLimits($tmService, $qq, $date, $configs);
        if ($check !== true)
        {
            return array("success"=>false, "message"=>$check);
        }

        //如果是负分，检查积分是否足够
        if ($configs['score'] < 0)
        {
            $result = $tmService->selectForUpdate(array("FQQ"=>$qq), $configs['targetField'], $configs['targetTable']);
            if ($result[0][$configs['targetField']] + $configs['score'] < 0)
            {
                return array("success"=>false,"message"=>"not enough score.");
            }
            $sNum = $configs['score'];
        }
        else
        {
            $sNum = "+" . $configs['score'];
        }

        //积分操作
        $tmService->operateState(array($configs['targetField']=>$sNum), $configs['targetTable'], "FQQ='$qq'");

        //记录明细
        $detailData = array(
            "FDesQQ"        => $qq,
            "FCategory"     => $configs['category'],
            "FScore"        => $configs['score'],
            "FStatus"       => 2,
            "FTime"         => $time,
            "FDate"         => $date,
            "FIp"           => TMUtil::getClientIp(),
            "FMemo"         => $memo
            );
        $tmService->insert($detailData, $configs['detailTable']);
        return array("success"=>true,"num"=>$configs['score']);
    }
}