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
 * TMCommandApplication
 * 命令行应用程序类
 *
 * @package sdk.mvc.src.framework.command
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMCommandApplication.class.php 2011-4-7 by ianzhang
 */
class TMCommandApplication extends TMBaseCommandApplication {
    protected $taskFiles = array ();
    protected $loggerArray = array();

    /**
     * Configures the current symfony command application.
     */
    public function configure() {
        // application
        $this->setName ( 'taesdk' );
        $this->setVersion ( TAESDK_VERSION );

        $this->loadTasks ();
    }
 
    /**
     * Loads all available tasks.
     */
    public function loadTasks() {
        // tae core tasks
        $dirs = array (FRAMEWORK_PATH . '/task' );

        foreach($dirs as $dir)
        {
            $finder = new TMFinder($dir);
            $finder->addIgnoreFolders(array('.', '..', '.svn'));
            $finder->setFormat('/(Task\.class\.php)$/');
            
            $findFiles = $finder->execute();
            
            foreach($findFiles as $file)
            {
                $this->taskFiles [basename ( $file, '.class.php' )] = $file;
            }
        }
        

        // register local autoloader for tasks
        spl_autoload_register ( array ($this, 'autoloadTask' ) );

        // require tasks
        foreach ( $this->taskFiles as $task => $file ) {
            // forces autoloading of each task class
            class_exists ( $task, true );
        }

        // unregister local autoloader
        spl_autoload_unregister ( array ($this, 'autoloadTask' ) );
    }

    /**
     * Autoloads a task class
     *
     * @param  string  $class  The task class name
     *
     * @return Boolean
     */
    public function autoloadTask($class) {
        if (isset ( $this->taskFiles [$class] )) {
            require_once $this->taskFiles [$class];

            return true;
        }

        return false;
    }

    /**
     * Runs the current application.
     *
     * @return int 0 if everything went fine, or an error code
     */
    public function run() {
        $this->handleOptions ();
        $arguments = $this->commandManager->getArgumentValues ();

        $options = null;
        
        if (! isset ( $arguments ['task'] )) {
            $arguments ['task'] = 'list';
            $options = "list";
        }

        $this->currentTask = $this->getTaskToExecute ( $arguments ['task'] );

        if ($this->currentTask instanceof TMCommandApplicationTask) {
            $this->currentTask->setCommandApplication ( $this );
        }

        $ret = $this->currentTask->runFromCLI ( $this->commandManager, $options);

        $this->currentTask = null;

        return $ret;
    }
    
    /**
     * 返回日期记录器数组
     * @return array
     */
    public function getLoggers()
    {
        return $this->loggerArray;
    }
    
    /**
     * 设置日志记录器
     * @param TMLogInterface $logger
     */
    public function setLogger($logger)
    {
        $this->loggerArray[] = $logger;
    }
}
?>
