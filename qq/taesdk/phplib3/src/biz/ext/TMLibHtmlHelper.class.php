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
 * Html helper
 *
 * @package sdk.lib3.src.biz.ext
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMHtmlHelper.class.php 2010-3-4 by ianzhang
 */
class TMLibHtmlHelper extends TMHelper{
    /**
     * 展示qq video
     *
     *  @param string $divid  qqvideo需要加载的div的id
     *  @param string $vid    qqvideo的视频vid
     *  @param string $width  加载视频的width
     *  @param string $height 加载视频的height
     *  @param string $auto   是否自动播放，默认1为自动播放，0为不自动播放
     *
     */
    public static function showQQVideo($divId,$vid,$width,$height,$auto="1") {
        echo <<<EOF
<script language="javascript" type="text/javascript" src="js/swf/swfobject.js"></script>
<script language="javascript" type="text/javascript">
    var s1 = new SWFObject("http://cache.tv.qq.com/qqplayerout.swf","player","{$width}","{$height}","9","#000000");
    s1.addParam("allowfullscreen","true");
    s1.addParam("allowscriptaccess","sameDomain");
    s1.addParam("wmode","transparent");
    s1.addParam("flashvars","vid={$vid}&amp;cgi=http%3A//video.qq.com/bin/vrank%3Ftype%3D8%26start%3D49%26end%3D56&auto={$auto}");
    s1.write("{$divId}");
    function changeQQVideo(divId,vid) {
        s1.addParam("flashvars","vid="+vid+"&amp;cgi=http%3A//video.qq.com/bin/vrank%3Ftype%3D8%26start%3D49%26end%3D56&auto={$auto}");
        s1.write(divId);
    }
</script>
EOF;
    }

    /**
     * 展示 flash
     *
     *  @param string $flashAddress  Flash的地址
     *  @param string $flashId    Flash的Id
     *  @param int $width  Flash的宽
     *  @param int $height Flash的高
     *  @param string $wmode   wmode的模式设置
     *
     */
    public static function showFlash($flashAddress, $flashId, $width, $height, $wmode = 'transparent') {
        echo <<<EOF
<object id="$flashId" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="$width" height="$height">
      <param name="movie" value="$flashAddress" />
      <param name="quality" value="high" />
      <param name="wmode" value="$wmode" />
      <param name="swfversion" value="9.0.45.0" />
      <!-- 此 param 标签提示使用 Flash Player 6.0 r65 和更高版本的用户下载最新版本的 Flash Player。如果您不想让用户看到该提示，请将其删除。 -->

      <!-- 下一个对象标签用于非 IE 浏览器。所以使用 IECC 将其从 IE 隐藏。 -->
      <!--[if !IE]>-->
      <object type="application/x-shockwave-flash" data="$flashAddress" width="$width" height="$height">
        <!--<![endif]-->
        <param name="quality" value="high" />
        <param name="wmode" value="$wmode" />
        <param name="swfversion" value="9.0.45.0" />
        <!-- 浏览器将以下替代内容显示给使用 Flash Player 6.0 和更低版本的用户。 -->

        <div>
          <h4>此页面上的内容需要较新版本的 Adobe Flash Player。</h4>
          <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="获取 Adobe Flash Player" width="112" height="33" /></a></p>
        </div>
        <!--[if !IE]>-->
      </object>
      <!--<![endif]-->
    </object>
EOF;
    }

    /**
     * 方便得生成邀请链接
     *
     * @param string $url 基础Url
     * @param int $qq QQ号码
     * @param int $type 类新1为好友邀请，其他请参考http://huodong.addev.com/con/website/act/monitor
     * @param int $cpid 活动id
     * @return string
     */
    public static function urlJump($url, $qq = '', $type = 1, $cpid)
    {
        return $url = "http://jump.t.l.qq.com/ping?target=".urlencode($url)."&cpid=".$cpid."&type=$type&fromqq=$qq";
    }
}