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
class TMActionTrackDebugger extends TMAbstractDebugger{
	protected static $trackArray = array();

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->name = "actionTrack";
    }
	
	public function add($qq, $actionId, $campaignId, $reserve)
	{
		$tmp = array();
		$tmp["qq"] = $qq;
		$tmp["actionId"] = $actionId;
		$tmp["campaignId"] = $campaignId;
		$tmp["reserve"] = $reserve;
		self::$trackArray[] = $tmp;
	}
	
	public static function getDebuggerTracks()
	{
		return self::$trackArray;
	}
}
?>