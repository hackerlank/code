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
 * TMCommandArgument
 * 命令行参数
 *
 * @package sdk.mvc.src.framework.command
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMCommandArgument.class.php 2011-4-20 by ianzhang    
 */
class TMCommandArgument
{
    const REQUIRED = 1;
    const OPTIONAL = 2;

    const IS_ARRAY = 4;

    protected $name = null, $mode = null, $default = null, $help = '';

    /**
     * Constructor.
     *
     * @param string  $name    The argument name
     * @param integer $mode    The argument mode: self::REQUIRED or self::OPTIONAL
     * @param string  $help    A help text
     * @param mixed   $default The default value (for self::OPTIONAL mode only)
     */
    public function __construct($name, $mode = null, $help = '', $default = null) {
        if (null === $mode) {
            $mode = self::OPTIONAL;
        } else if (is_string ( $mode ) || $mode > 7) {
            throw new TMCommandException ( sprintf ( 'Argument mode "%s" is not valid.', $mode ) );
        }

        $this->name = $name;
        $this->mode = $mode;
        $this->help = $help;

        $this->setDefault ( $default );
    }

    /**
     * Returns the argument name.
     *
     * @return string The argument name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Returns true if the argument is required.
     *
     * @return Boolean true if parameter mode is self::REQUIRED, false otherwise
     */
    public function isRequired() {
        return self::REQUIRED === (self::REQUIRED & $this->mode);
    }

    /**
     * Returns true if the argument can take multiple values.
     *
     * @return Boolean true if mode is self::IS_ARRAY, false otherwise
     */
    public function isArray() {
        return self::IS_ARRAY === (self::IS_ARRAY & $this->mode);
    }

    /**
     * Sets the default value.
     *
     * @param mixed $default The default value
     */
    public function setDefault($default = null) {
        if (self::REQUIRED === $this->mode && null !== $default) {
            throw new TMCommandException ( 'Cannot set a default value except for sfCommandParameter::OPTIONAL mode.' );
        }

        if ($this->isArray ()) {
            if (null === $default) {
                $default = array ();
            } else if (! is_array ( $default )) {
                throw new TMCommandException ( 'A default value for an array argument must be an array.' );
            }
        }

        $this->default = $default;
    }

    /**
     * Returns the default value.
     *
     * @return mixed The default value
     */
    public function getDefault() {
        return $this->default;
    }

    /**
     * Returns the help text.
     *
     * @return string The help text
     */
    public function getHelp() {
        return $this->help;
    }
}
?>