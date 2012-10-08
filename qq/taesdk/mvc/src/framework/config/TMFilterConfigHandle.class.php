<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2009 BY  TENCENT  CORPORATION.  ALL RIGHTS
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
 * Filter config handle
 *
 * @package sdk.mvc.src.framework.config
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMFilterConfigHandle.class.php 2009-4-17 by ianzhang
 */
class TMFilterConfigHandle
{
    /**
     * Execute filter config handle
     * Parse config/filter.yml, and return cache file content. <br>
     * This function will be called in TMConfigDispatcher::callHandle,
     * and then write the content into cache/config/filter.php.
     *
     * @access public
     * @param string $configFile config file path
     * @return string $content
     */
    public function execute($configFile)
    {
        $array = TMYaml::load($configFile);

        $content = "<?php\n";
        foreach($array as $filter)
        {
            $content .= $this->addFilter($filter);
        }
        $content .= "\n?>";
        return $content;
    }

    /**
     * Adds a filter statement to the data
     *
     * @param  string $class  the class name
     * @return string
     */
    protected function addFilter($class)
    {
    return <<<EOF
\$this->register('{$class}');
EOF;
    }
}