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
 * 流程链控制类
 *
 * LIB库内部调用
 * 根据config/filter.yml的配置，判断哪些流程需要执行，并依次调用执行
 *
 * @package sdk.mvc.src.framework.filter
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMFilterChain.class.php 2009-4-16 by ianzhang
 */
class TMFilterChain {
    protected $chain = array();
    protected $index = -1;

    /**
     * 获取流程链配置
     */
    public function loadConfiguration()
    {
        $configDispatcher = new TMConfigDispatcher();
        require($configDispatcher->getConfigFile("filter"));
    }

    /**
     * Register the filter name into chain
     *
     * @param string $filterName
     */
    public function register($filterName)
    {
        $this->chain[] = $filterName;
    }

    /**
     * Execute the filter event
     *
     */
    public function execute()
    {
        ++$this->index;

        if ($this->index < count($this->chain))
        {
            $className = $this->chain[$this->index];
			
			while(!class_exists($className)){
				++$this->index;
				if($this->index < count($this->chain))
				{
					$className = $this->chain[$this->index];
				}else{
					return;
				}
			}
			
			$filter = new $className();
			$filter->execute($this);
        }
    }
}