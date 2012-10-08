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
 * TMListTask
 * 列出所有的task
 *
 * @package sdk.src.mvc.framework.task
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMListTask.class.php 2011-4-20 by ianzhang    
 */
class TMListTask extends TMCommandApplicationTask {
    /**
     * @see TMTask
     */
    protected function configure() {
        $this->addArguments ( array (new TMCommandArgument ( 'namespace', TMCommandArgument::OPTIONAL, 'The namespace name' ) ) );

        $this->briefDescription = 'Lists tasks';

        $this->detailedDescription = <<<EOF
The [list|INFO] task lists all tasks:

  [./taesdk.php list|INFO]

You can also display the tasks for a specific namespace:

  [./taesdk.php list test|INFO]
EOF;
    }

    /**
     * @see TMTask
     */
    protected function execute($arguments = array(), $options = array()) {
        $tasks = array ();
        foreach ( $this->commandApplication->getTasks () as $name => $task ) {
            if ($arguments ['namespace'] && $arguments ['namespace'] != $task->getNamespace ()) {
                continue;
            }

            if ($name != $task->getFullName ()) {
                // it is an alias
                continue;
            }

            if (! $task->getNamespace ()) {
                $name = '_default:' . $name;
            }

            $tasks [$name] = $task;
        }

        $this->outputAsText ( $arguments ['namespace'], $tasks );
    }

    /**
     * 将内容输出
     * @param string $namespace
     * @param array $tasks
     */
    protected function outputAsText($namespace, $tasks) {
        //$this->commandApplication->help ();
        $this->log ( '' );

        $width = 0;
        foreach ( $tasks as $name => $task ) {
            $width = strlen ( $task->getName () ) > $width ? strlen ( $task->getName () ) : $width;
        }
        $width += strlen ( '  ' );

        $messages = array ();
        if ($namespace) {
            $messages [] = sprintf ( "Available tasks for the \"%s\" namespace:", $namespace );
        } else {
            $messages [] = 'Available tasks:';
        }

        // display tasks
        ksort ( $tasks );
        $currentNamespace = '';
        foreach ( $tasks as $name => $task ) {
            if (! $namespace && $currentNamespace != $task->getNamespace ()) {
                $currentNamespace = $task->getNamespace ();
                $messages [] = $task->getNamespace ();
            }

            $aliases = $task->getAliases () ? ' (' . implode ( ', ', $task->getAliases () ) . ')' : '';

            $messages [] = sprintf ( "  %-${width}s %s%s", ':' . $task->getName (), $task->getBriefDescription (), $aliases );
        }

        $this->log ( $messages );
    }
}
?>