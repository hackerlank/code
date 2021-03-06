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
 * TMRoute
 * 路由类
 *
 * @package sdk.mvc.src.framework.routing
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMRoute.class.php 2011-9-7 by ianzhang
 */
class TMRoute implements Serializable
{
    protected $parameters        = null;
    protected $suffix            = null;
    protected $compiled          = false;
    protected $options           = array();
    protected $pattern           = null;
    protected $regex             = null;
    protected $defaults          = array();
    protected $requirements      = array();
    protected $tokens            = array();

    public function __construct($pattern, array $defaults = array(), array $requirements = array(), array $options = array())
    {
        $this->pattern      = trim($pattern);
        $this->defaults     = $defaults;
        $this->requirements = $requirements;
        $this->options      = $options;
    }

    /**
     * Returns an array of parameters if the URL matches this route, false otherwise.
     *
     * @param  string  $url     The URL
     *
     * @return mixed   An array of parameters
     */
    public function matchesUrl($url)
    {
        if (!$this->compiled)
        {
            $this->compile();
        }

        if (!preg_match($this->regex, $url, $matches))
        {
            return false;
        }

        $defaults   = $this->defaults;
        $parameters = array();

        // *
        if (isset($matches['_star']))
        {
            $parameters = $this->parseStarParameter($matches['_star']);
            unset($matches['_star']
            , $parameters[TMConfig::get("controller", "key")], $parameters[TMConfig::get("action", "key")]);
        }

        // defaults
        $parameters = $this->mergeArrays($defaults, $parameters);

        // variables
        foreach ($matches as $key => $value)
        {
            //排除自身括号的情况，也说明key不能使用int
            if (!is_int($key))
            {
                $parameters[$key] = urldecode($value);
            }
        }

        return $parameters;
    }

    protected function mergeArrays($arr1, $arr2)
    {
        foreach ($arr2 as $key => $value)
        {
            $arr1[$key] = $value;
        }
        
        return $arr1;
    }
    
    /**
     * Compiles the current route instance.
     */
    public function compile()
    {
        if ($this->compiled)
        {
            return;
        }

        $this->initializeOptions();
        $this->fixRequirements();
        $this->fixDefaults();

        $this->compiled = true;
        $this->segments = array();

        // a route must start with a slash
        if (empty($this->pattern) || '/' != $this->pattern[0])
        {
          $this->pattern = '/'.$this->pattern;
        }

        $this->tokenize();

        // parse
        foreach ($this->tokens as $token)
        {
            call_user_func_array(array($this, 'compileFor'.ucfirst(array_shift($token))), $token);
        }

        $separator = '';
        
        if (count($this->tokens))
        {
            //处理如果规则最后是/之类的分隔符，则在最后要加上这个分隔符来做匹配
            $lastToken = $this->tokens[count($this->tokens) - 1];
            $separator = 'separator' == $lastToken[0] ? $lastToken[2] : '';
        }

        $this->regex = "#^".implode("", $this->segments)."".preg_quote($separator, '#')."$#x";
    }

    protected function initializeOptions()
    {
        $this->options = array_merge(array(
            'suffix'                           => '',
            'variable_prefixes'                => array(':'),
            'segment_separators'               => array('/', '.'),
            'variable_regex'                   => '[\w\d_]+',
            'text_regex'                       => '.+?',
            'generate_shortest_url'            => true,
            'extra_parameters_as_query_string' => true,
        ), $this->options);

        $preg_quote_hash = create_function('$a', 'return preg_quote($a, \'#\');');

        // compute some regexes
        $this->options['variable_prefix_regex'] = '(?:'.implode('|', array_map($preg_quote_hash, $this->options['variable_prefixes'])).')';

        if (count($this->options['segment_separators']))
        {
            $this->options['segment_separators_regex'] = '(?:'.implode('|', array_map($preg_quote_hash, $this->options['segment_separators'])).')';

            //处理在没有设定变量value正则情况下的默认正则
            // 在5.3.0的版本之下, preg_quote 不会去转义 "-" (see http://bugs.php.net/bug.php?id=47229)
            $preg_quote_hash_53 = create_function('$a', 'return str_replace(\'-\', \'\-\', preg_quote($a, \'#\'));');
            $this->options['variable_content_regex'] = '[^'.implode('',
            array_map(version_compare(PHP_VERSION, '5.3.0', '>=') ? $preg_quote_hash : $preg_quote_hash_53, $this->options['segment_separators'])
            ).']+';
        }
    }

