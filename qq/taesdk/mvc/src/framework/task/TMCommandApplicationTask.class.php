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
 * TMCommandApplicationTask
 * 命令行应用任务
 *
 * @package sdk.mvc.src.framework.task
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMCommandApplicationTask.class.php 2011-4-20 by ianzhang
 */
abstract class TMCommandApplicationTask extends TMTask {
    /**
     * TMCommandApplication
     * @var TMCommandApplication
     */
    protected $commandApplication = null;

    /**
     * Sets the command application instance for this task.
     *
     * @param TMCommandApplication $commandApplication A TMCommandApplication instance
     */
    public function setCommandApplication(TMCommandApplication $commandApplication = null) {
        $this->commandApplication = $commandApplication;
    }

    /**
     * Logs a message.
     *
     * @param mixed $messages  The message as an array of lines of a single string
     * @param mixed $subject 主题
     */
    public function log($messages, $subject = '')
    {
        if (!is_array($messages))
        {
            $messages = array($messages);
        }

        $loggerArray = $this->commandApplication->getLoggers();
        foreach($loggerArray as $logger)
        {
            $logger->log($messages, $subject);
        }
    }

    /**
     * Logs a message as a block of text.
     *
     * @param string|array $messages The message to display in the block
     */
    public function logBlock($messages)
    {
        if (!is_array($messages))
        {
            $messages = array($messages);
        }

        $large = (Boolean) $count;

        $len = 0;
        $lines = array();
        foreach ($messages as $message)
        {
            $lines[] = sprintf($large ? '  %s  ' : ' %s ', $message);
            $len = max($this->strlen($message) + ($large ? 4 : 2), $len);
        }

        $messages = $large ? array(str_repeat(' ', $len)) : array();
        foreach ($lines as $line)
        {
            $messages[] = $line.str_repeat(' ', $len - $this->strlen($line));
        }
        if ($large)
        {
            $messages[] = str_repeat(' ', $len);
        }

        foreach ($messages as $message)
        {
            $this->log($message);
        }
    }
}
?>