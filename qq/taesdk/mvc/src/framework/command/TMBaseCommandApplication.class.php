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
 * TMBaseCommandApplication
 * 命令行应用程序基类
 *
 * @package sdk.mvc.src.framework.command
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMBaseCommandApplication.class.php 2011-4-20 by ianzhang
 */
abstract class TMBaseCommandApplication {
    protected $commandManager = null, $nowrite = false, $name = 'UNKNOWN', $version = 'UNKNOWN', $tasks = array (), $currentTask = null;

    /**
     * Constructor
     */
    public function __construct() {
        $this->fixCgi();

        $argumentSet = new TMCommandArgumentSet ( array (new TMCommandArgument ( 'task', TMCommandArgument::REQUIRED, 'The task to execute' ) ) );
        $this->commandManager = new TMCommandManager ( $argumentSet);

        $this->configure ();

        $this->registerTasks ();
    }

    /**
     * Configures the current command application.
     */
    abstract public function configure();

    /**
     * 清除任务
     */
    public function clearTasks() {
        $this->tasks = array ();
    }

    /**
     * Registers an array of task objects.
     *
     * If you pass null, this method will register all available tasks.
     *
     * @param array  $tasks  An array of tasks
     */
    public function registerTasks($tasks = null)
    {
        if (null === $tasks)
        {
            $tasks = $this->autodiscoverTasks();
        }

        foreach ($tasks as $task)
        {
            $this->registerTask($task);
        }
    }

    /**
     * Autodiscovers task classes.
     *
     * @return array An array of tasks instances
     */
    public function autodiscoverTasks()
    {
        $tasks = array();
        foreach (get_declared_classes() as $class)
        {
            $r = new ReflectionClass($class);

            if ($r->isSubclassOf('TMTask') && !$r->isAbstract())
            {
                $tasks[] = new $class();
            }
        }

        return $tasks;
    }

    /**
     * Registers a task object.
     *
     * @param TMTask $task An TMTask object
     */
    public function registerTask(TMTask $task)
    {
        if (isset($this->tasks[$task->getFullName()]))
        {
            throw new TMCommandException(sprintf('The task named "%s" in "%s" task is already registered by the "%s" task.', $task->getFullName(), get_class($task), get_class($this->tasks[$task->getFullName()])));
        }

        $this->tasks[$task->getFullName()] = $task;

        foreach ($task->getAliases() as $alias)
        {
            if (isset($this->tasks[$alias]))
            {
                throw new TMCommandException(sprintf('A task named "%s" is already registered.', $alias));
            }

            $this->tasks[$alias] = $task;
        }
    }

    /**
     * Returns all registered tasks.
     *
     * @return array An array of TMTask objects
     */
    public function getTasks() {
        return $this->tasks;
    }

    /**
     * Returns a registered task by name or alias.
     *
     * @param string $name The task name or alias
     *
     * @return TMTask An TMTask object
     */
    public function getTask($name) {
        if (! isset ( $this->tasks [$name] )) {
            throw new TMCommandException ( sprintf ( 'The task "%s" does not exist.', $name ) );
        }

        return $this->tasks [$name];
    }

    /**
     * Runs the current application.
     *
     * @return integer 0 if everything went fine, or an error code
     */
    public function run() {
        $this->handleOptions ( $options );
        $arguments = $this->commandManager->getArgumentValues ();

        $this->currentTask = $this->getTaskToExecute ( $arguments ['task'] );

        $ret = $this->currentTask->runFromCLI ( $this->commandManager);

        $this->currentTask = null;

        return $ret;
    }

    /**
     * Gets the name of the application.
     *
     * @return string The application name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets the application name.
     *
     * @param string $name The application name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Gets the application version.
     *
     * @return string The application version
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * Sets the application version.
     *
     * @param string $version The application version
     */
    public function setVersion($version) {
        $this->version = $version;
    }

    /**
     * Parses and handles command line
     */
    protected function handleOptions() {
        $this->commandManager->process();
    }

    /**
     * Renders an exception.
     *
     * @param TMException $e An exception object
     */
    public function renderException($e) {
        $title = sprintf ( '  [%s]  ', get_class ( $e ) );
        $len = $this->strlen ( $title );
        $lines = array ();
        foreach ( explode ( "\n", $e->getMessage () ) as $line ) {
            $lines [] = sprintf ( '  %s  ', $line );
            $len = max ( $this->strlen ( $line ) + 4, $len );
        }

        $messages = array (str_repeat ( ' ', $len ) );

        foreach ( $lines as $line ) {
            $messages [] = $line . str_repeat ( ' ', $len - $this->strlen ( $line ) );
        }

        $messages [] = str_repeat ( ' ', $len );

        fwrite ( STDERR, "\n" );
        foreach ( $messages as $message ) {
            fwrite ( STDERR, $message. "\n" );
        }
        fwrite ( STDERR, "\n" );

        if (null !== $this->currentTask && $e instanceof TMCommandArgumentsException) {
            fwrite ( STDERR, sprintf ( $this->currentTask->getSynopsis (), $this->getName () ). "\n" );
            fwrite ( STDERR, "\n" );
        }
    }

