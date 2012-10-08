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
 * Web Request extends TMRequest for web request
 * @package sdk.lib3.src.base.core
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMWebRequest.class.php 2008-9-11 by ianzhang
 */
class TMWebRequest extends TMRequest
{
    protected
    $languages              = null,
    $charsets               = null,
    $acceptableContentTypes = null,
    $pathInfoArray          = null,
    $relativeUrlRoot        = null,
    $getParameters          = null,
    $postParameters         = null,
    $formats                = array(),
    $format                 = null;

    private static $instance = null;

    /**
     * Get instance function
     *
     * @param array $parameters
     * @param array $attributes
     * @return TMWebRequest
     */
    public static function getInstance($parameters = array(), $attributes = array())
    {
        if(self::$instance === null)
        {
            self::$instance = new TMWebRequest($parameters, $attributes);
        }

        return self::$instance;
    }

    /**
     * Initializes this WebRequest.
     * @param array $parameters An associative array of initialization parameters
     * @param array $attributes An associative array of initialization attributes
     */
    public function initialize($parameters = array(), $attributes = array())
    {
        parent::initialize($parameters, $attributes);

        if (isset($_SERVER['REQUEST_METHOD']))
        {
            switch ($_SERVER['REQUEST_METHOD'])
            {
                case 'GET':
                    $this->setMethod(self::GET);
                    break;

                case 'POST':
                    $this->setMethod(self::POST);
                    break;

                case 'PUT':
                    $this->setMethod(self::PUT);
                    break;

                case 'DELETE':
                    $this->setMethod(self::DELETE);
                    break;

                case 'HEAD':
                    $this->setMethod(self::HEAD);
                    break;

                default:
                    $this->setMethod(self::GET);
            }
        }
        else
        {
            // set the default method
            $this->setMethod(self::GET);
        }

        foreach ($this->getAttribute('formats', array()) as $format => $mimeTypes)
        {
            $this->setFormat($format, $mimeTypes);
        }

        // load parameters from GET/POST
        $this->loadParameters();
    }

    /**
     * Loads GET, PATH_INFO and POST data into the parameter list.
     */
    protected function loadParameters()
    {
        // GET parameters
        $this->getParameters = get_magic_quotes_gpc() ? TMUtil::stripslashesDeep($_GET) : $_GET;
        foreach($this->getParameters as $key => $parameter)
        {
            $this->getParameters[$key] = TMUtil::filterTextDeep($parameter);
        }
        $this->parameterHolder->add($this->getParameters);

        // POST parameters
        $this->postParameters = get_magic_quotes_gpc() ? TMUtil::stripslashesDeep($_POST) : $_POST;
        foreach($this->postParameters as $key => $parameter)
        {
            $this->postParameters[$key] = TMUtil::filterTextDeep($parameter);
        }
        $this->parameterHolder->add($this->postParameters);
    }

    //--------------------------File Operation-------------------------------//

    /**
     * Retrieves an array of file information.
     * @param string $name A file name
     * @return array An associative array of file information, if the file exists, otherwise null
     */
    public function getFile($name)
    {
        return $this->hasFile($name) ? $this->getFileValues($name) : null;
    }

    /**
     * Retrieves a file error.
     * @param    string $name    A file name
     * @return int One of the following error codes:
     * - <b>UPLOAD_ERR_OK</b> (no error)
     * - <b>UPLOAD_ERR_INI_SIZE</b> (the uploaded file exceeds the upload_max_filesize directive in php.ini)
     * - <b>UPLOAD_ERR_FORM_SIZE</b> (the uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form)
     * - <b>UPLOAD_ERR_PARTIAL</b> (the uploaded file was only partially uploaded)
     * - <b>UPLOAD_ERR_NO_FILE</b> (no file was uploaded)
     */
    public function getFileError($name)
    {
        return $this->hasFile($name) ? $this->getFileValue($name, 'error') : UPLOAD_ERR_NO_FILE;
    }

    /**
     * Retrieves a file name.
     * @param string $name A file nam.
     * @return string A file name, if the file exists, otherwise null
     */
    public function getFileName($name)
    {
        return $this->hasFile($name) ? $this->getFileValue($name, 'name') : null;
    }

    /**
     * Retrieves an array of file names.
     * @return array An indexed array of file names
     */
    public function getFileNames()
    {
        return array_keys($_FILES);
    }

