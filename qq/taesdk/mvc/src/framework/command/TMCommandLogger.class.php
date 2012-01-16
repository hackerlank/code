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
 * TMCommandLogger
 * 命令行日志记录器
 *
 * @package sdk.mvc.src.framework.command
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMCommandLogger.class.php 2011-4-21 by ianzhang
 */
class TMCommandLogger {

    protected $stream;

    /**
     * Class constructor.
     *
     * @see initialize()
     */
    public function __construct()
    {
        $this->initialize();
    }

    /**
     * 初始化
     */
    public function initialize()
    {
        $stream = defined('STDOUT') ? STDOUT : fopen('php://stdout', 'w');

        $this->stream = $stream;
    }

    /**
     * Sets the PHP stream to use for this logger.
     *
     * @param stream $stream A php stream
     */
    public function setStream($stream)
    {
        $this->stream = $stream;
    }

    /**
     * Logs a message.
     *
     * @param string $message   Message
     */
    protected function doLog($message)
    {
        fwrite($this->stream, $message.PHP_EOL);
        flush();
    }

    /**
     * 打印命令行日志
     * @param string $messages
     * @param string $subject
     */
    public function log($messages, $subject = '')
    {
        $prefix = '';

        if (!empty($subject))
        {
            $subject  = is_object($subject) ? get_class($subject) : (is_string($subject) ? $subject : 'main');

            $prefix = '>> '.$subject.' ';
        }

        foreach ($messages as $key => $message)
        {
            $this->doLog(sprintf('%s%s', $prefix, $message));
        }
    }
}
?>