    /**
     * 获得一个可以执行的任务.
     *
     * @param  string  $name  The task name or a task shortcut
     *
     * @return TMTask A TMTask object
     */
    public function getTaskToExecute($name) {
        // 处理namespace
        if (false !== $pos = strpos ( $name, ':' )) {
            $namespace = substr ( $name, 0, $pos );
            $name = substr ( $name, $pos + 1 );

            $namespaces = array ();
            foreach ( $this->tasks as $task ) {
                if ($task->getNamespace () && ! in_array ( $task->getNamespace (), $namespaces )) {
                    $namespaces [] = $task->getNamespace ();
                }
            }
            $abbrev = $this->getAbbreviations ( $namespaces );

            if (! isset ( $abbrev [$namespace] )) {
                throw new TMCommandException ( sprintf ( 'There are no tasks defined in the "%s" namespace.', $namespace ) );
            } else if (count ( $abbrev [$namespace] ) > 1) {
                throw new TMCommandException ( sprintf ( 'The namespace "%s" is ambiguous (%s).', $namespace, implode ( ', ', $abbrev [$namespace] ) ) );
            } else {
                $namespace = $abbrev [$namespace] [0];
            }
        } else {
            $namespace = '';
        }

        // 处理name
        $tasks = array ();
        foreach ( $this->tasks as $taskName => $task ) {
            if ($taskName == $task->getFullName () && $task->getNamespace () == $namespace) {
                $tasks [] = $task->getName ();
            }
        }

        $abbrev = $this->getAbbreviations ( $tasks );
        if (isset ( $abbrev [$name] ) && count ( $abbrev [$name] ) == 1) {
            return $this->getTask ( $namespace ? $namespace . ':' . $abbrev [$name] [0] : $abbrev [$name] [0] );
        }

        // 处理aliases
        $aliases = array ();
        foreach ( $this->tasks as $taskName => $task ) {
            if ($taskName == $task->getFullName ()) {
                foreach ( $task->getAliases () as $alias ) {
                    $aliases [] = $alias;
                }
            }
        }

        $abbrev = $this->getAbbreviations ( $aliases );
        $fullName = $namespace ? $namespace . ':' . $name : $name;
        if (! isset ( $abbrev [$fullName] )) {
            throw new TMCommandException ( sprintf ( 'Task "%s" is not defined.', $fullName ) );
        } else if (count ( $abbrev [$fullName] ) > 1) {
            throw new TMCommandException ( sprintf ( 'Task "%s" is ambiguous (%s).', $fullName, implode ( ', ', $abbrev [$fullName] ) ) );
        } else {
            return $this->getTask ( $abbrev [$fullName] [0] );
        }
    }

    /**
     * 获得字符串长度
     * @param string $string
     * @return int
     */
    protected function strlen($string) {
        return function_exists ( 'mb_strlen' ) ? mb_strlen ( $string ) : strlen ( $string );
    }

    /**
     * 如果在cgi模式下运行则关闭操作
     *
     * @see http://www.sitepoint.com/article/php-command-line-1/3
     */
    protected function fixCgi() {
        // handle output buffering
        @ob_end_flush ();
        ob_implicit_flush ( true );
        if (version_compare(phpversion(), '4.3.0', '<') || php_sapi_name() == 'cgi') { 
            // Handle output buffering @ob_end_flush(); ob_implicit_flush(TRUE); 
            // PHP ini settings 
            set_time_limit(0); 
            ini_set('track_errors', TRUE); 
            ini_set('html_errors', FALSE); 
            ini_set('magic_quotes_runtime', FALSE); 
            // Define stream constants 
            define('STDIN', fopen('php://stdin', 'r')); 
            define('STDOUT', fopen('php://stdout', 'w')); 
            define('STDERR', fopen('php://stderr', 'w')); 
            
            // Close the streams on script termination 
            register_shutdown_function( create_function('', 'fclose(STDIN); fclose(STDOUT); fclose(STDERR); return true;') ); 
        }
    }

    /**
     * 得到缩写名字的数组
     *
     * @param array $names
     */
    protected function getAbbreviations($names) {
        $abbrevs = array ();
        $table = array ();

        foreach ( $names as $name ) {
            for($len = strlen ( $name ) - 1; $len > 0; -- $len) {
                $abbrev = substr ( $name, 0, $len );
                if (! array_key_exists ( $abbrev, $table )) {
                    $table [$abbrev] = 1;
                } else {
                    ++ $table [$abbrev];
                }

                $seen = $table [$abbrev];
                if ($seen == 1) {
                    // We're the first word so far to have this abbreviation.
                    $abbrevs [$abbrev] = array ($name );
                } else if ($seen == 2) {
                    // 不能使用两次定义的缩略语.
                    // unset($abbrevs[$abbrev]);
                    $abbrevs [$abbrev] [] = $name;
                } else {
                    // We're the third word to have this abbreviation, so skip to the next word.
                    continue;
                }
            }
        }

        // Non-abbreviations always get entered, even if they aren't unique
        foreach ( $names as $name ) {
            $abbrevs [$name] = array ($name );
        }

        return $abbrevs;
    }
}
?>