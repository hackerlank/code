<?php

/*
 * ---------------------------------------------------------------------------
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
 * ---------------------------------------------------------------------------
 */

/**
 * 安全扫描类
 *
 * 开关：config/filter.yml - TMSafeScanFilter
 *
 * @package sdk.mvc.src.framework.filter
 * @author  gastonwu <gastonwu@tencent.com>
 */
class TMSafeScanFilter extends TMFilter {

    /**
     * 执行安全扫描逻辑
     *
     * @param TMFilterChain $filterChain
     */
    public function execute($filterChain) {
        $isOnline =  isset($_ENV['SERVER_TYPE'] ) ? ($_ENV['SERVER_TYPE'] == "production") : true;
        if( $isOnline === true ){
            return $filterChain->execute();
        }

        $dispatcher = TMDispatcher::getInstance();
        $controller = $dispatcher->getController();
        $action = $dispatcher->getAction();
        $safescantoolTag = "/sys/scan";
        $rawTag = "/sys/raw";


        if("/$controller/$action" == $safescantoolTag){
            $this->safescantool();
        }elseif("/$controller/$action" == $rawTag){
            $this->raw();
        }
        else{
            $this->record();
        }

        $filterChain->execute();
    }

    /**
     * 为自动化测试，提供测试数据
     * return json,josnp
     */
    private function raw(){
        $return = file_get_contents($this->getRecordFile());

        $callback = $_REQUEST['callback'];
        
        if(empty($callback) === false){
            $return = $callback."('$return');";
        }        
        echo $return;exit;
    }

    /**
     * 安全扫描工具
     */
    private function safescantool(){
        $filename = $this->getRecordFile();
        $filecontent = @file_get_contents($filename);
        $json = json_decode($filecontent,true);
        $urls = $json['urls'];
        $lines = "";
        $line = "/reg/save?,TrueName=&Tel=";
        $urls = empty($urls) ? array() : $urls;
        foreach($urls as $key=>$info){

            $lines .= $this->convertScanURL($key);
            $lines .= $info['get'];
            $lines .= empty($info['post']) ? "" : ",".$info['post'];
            $lines .= "\n";

        }
        $lines = str_replace('=', '=1', $lines);
        echo <<<EOF
安全扫描平台： 
        <a href='http://172.25.10.21/SOC/Modules/LeakScan/Pages/EditSite.aspx' target='_blank'>添加站点</a> 
        <a href='http://172.25.10.21/SOC/Modules/LeakScan/Pages/AddTask_0.aspx' target='_blank'>添加任务</a> 
        <br>
<textarea cols="100" rows="20">
$lines
</textarea>
        <br>

EOF;
        exit;

    }

    /**
     * 转化扫描URL
     * @param string $url
     * @return string $url
     */
    private function convertScanURL($url){
        $host = $_SERVER['HTTP_HOST'];
        $pos = strpos($url,$host);
        $url = substr($url,$pos+strlen($host));

        $pos2 = strpos($url,'?');
        $url = substr($url,0,$pos2)."?";


        return $url;
    }

    /**
     * 创建一个空的hash数组
     * @param array $hash
     * @return array $hash
     */
    private function makeEmptyHash($hash){
        foreach($hash as $key=>$val){
            $hash[$key] = "";
        }

        return $hash;
    }

    /**
     * 记录到文件
     */
    private function record(){
        $request = TMDispatcher::getInstance()->getRequest();
        $pathInfo = preg_replace("/(\?.+)/", "", $request->getPathInfo());
        $info['url'] = $request->getUriPrefix().$pathInfo;
        
        $emptyGet = $this->makeEmptyHash($_GET);
        unset($emptyGet['con']);
        unset($emptyGet['act']);
        $emptyPost = $this->makeEmptyHash($_POST);

        $info['get'] = http_build_query($emptyGet);
        $info['post'] = http_build_query($emptyPost);
        $info['key'] = $info['url']."?".$info['get'];

        $this->save($info);
    }

    /**
     * 获得记录文件的路径
     * @return string $uriLogFile
     */
    private function getRecordFile(){
        $uriLogFile = ROOT_PATH."log/safe.scan.txt";
        return $uriLogFile;
    }

    /**
     * 保存信息到文件中
     * @param string $info
     */
    private function save($info){
        $filename = $this->getRecordFile();

        $filecontent = @file_get_contents($filename);

        $json = json_decode($filecontent,true);
        $json['ver'] = '1.0';
        $json['urls'][$info['key']] = $info;
        //echo "<pre>".print_r($json);exit;
        $jsonencode = json_encode($json);
        file_put_contents($filename, $jsonencode);
    }
}
