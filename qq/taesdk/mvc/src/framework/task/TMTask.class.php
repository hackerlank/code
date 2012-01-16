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
 * TMTask
 * 命令行任务
 *
 * @package sdk.mvc.src.framework.task
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMTask.class.php 2011-4-20 by ianzhang    
 */
abstract class TMTask {
    protected $namespace = ''; 
    protected $name = null; 
    protected $aliases = array ();
    protected $briefDescription = ''; 
    protected $detailedDescription = '';
    protected $arguments = array ();

    /**
     * Constructor.
     */
    public function __construct() {
        $this->configure ();
    }

    /**
     * Configures the current task.
     */
    protected function configure() {
    }

    /**
     * Runs the task from the CLI.
     *
     * @param TMCommandManager $commandManager  An TMCommandManager instance
     * @param mixed $arguments
     *
     * @return integer 0 if everything went fine, or an error code
     */
    public function runFromCLI(TMCommandManager $commandManager, $arguments) {
        $commandManager->getArgumentSet ()->addArguments ( $this->getArguments () );

        return $this->doRun ( $commandManager, $arguments );
    }

    /**
     * Returns the argument objects.
     *
     * @return TMCommandArgument An array of TMCommandArgument objects.
     */
    public function getArguments() {
        return $this->arguments;
    }

    /**
     * Adds an array of argument objects.
     *
     * @param array $arguments  An array of arguments
     */
    public function addArguments($arguments) {
        $this->arguments = array_merge ( $this->arguments, $arguments );
    }

    /**
     * Add an argument.
     *
     * 使用TMCommandArgument来创建参数
     *
     * @see TMCommandArgument::__construct()
     */
    public function addArgument($name, $mode = null, $help = '', $default = null) {
        $this->arguments [] = new TMCommandArgument ( $name, $mode, $help, $default );
    }
    
    /**
     * Returns the task namespace.
     *
     * @return string The task namespace
     */
    public function getNamespace() {
        return $this->namespace;
    }

    /**
     * Returns the task name
     *
     * @return string The task name
     */
    public function getName() {
        if ($this->name) {
            return $this->name;
        }

        $name = get_class ( $this );

        if ('TM' == substr ( $name, 0, 2 )) {
            $name = substr ( $name, 2 );
        }

        if ('Task' == substr ( $name, - 4 )) {
            $name = substr ( $name, 0, - 4 );
        }

        return strtolower($name);
    }

    /**
     * 得到一个完整的名字，如果有namespace，则把namespace一起返回
     *
     * @return string The fully qualified task name
     */
    final function getFullName() {
        return $this->getNamespace () ? $this->getNamespace () . ':' . $this->getName () : $this->getName ();
    }

    /**
     * Returns the brief description for the task.
     *
     * @return string The brief description for the task
     */
    public function getBriefDescription() {
        return $this->briefDescription;
    }

    /**
     * Returns the detailed description for the task.
     *
     * @return string The detailed description for the task
     */
    public function getDetailedDescription() {
        return $this->detailedDescription;
    }

    /**
     * Returns the aliases for the task.
     *
     * @return array An array of aliases for the task
     */
    public function getAliases() {
        return $this->aliases;
    }

    /**
     * Returns the synopsis for the task.
     *
     * @return string The synopsis
     */
    public function getSynopsis() {

        $arguments = array ();
        foreach ( $this->getArguments () as $argument ) {
            $arguments [] = sprintf ( $argument->isRequired () ? '%s' : '[%s]', $argument->getName () . ($argument->isArray () ? '1' : '') );

            if ($argument->isArray ()) {
                $arguments [] = sprintf ( '... [%sN]', $argument->getName () );
            }
        }

        return sprintf ( '%%s %s %s', $this->getFullName (), implode ( ' ', $arguments ) );
    }

    /**
     * 处理参数
     * @param TMCommandManager $commandManager
     * @param mixed $arguments
     */
    protected function process(TMCommandManager $commandManager, $arguments) {
        $commandManager->process ($arguments);
        if (! $commandManager->isValid ()) {
            throw new TMCommandArgumentsException ( sprintf ( "The execution of task \"%s\" failed.\n- %s", $this->getFullName (), implode ( "\n- ", $commandManager->getErrors () ) ) );
        }
    }

    /**
     * 运行该任务
     * @param TMCommandManager $commandManager
     * @param mixed $arguments
     * @return int
     */
    protected function doRun(TMCommandManager $commandManager, $arguments) {
        $this->process ( $commandManager, $arguments);

        $ret = $this->execute ( $commandManager->getArgumentValues ());

        return $ret;
    }

    /**
     * 调用具体逻辑
     *
     * @param array    $arguments  An array of arguments
     *
     * @return integer 0 if everything went fine, or an error code
     */
    abstract protected function execute($arguments = array());

}
?>