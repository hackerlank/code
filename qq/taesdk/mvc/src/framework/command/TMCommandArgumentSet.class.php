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
 * TMCommandArgumentSet
 * 命令行参数集合
 *
 * @package sdk.mvc.src.framework.command
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMCommandArgumentSet.class.php 2011-4-20 by ianzhang    
 */
class TMCommandArgumentSet {
    protected $arguments = array (), $requiredCount = 0, $hasAnArrayArgument = false, $hasOptional = false;

    /**
     * Constructor.
     *
     * @param array $arguments An array of TMCommandArgument objects
     */
    public function __construct($arguments = array()) {
        $this->setArguments ( $arguments );
    }

    /**
     * Sets the TMCommandArgument objects.
     *
     * @param array $arguments An array of TMCommandArgument objects
     */
    public function setArguments($arguments = array()) {
        $this->arguments = array ();
        $this->requiredCount = 0;
        $this->hasOptional = false;
        $this->addArguments ( $arguments );
    }

    /**
     * Add an array of TMCommandArgument objects.
     *
     * @param array $arguments An array of TMCommandArgument objects
     */
    public function addArguments($arguments = array()) {
        if (null !== $arguments) {
            foreach ( $arguments as $argument ) {
                $this->addArgument ( $argument );
            }
        }
    }

    /**
     * Add a TMCommandArgument objects.
     *
     * @param TMCommandArgument $argument A TMCommandArgument object
     */
    public function addArgument(TMCommandArgument $argument) {
        if (isset ( $this->arguments [$argument->getName ()] )) {
            throw new TMCommandException ( sprintf ( 'An argument with name "%s" already exist.', $argument->getName () ) );
        }

        if ($this->hasAnArrayArgument) {
            throw new TMCommandException ( 'Cannot add an argument after an array argument.' );
        }

        if ($argument->isRequired () && $this->hasOptional) {
            throw new TMCommandException ( 'Cannot add a required argument after an optional one.' );
        }

        if ($argument->isArray ()) {
            $this->hasAnArrayArgument = true;
        }

        if ($argument->isRequired ()) {
            ++ $this->requiredCount;
        } else {
            $this->hasOptional = true;
        }

        $this->arguments [$argument->getName ()] = $argument;
    }

    /**
     * Returns an argument by name.
     *
     * @param string $name The argument name
     *
     * @return TMCommandArgument A TMCommandArgument object
     */
    public function getArgument($name) {
        if (! $this->hasArgument ( $name )) {
            throw new TMCommandException ( sprintf ( 'The "%s" argument does not exist.', $name ) );
        }

        return $this->arguments [$name];
    }

    /**
     * Returns true if an argument object exists by name.
     *
     * @param string $name The argument name
     *
     * @return Boolean true if the argument object exists, false otherwise
     */
    public function hasArgument($name) {
        return isset ( $this->arguments [$name] );
    }

    /**
     * Gets the array of TMCommandArgument objects.
     *
     * @return array An array of TMCommandArgument objects
     */
    public function getArguments() {
        return $this->arguments;
    }

    /**
     * Returns the number of arguments.
     *
     * @return integer The number of arguments
     */
    public function getArgumentCount() {
        return $this->hasAnArrayArgument ? PHP_INT_MAX : count ( $this->arguments );
    }

    /**
     * Returns the number of required arguments.
     *
     * @return integer The number of required arguments
     */
    public function getArgumentRequiredCount() {
        return $this->requiredCount;
    }

    /**
     * Gets the default values.
     *
     * @return array An array of default values
     */
    public function getDefaults() {
        $values = array ();
        foreach ( $this->arguments as $argument ) {
            $values [$argument->getName ()] = $argument->getDefault ();
        }

        return $values;
    }
}
?>