    /**
     * Retrieves an array of files.
     * @access public
     * @param string $key A key
     * @return array An associative array of files
     */
    public function getFiles($key = null)
    {
        return is_null($key) ? $_FILES : (isset($_FILES[$key]) ? $_FILES[$key] : array());
    }

    /**
     * Retrieves a file path.
     * @param string $name A file name
     * @return string A file path, if the file exists, otherwise null
     */
    public function getFilePath($name)
    {
        return $this->hasFile($name) ? $this->getFileValue($name, 'tmp_name') : null;
    }

    /**
     * Retrieve a file size.
     * @param string $name A file name
     * @return int A file size, if the file exists, otherwise null
     */
    public function getFileSize($name)
    {
        return $this->hasFile($name) ? $this->getFileValue($name, 'size') : null;
    }

    /**
     * Retrieves a file type.
     * This may not be accurate. This is the mime-type sent by the browser during the upload.
     * @param    string $name    A file name
     * @return string A file type, if the file exists, otherwise null
     */
    public function getFileType($name)
    {
        return $this->hasFile($name) ? $this->getFileValue($name, 'type') : null;
    }

    /**
     * Indicates whether or not a file exists.
     * @param    string $name    A file name
     * @return bool true, if the file exists, otherwise false
     */
    public function hasFile($name)
    {
        if (preg_match('/^(.+?)\[(.+?)\]$/', $name, $match))
        {
            return isset($_FILES[$match[1]]['name'][$match[2]]);
        }
        else
        {
            return isset($_FILES[$name]);
        }
    }

    /**
     * Indicates whether or not a file error exists.
     * @param    string $name    A file name
     * @return bool true, if the file error exists, otherwise false
     */
    public function hasFileError($name)
    {
        return $this->hasFile($name) ? ($this->getFileValue($name, 'error') != UPLOAD_ERR_OK) : false;
    }

