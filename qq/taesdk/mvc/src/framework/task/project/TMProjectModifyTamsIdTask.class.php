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
 * TMProjectModifyTamsIdTask
 * 修改项目tams的id
 *
 * @package sdk.mvc.src.framework.task.project
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMProjectModifyTamsIdTask.class.php 2011-4-27 by ianzhang    
 */
class TMProjectModifyTamsIdTask extends TMCommandApplicationTask {
    /**
     * @see TMTask
     */
    protected function configure() {
        $this->namespace = 'project';
        $this->name = 'modify-tamsid';
        
        $this->addArguments ( array (new TMCommandArgument ( 'tamsid', TMCommandArgument::REQUIRED, 'The TAMS ID' ) ) );

        $this->briefDescription = 'Modify project tams id';

        $this->detailedDescription = <<<EOF
The [modify tams id|INFO] task modifys project's tams id:

  [./taesdk.php project:modify_tams_id|INFO]

EOF;
    }

    /**
     * @see TMTask
     */
    protected function execute($arguments = array()) {
        $tamsid = $arguments["tamsid"];
        
        $beforeTamsId = TMConfig::get("tams_id");
        
        $path = ROOT_PATH;
        $finder = new TMFinder($path);
        $finder->addIgnoreFolders(array('.', '..', '.svn'));
        $finder->setFormat('/(\.php)|(\.js)|(\.yml)$/');

        $findFiles = $finder->execute();
        $messages = array();
        
        foreach ($findFiles as $file)
        {
            $content = file_get_contents($file);
            $handleContent = str_replace($beforeTamsId, $tamsid, $content);
            if($handleContent != $content){
                file_put_contents($file, $handleContent);
                
                $messages [] = sprintf ( "Modify the tams id in File: \"%s\"", $file );
            }
        }
        
        $this->log($messages);
    }
}
?>