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
class TMDebugBarFilter extends TMFilter {
    public function execute($filterChain) {
        $dispatcher = TMDispatcher::getInstance();
        $controller = $dispatcher->getController();
        $action = $dispatcher->getAction();

        if("/$controller/$action" == "/taedebug/showajaxbar"){
            $this->showAjaxBar();
        }else{
            $filterChain->execute();
            $this->debugBar();
        }
    }

    protected function showAjaxBar()
    {
        session_start();
        $sessionId = session_id();//uniqid(TMUtil::getClientIp());

        $content = "";
        if(!empty($sessionId))
        {
            $logFile = ROOT_PATH."log/debug/$sessionId.php";

            if(is_file($logFile)){
                $debugArray = include($logFile);
    
                if(!empty($debugArray))
                {
                    $view = new TMView();
                    $content = $view->renderFile($debugArray, ROOT_PATH."templates/debug/debugbarcore.php");
                    unlink($logFile);
                }
            }
        }

        $dispatcher = TMDispatcher::getInstance();
        $dispatcher->getResponse()->setContent($content);

    }

    /**
     * 执行添加debug bar逻辑
     * 监控代码在 ROOT_PATH . 'templates/debugbar.php'
     */
    protected function debugBar()
    {
        session_start();

        $sessionId = session_id();
        
        $dispatcher = TMDispatcher::getInstance();

        $request = $dispatcher->getRequest();
        $uri = $request->getRelativeUrlRoot();

        $gets = $request->getGetParameters();
        $posts = $request->getPostParameters();
        $files = $_FILES;

        $actionTrackData = TMActionTrackDebugger::getDebuggerTracks();
        $logData = TMLogDebugger::getDebuggerLogs();
        $debuggerSqlData = TMSqlDebugger::getDebuggerSqls();
        $defectSqlData = TMSqlDebugger::getExplainResults();
        $timerArray = TMTimerManager::getTimers();
        $timerData = array();
        foreach($timerArray as $key => $timer)
        {
            $time = $timer->getElapsedTime();
            $call = $timer->getCalls();
            
            $timerData[$key] = array("time" => $time, "call" => $call);
        }

        $tmpArray[$uri]["gets"] = $gets;
        $tmpArray[$uri]["posts"] = $posts;
        $tmpArray[$uri]["files"] = $files;

        $tmpArray[$uri]["actionTrackData"] = $actionTrackData;
        $tmpArray[$uri]["logData"] = $logData;
        $tmpArray[$uri]["debuggerSqlData"] = $debuggerSqlData;
        $tmpArray[$uri]["defectSqlData"] = $defectSqlData;
        $tmpArray[$uri]["timerData"] = $timerData;

        $logFile = ROOT_PATH."log/debug/$sessionId.php";
       
        $debugArray["debugArray"] = $tmpArray;
        if(is_file($logFile)){
            $debugFileArray = include($logFile);
            
            if(!empty($debugFileArray)){
                $debugArray["debugArray"] = array_merge($debugFileArray["debugArray"], $tmpArray);
            }
        }
        
        $fileContent = "<?php\n\nreturn ".var_export($debugArray, true).";\n";

        $currMask = umask(0);
        $this->mkdirs(ROOT_PATH."log/debug/");
        umask($currMask);

        file_put_contents(ROOT_PATH."log/debug/$sessionId.php", $fileContent);

        $content = $dispatcher->getResponse()->getContent();

        //$replace = "<body>\n".$toolBar."\n";
        //$content = str_replace("<body>",$replace,$content);
        $content = preg_replace_callback('/<body([^>]*)>/is', array($this, 'debugbarReplaceCallback'), $content);

        $dispatcher->getResponse()->setContent($content);
    }

    /**
     * 正则替换debugbar的回调函数
     *
     * @param array $matches 正则匹配到的数组
     */
    protected function debugbarReplaceCallback($matches) {
        $view = new TMView();
        $debugBar = $view->renderFile(array(), ROOT_PATH . "templates/debug/debugbar.php");
        return "<body{$matches[1]}>\n{$debugBar}\n";
    }

    protected function mkdirs($dir)
    {
        if(!is_dir($dir))
        {
            if(!$this->mkdirs(dirname($dir))){
                return false;
            }
            if(!mkdir($dir,0777)){
                return false;
            }
        }
        return true;
    }
}
?>