    /**
     * 修正路由参数变换规则
     */
    protected function fixRequirements()
    {
        foreach ($this->requirements as $key => $regex)
        {
            if (!is_string($regex))
            {
                continue;
            }

            if ('$' == substr($regex, -1))
            {
                $regex = substr($regex, 0, -1);
            }
            if ('^' == $regex[0])
            {
                $regex = substr($regex, 1);
            }
            
            $this->requirements[$key] = $regex;
        }
    }

    protected function fixDefaults()
    {
        foreach ($this->defaults as $key => $value)
        {
            $this->defaults[$key] = urldecode($value);
        }
    }
    
    /**
    * Tokenizes the route.
    */
    protected function tokenize()
    {
        $this->tokens = array();
        $buffer = $this->pattern;
        $afterASeparator = false;
        $currentSeparator = '';
        
        // a route is an array of (separator + variable) or (separator + text) segments
        while (strlen($buffer))
        {
            if ($afterASeparator && preg_match('#^'.$this->options['variable_prefix_regex'].'('.$this->options['variable_regex'].')#', $buffer, $match))
            {
                //处理变量 match[0]表示整个变量 matche[1]表示变量的关键字比如 con
                $this->tokens[] = array('variable', $currentSeparator, $match[1]);
                
                //回归到准备匹配分隔符状态
                $currentSeparator = '';
                $buffer = substr($buffer, strlen($match[0]));
                $afterASeparator = false;
            }
            else if ($afterASeparator && preg_match('#^('.$this->options['text_regex'].')(?:'.$this->options['segment_separators_regex'].'|$)#', $buffer, $match))
            {
                // a text
                $this->tokens[] = array('text', $currentSeparator, $match[1]);
                
                $currentSeparator = '';
                $buffer = substr($buffer, strlen($match[1]));
                $afterASeparator = false;
            }
            else if (!$afterASeparator && preg_match('#^/|^'.$this->options['segment_separators_regex'].'#', $buffer, $match))
            {
                //以斜扛/开头，或者是分隔符
                $this->tokens[] = array('separator', $currentSeparator, $match[0]);
                
                $currentSeparator = $match[0];
                $buffer = substr($buffer, strlen($match[0]));
                $afterASeparator = true;
            }
            else
            {
                // parsing problem
                throw new TMConfigException(sprintf('Unable to parse "%s" route near "%s".', $this->pattern, $buffer));
            }
        }
    }
    
    protected function compileForText($separator, $text)
    {
        //要处理井号的情况
        if ('*' == $text)
        {
            $this->segments[] = '('.preg_quote($separator, '#').'(?P<_star>.*))?';
        }
        else
        {        
            $this->segments[] = preg_quote($separator, '#').preg_quote($text, '#');
        }
    }

    protected function compileForVariable($separator, $variable)
    {
        if (!isset($this->requirements[$variable]))
        {
            $this->requirements[$variable] = $this->options['variable_content_regex'];
        }
        
        $this->segments[] = preg_quote($separator, '#').'(?P<'.$variable.'>'.$this->requirements[$variable].')';
    }

    protected function compileForSeparator($separator, $regexSeparator)
    {
        //do nothing
    }

    protected function parseStarParameter($star)
    {
        $parameters = array();
        $tmp = explode('/', $star);
        $max = count($tmp);
        for ($i = 0; $i < $max; $i += 2)
        {
            if (!empty($tmp[$i]))
            {
              $parameters[$tmp[$i]] = isset($tmp[$i + 1]) ? urldecode($tmp[$i + 1]) : true;
            }
        }
        
        return $parameters;
    }
    
    public function serialize()
    {
        // always serialize compiled routes
        $this->compile();

        return serialize(array($this->tokens, $this->options, $this->pattern, $this->regex, $this->defaults, $this->requirements, $this->suffix));
    }

    public function unserialize($data)
    {
        list($this->tokens, $this->options, $this->pattern, $this->regex, $this->defaults, $this->requirements, $this->suffix) = unserialize($data);
        $this->compiled = true;
    }
    
    /**
     * Returns the compiled pattern.
     *
     * @return string The compiled pattern
     */
    public function getPattern()
    {
        if (!$this->compiled)
        {
            $this->compile();
        }
        
        return $this->pattern;
    }
}
?>