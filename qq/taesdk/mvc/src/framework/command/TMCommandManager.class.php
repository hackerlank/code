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
 * TMCommandManager
 * 对command指令的解析
 *
 * @package sdk.mvc.src.framework.command
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMCommandManager.class.php 2011-4-12 by ianzhang
 */
class TMCommandManager {
    protected $arguments = '', $errors = array (), $argumentSet = array (), $argumentValues = array (), $parsedArgumentValues = array ();

    /**
     * Constructor.
     *
     * @param TMCommandArgumentSet $argumentSet A TMCommandArgumentSet object
     */
    public function __construct(TMCommandArgumentSet $argumentSet = null) {
        if (null === $argumentSet) {
            $argumentSet = new TMCommandArgumentSet ();
        }
        $this->setArgumentSet ( $argumentSet );
    }

    /**
     * Sets the argument set.
     *
     * @param TMCommandArgumentSet $argumentSet A TMCommandArgumentSet object
     */
    public function setArgumentSet(TMCommandArgumentSet $argumentSet) {
        $this->argumentSet = $argumentSet;
    }

    /**
     * Gets the argument set.
     *
     * @return TMCommandArgumentSet A TMCommandArgumentSet object
     */
    public function getArgumentSet() {
        return $this->argumentSet;
    }

    /**
     * Processes command line arguments.
     *
     * @param mixed $arguments A string or an array of command line parameters
     */
    public function process($arguments = null) {
        if (null === $arguments) {
            $arguments = $_SERVER ['argv'];

            // we strip command line program
            if (isset ( $arguments [0] ) && '-' != $arguments [0] [0]) {
                array_shift ( $arguments );
            }
        }
        else if (!is_array($arguments))
        {
            $arguments = preg_split('/\s+/', $arguments);
        }

        $this->arguments = $arguments;
        $this->argumentValues = $this->argumentSet->getDefaults ();
        $this->parsedArgumentValues = array ();
        $this->errors = array ();

        while ( ! in_array ( $argument = array_shift ( $this->arguments ), array ('', null ) ) ) {
            $this->parsedArgumentValues [] = $argument;
        }

        $position = 0;
        foreach ( $this->argumentSet->getArguments () as $argument ) {
            if (array_key_exists ( $position, $this->parsedArgumentValues )) {
                if ($argument->isArray ()) {
                    $this->argumentValues [$argument->getName ()] = array_slice ( $this->parsedArgumentValues, $position );
                    break;
                } else {
                    $this->argumentValues [$argument->getName ()] = $this->parsedArgumentValues [$position];
                }
            }
            ++ $position;
        }

        $this->arguments = $arguments;

        if (count ( $this->parsedArgumentValues ) < $this->argumentSet->getArgumentRequiredCount ()) {
            $this->errors [] = 'Not enough arguments.';
        } else if (count ( $this->parsedArgumentValues ) > $this->argumentSet->getArgumentCount ()) {
            $this->errors [] = sprintf ( 'Too many arguments ("%s" given).', implode ( ' ', $this->parsedArgumentValues ) );
        }
    }

    /**
     * 返回该命令工具是否报错
     *
     * @return true if there are some validation errors, false otherwise
     */
    public function isValid() {
        return count ( $this->errors ) ? false : true;
    }

    /**
     * Gets the current errors.
     *
     * @return array An array of errors
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Returns the argument values.
     *
     * @return array An array of argument values
     */
    public function getArgumentValues() {
        return $this->argumentValues;
    }

    /**
     * Returns the argument value for a given argument name.
     *
     * @param string $name The argument name
     *
     * @return mixed The argument value
     */
    public function getArgumentValue($name) {
        if (! $this->argumentSet->hasArgument ( $name )) {
            throw new TMCommandException ( sprintf ( 'The "%s" argument does not exist.', $name ) );
        }

        return $this->argumentValues [$name];
    }
}
?>