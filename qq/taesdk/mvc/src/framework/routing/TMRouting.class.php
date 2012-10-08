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
 * TMRouting
 * 路由解析类
 *
 * @package sdk.mvc.src.framework.routing
 * @author  ianzhang <ianzhang@tencent.com> 
 * @version TMRouting.class.php 2011-9-7 by ianzhang    
 */
class TMRouting {
    protected
        $currentRouteName   = null,
        $defaultParamsDirty = false,
        $routes             = array();
    
    /**
     * Class constructor.
     *
     * @see initialize()
     */
    public function __construct()
    {
        $this->initialize();
    }
    
    public function initialize()
    {
        $this->loadConfiguration();
    }
    
    protected function loadConfiguration()
    {
        $configDispatcher = new TMConfigDispatcher();
        $configFileName = $configDispatcher->getConfigFile("routing");
        if(!empty($configFileName)){
            include $configFileName;
        }
    }
    
	public function parse($url) {
		if (false === $info = $this->findRoute($url))
        {
            $this->currentRouteName = null;
            
            return false;
        }
        
        return $info['parameters'];
	}
	
    public function findRoute($url)
    {
        $url = $this->normalizeUrl($url);

        $info = $this->getRouteThatMatchesUrl($url);
                
        return $info;
    }
    
    protected function normalizeUrl($url)
    {
        if ('/' != substr($url, 0, 1))
        {
            $url = '/'.$url;
        }
        
        // 去掉问号
        if (false !== $pos = strpos($url, '?'))
        {
            $url = substr($url, 0, $pos);
        }
        
        // remove multiple /
        $url = preg_replace('#/+#', '/', $url);
        
        return $url;
    }
    
    protected function getRouteThatMatchesUrl($url)
    {
        foreach ($this->routes as $name => $route)
        {
            if (false === $parameters = $route->matchesUrl($url))
            {
                continue;
            }
            return array('name' => $name, 'pattern' => $route->getPattern(), 'parameters' => $parameters);
        }
        
        return false;
    }
}
?>