    /**
     * Indicates whether or not any file errors occured.
     * @return bool true, if any file errors occured, otherwise false
     */
    public function hasFileErrors()
    {
        foreach ($this->getFileNames() as $name)
        {
            if ($this->hasFileError($name) === true)
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Indicates whether or not any files exist.
     * @return boolean true, if any files exist, otherwise false
     */
    public function hasFiles()
    {
        return (count($_FILES) > 0);
    }

    /**
     * Retrieves a file value.
     * @param string $name A file name
     * @param string $key Value to search in the file
     * @return string File value
     */
    public function getFileValue($name, $key=0)
    {
        if (preg_match('/^(.+?)\[(.+?)\]$/', $name, $match))
        {
            return $_FILES[$match[1]][$key][$match[2]];
        }
        else
        {
            return $_FILES[$name][$key];
        }
    }

    /**
     * Retrieves all the values from a file.
     * @param    string $name    A file name
     * @return array Associative list of the file values
     */
    public function getFileValues($name)
    {
        if (preg_match('/^(.+?)\[(.+?)\]$/', $name, $match))
        {
            return array(
                'name'         => $_FILES[$match[1]]['name'][$match[2]],
                'type'         => $_FILES[$match[1]]['type'][$match[2]],
                'tmp_name' => $_FILES[$match[1]]['tmp_name'][$match[2]],
                'error'        => $_FILES[$match[1]]['error'][$match[2]],
                'size'         => $_FILES[$match[1]]['size'][$match[2]],
            );
        }
        else
        {
            return $_FILES[$name];
        }
    }

    /**
     * Retrieves the uniform resource identifier for the current web request.
     * @return string Unified resource identifier
     */
    public function getUri()
    {
        $pathArray = $this->getPathInfoArray();

        if ($this->isAbsUri())
        {
            return $pathArray['REQUEST_URI'];
        }

        return $this->getUriPrefix().$pathArray['REQUEST_URI'];
    }

    /**
     * See if the client is using absolute uri
     * @return boolean true, if is absolute uri otherwise false
     */
    public function isAbsUri()
    {
        $pathArray = $this->getPathInfoArray();

        return preg_match('/^http/', $pathArray['REQUEST_URI']);
    }

    /**
     * Returns Uri prefix, including protocol, hostname and server port.
     * @return string Uniform resource identifier prefix
     */
    public function getUriPrefix()
    {
        $pathArray = $this->getPathInfoArray();
        if ($this->isSecure())
        {
            $standardPort = '443';
            $protocol = 'https';
        }
        else
        {
            $standardPort = '80';
            $protocol = 'http';
        }

        $host = explode(":", $pathArray['HTTP_HOST']);
        if (count($host) == 1)
        {
            $host[] = $pathArray['SERVER_PORT'];
        }

        if ($host[1] == $standardPort || empty($host[1]))
        {
            unset($host[1]);
        }

        return $protocol.'://'.implode(':', $host);;
    }

    /**
     * Retrieves the path info for the current web request.
     * @return string Path info
     */
    public function getPathInfo()
    {
        $pathInfo = '';

        $pathArray = $this->getPathInfoArray();

        if (!isset($pathArray['PATH_INFO']) || !$pathArray['PATH_INFO'])
        {
            if (isset($pathArray['REQUEST_URI']))
            {
                $script_name = $this->getScriptName();
                $uri_prefix = $this->isAbsUri() ? $this->getUriPrefix() : '';
                $pathInfo = preg_replace('/^'.preg_quote($uri_prefix, '/').'/','',$pathArray['REQUEST_URI']);
                $pathInfo = preg_replace('/^'.preg_quote($script_name, '/').'/', '', $pathInfo);
                $prefix_name = preg_replace('#/[^/]+$#', '', $script_name);
                $pathInfo = preg_replace('/^'.preg_quote($prefix_name, '/').'/', '', $pathInfo);
                $pathInfo = preg_replace('/'.preg_quote($pathArray['QUERY_STRING'], '/').'$/', '', $pathInfo);
            }
        }
        else
        {
            $pathInfo = $pathArray['PATH_INFO'];
            if ($relative_url_root = $this->getRelativeUrlRoot())
            {
                $pathInfo = preg_replace('/^'.str_replace('/', '\\/', $relative_url_root).'\//', '', $pathInfo);
            }
        }

        if (!$pathInfo)
        {
            $pathInfo = '/';
        }

        return $pathInfo;
    }

    /**
     * Get the request get parameters
     * @return array     the request get parameters
     */
    public function getGetParameters()
    {
        return $this->getParameters;
    }

    /**
     * Get the request post parameters
     * @return array     the request post parameters
     */
    public function getPostParameters()
    {
        return $this->postParameters;
    }

    /**
     * Returns referer.
     * @return string
     */
    public function getReferer()
    {
        $pathArray = $this->getPathInfoArray();

        return isset($pathArray['HTTP_REFERER']) ? $pathArray['HTTP_REFERER'] : '';
    }

    /**
     * Returns current host name.
     * @return string
     */
    public function getHost()
    {
        $pathArray = $this->getPathInfoArray();

        return isset($pathArray['HTTP_X_FORWARDED_HOST']) ? $pathArray['HTTP_X_FORWARDED_HOST'] : (isset($pathArray['HTTP_HOST']) ? $pathArray['HTTP_HOST'] : '');
    }

    /**
     * Returns current script name.
     * @return string
     */
    public function getScriptName()
    {
        $pathArray = $this->getPathInfoArray();

        return isset($pathArray['SCRIPT_NAME']) ? $pathArray['SCRIPT_NAME'] : (isset($pathArray['ORIG_SCRIPT_NAME']) ? $pathArray['ORIG_SCRIPT_NAME'] : '');
    }

    /**
     * Checks if the request method is the given one.
     * @param    string $method    The method name
     * @return bool true if the current method is the given one, false otherwise
     */
    public function isMethod($method)
    {
        $pathArray = $this->getPathInfoArray();

        return strtolower($method) == strtolower($this->getMethodName());
    }

    /**
     * Returns request method.
     * @return string
     */
    public function getMethodName()
    {
        $pathArray = $this->getPathInfoArray();

        return isset($pathArray['REQUEST_METHOD']) ? $pathArray['REQUEST_METHOD'] : 'GET';
    }

    /**
     * Returns the preferred culture for the current request.
     * @param    array    $cultures    An array of ordered cultures available
     * @return string The preferred culture
     */
    public function getPreferredCulture(array $cultures = null)
    {
        $preferredCultures = $this->getLanguages();

        if (is_null($cultures))
        {
            return isset($preferredCultures[0]) ? $preferredCultures[0] : null;
        }

        if (!$preferredCultures)
        {
            return $cultures[0];
        }

        $preferredCultures = array_values(array_intersect($preferredCultures, $cultures));

        return isset($preferredCultures[0]) ? $preferredCultures[0] : $cultures[0];
    }

    /**
     * Gets a list of languages acceptable by the client browser
     * @return array Languages ordered in the user browser preferences
     */
    public function getLanguages()
    {
        if ($this->languages)
        {
            return $this->languages;
        }

        if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            return array();
        }

        $languages = $this->splitHttpAcceptHeader($_SERVER['HTTP_ACCEPT_LANGUAGE']);
        foreach ($languages as $lang)
        {
            if (strstr($lang, '-'))
            {
                $codes = explode('-', $lang);
                if ($codes[0] == 'i')
                {
                    // Language not listed in ISO 639 that are not variants
                    // of any listed language, which can be registerd with the
                    // i-prefix, such as i-cherokee
                    if (count($codes) > 1)
                    {
                        $lang = $codes[1];
                    }
                }
                else
                {
                    for ($i = 0, $max = count($codes); $i < $max; $i++)
                    {
                        if ($i == 0)
                        {
                            $lang = strtolower($codes[0]);
                        }
                        else
                        {
                            $lang .= '_'.strtoupper($codes[$i]);
                        }
                    }
                }
            }

            $this->languages[] = $lang;
        }

        return $this->languages;
    }

    /**
     * Gets a list of charsets acceptable by the client browser.
     * @return array List of charsets in preferable order
     */
    public function getCharsets()
    {
        if ($this->charsets)
        {
            return $this->charsets;
        }

        if (!isset($_SERVER['HTTP_ACCEPT_CHARSET']))
        {
            return array();
        }

        $this->charsets = $this->splitHttpAcceptHeader($_SERVER['HTTP_ACCEPT_CHARSET']);

        return $this->charsets;
    }

    /**
     * Gets a list of content types acceptable by the client browser
     * @return array Languages ordered in the user browser preferences
     */
    public function getAcceptableContentTypes()
    {
        if ($this->acceptableContentTypes)
        {
            return $this->acceptableContentTypes;
        }

        if (!isset($_SERVER['HTTP_ACCEPT']))
        {
            return array();
        }

        $this->acceptableContentTypes = $this->splitHttpAcceptHeader($_SERVER['HTTP_ACCEPT']);

        return $this->acceptableContentTypes;
    }

    /**
     * Returns true if the request is a XMLHttpRequest.
     * It works if JavaScript library set an X-Requested-With HTTP header.
     * Works with Prototype(DOM), jQuery.
     * @return bool true if the request is an XMLHttpRequest, false otherwise
     */
    public function isXmlHttpRequest()
    {
        return ($this->getHttpHeader('X_REQUESTED_WITH') == 'XMLHttpRequest');
    }

    /**
     * Returns http header.
     * It works if JavaScript library set an X-Requested-With HTTP header.
     * Works with Prototype(DOM), jQuery.
     * @param  string $name        the name of 'X_REQUESTED_WITH'
     * @param  string $prefix     协议前缀
     * @return bool true if the request is an XMLHttpRequest, false otherwise
     */
    public function getHttpHeader($name, $prefix = 'http')
    {
        if ($prefix)
        {
            $prefix = strtoupper($prefix).'_';
        }

        $name = $prefix.strtoupper(strtr($name, '-', '_'));

        $pathArray = $this->getPathInfoArray();

        return isset($pathArray[$name]) ? TMUtil::stripslashesDeep($pathArray[$name]) : null;
    }

    /**
     * Gets a cookie value.
     * @param    string $name Cookie name
     * @param    string $defaultValue    Default value returned when no cookie with given name is found
     * @return mixed $retval
     */
    public function getCookie($name, $defaultValue = null)
    {
        $retval = $defaultValue;

        if (isset($_COOKIE[$name]))
        {
            $retval = get_magic_quotes_gpc() ? TMUtil::stripslashesDeep($_COOKIE[$name]) : $_COOKIE[$name];
        }

        return $retval;
    }

    /**
     * Returns true if the current request is secure (HTTPS protocol).
     * @return boolean
     */
    public function isSecure()
    {
        $pathArray = $this->getPathInfoArray();

        return (
        (isset($pathArray['HTTPS']) && (strtolower($pathArray['HTTPS']) == 'on' || $pathArray['HTTPS'] == 1))
        ||
        (isset($pathArray['HTTP_SSL_HTTPS']) && (strtolower($pathArray['HTTP_SSL_HTTPS']) == 'on' || $pathArray['HTTP_SSL_HTTPS'] == 1))
        ||
        (isset($pathArray['HTTP_X_FORWARDED_PROTO']) && strtolower($pathArray['HTTP_X_FORWARDED_PROTO']) == 'https')
        );
    }

    /**
     * Retrieves relative root url.
     * @return string URL
     */
    public function getRelativeUrlRoot()
    {
        if ($this->relativeUrlRoot === null)
        {
            $this->relativeUrlRoot = $this->getScriptName();
        }

        return $this->relativeUrlRoot;
    }

    /**
     * Sets the relative root url for the current web request.
     * @param string $value    Value for the url
     */
    public function setRelativeUrlRoot($value)
    {
        $this->relativeUrlRoot = $value;
    }

    /**
     * Splits an HTTP header for the current web request.
     * @param string $header    Header to split
     */
    public function splitHttpAcceptHeader($header)
    {
        $values = array();
        foreach (array_filter(explode(',', $header)) as $value)
        {
            // Cut off any q-value that might come after a semi-colon
            if ($pos = strpos($value, ';'))
            {
                $q         = (float) trim(substr($value, $pos + 3));
                $value = trim(substr($value, 0, $pos));
            }
            else
            {
                $q = 1;
            }

            $values[$value] = $q;
        }

        arsort($values);

        return array_keys($values);
    }

    /**
     * Returns the array that contains all request information ($_SERVER or $_ENV).
     * This information is stored in the [sf_path_info_array] constant.
     * @return    array Path information
     */
    protected function getPathInfoArray()
    {
        if (!$this->pathInfoArray)
        {
            $this->pathInfoArray =& $_SERVER;

        }

        return $this->pathInfoArray;
    }

    /**
     * Gets the mime type associated with the format.
     * @param    string $format    The format
     * @return string The associated mime type (null if not found)
     */
    public function getMimeType($format)
    {
        return isset($this->formats[$format]) ? $this->formats[$format][0] : null;
    }

    /**
     * Gets the format associated with the mime type.
     * @param    string $mimeType    The associated mime type
     * @return string The format (null if not found)
     */
    public function getFormat($mimeType)
    {
        foreach ($this->formats as $format => $mimeTypes)
        {
            if (in_array($mimeType, $mimeTypes))
            {
                return $format;
            }
        }

        return null;
    }

    /**
     * Associates a format with mime types.
     * @param string $format The format
     * @param string|array $mimeTypes The associated mime types (the preferred one must be the first as it will be used as the content type)
     */
    public function setFormat($format, $mimeTypes)
    {
        $this->formats[$format] = is_array($mimeTypes) ? $mimeTypes : array($mimeTypes);
    }

    /**
     * Sets the request format.
     * @param string $format    The request format
     */
    public function setRequestFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Gets the request format.
     * If no format is defined by the user, it defaults to the sf_format request parameter if available.
     * @return string The request format
     */
    public function getRequestFormat()
    {
        if (is_null($this->format))
        {
            $acceptableContentTypes = $this->getAcceptableContentTypes();

            // skip if no acceptable content types or browsers
            if (isset($acceptableContentTypes[0]) && 'text/xml' != $acceptableContentTypes[0])
            {
                $this->setRequestFormat($this->getFormat($acceptableContentTypes[0]));
            }
        }

        return $this->format;
    }

    /**
     * Returns the value of a GET parameter.
     * @param    string $name         The GET parameter name
     * @param    string $default    The default value
     * @return string The GET parameter value
     */
    public function getGetParameter($name, $default = null)
    {
        if (isset($this->getParameters[$name]))
        {
            return $this->getParameters[$name];
        }
        else
        {
            return TMUtil::getArrayValueForPath($this->getParameters, $name, $default);
        }
    }

    /**
     * Returns the value of a POST parameter.
     * @param    string $name         The POST parameter name
     * @param    string $default    The default value
     * @return string The POST parameter value
     */
    public function getPostParameter($name, $default = null)
    {
        if (isset($this->postParameters[$name]))
        {
            return $this->postParameters[$name];
        }
        else
        {
            return TMUtil::getArrayValueForPath($this->postParameters, $name, $default);
        }
    }

    /**
     * 添加Request参数
     * @param $parameters
     */
    public function addRequestParameters($parameters)
    {
        $this->getParameters = array_merge($this->getParameters, $parameters);
        $this->getParameterHolder()->add($parameters);
    }
}