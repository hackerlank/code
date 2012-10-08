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
 * 处理自动化生成项目
 *
 * @package sdk.mvc.src.framework.builder
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMBuilderManager.class.php 2009-10-30 by ianzhang
 */
class TMBuilderManager {
    /**
     * @var string template path
     *
     * @access private
     */
    private $template;

    /**
     * construction function
     * @access public
     * @param  string $template     template path 模板路径
     * @return void
     */
    public function __construct($template = null )
    {
        if($template != null)
        {
            $this->template = $template;
        }
    }


    /**
     * build folders and files in project 生成项目内的元素
     *
     * @access public
     * @param  array $configParamArray   传入的配置项目数组
     * @return void
     */
    public function buildProject($configParamArray = array())
    {
        $rootPath = ROOT_PATH;

        $configArray = array();
        if(empty($configArray))
        {
            $configArray = TMYaml::load($rootPath."builder.yml");
        }

        foreach($configParamArray as $key => $configParam)
        {
            $configArray[$key] = $configParam;
        }
        
        $author = $configArray["serveradmin"];
        $nameSpace = $configArray["pathname"];
        $version = $configArray["version"];
        
        $domain = $configArray["domain-prefix"].$configArray["domain-postfix"];
        $baseUrl = $configArray["baseurl"];
        if(empty($baseUrl))
        {
            $baseUrl = "http://".$domain."/";
        }

        if(preg_match("/^([a-zA-Z]+)(.+)$/", $version, $matches))
        {
            $framework = $matches[1];
            $version = $matches[2];
        }else{
            $framework = $configArray["framework"];
        }
        
        if($this->template == null)
        {
            $this->template = MVC_PATH."builder";
        }

        if(!is_dir($this->template))
        {
            throw new TMBuilderException("version info is error");
        }

        TMBuilder::copyDir($this->template ,$rootPath, array(".svn"));
        exec("chmod 777 ".$rootPath." -R");

        $configExtractArray = array();
        $configExtractArray["machine_ip"] = $configArray["machine_ip"];
        $configExtractArray["appid"] = $configArray["appid"];
        $configExtractArray["namespace"] = $nameSpace;
        $configExtractArray["tamsid"] = $configArray["tams_id"];
        $configExtractArray["domain"] = $domain;
        $configExtractArray["baseurl"] = $baseUrl;
        $configExtractArray["dbname"] = $configArray["dbname"];
        $configExtractArray["pro_dbhost"] = $configArray["dbhost-online"];
        $configExtractArray["author"] = $author;
        $configExtractArray["version"] = $version;
        $configExtractArray["framework"] = $framework;
        $configExtractArray["date"] = date("Y-m-d");
        $configExtractArray["full_namespace"] = $configExtractArray["domain"] . "_" . $configExtractArray["tamsid"];

        $this->recursionCreateFile($rootPath."src", $configExtractArray);

        $this->buildSqlFile($rootPath, $configExtractArray);

        $this->buildSvnImportFile($rootPath, $configExtractArray);

        $symlinkPath = "/usr/local/tads/htdocs/".$configExtractArray["full_namespace"]."_view";
        $configExtractArray["symLinkPath"] = $symlinkPath;
        $this->buildApacheConfig($rootPath, $configExtractArray);

        TMBuilder::copyDir(MVC_PATH."component" ,$rootPath."src/components", array(".svn"));
        $this->handleComponentsResource($rootPath."src/components");
        
        exec("chmod 777 ".$rootPath."src/components"." -R");
        
        $etcYmlPath = TMConfig::getEtcYmlPath();
        if(!is_file($etcYmlPath))
        {
            rename($rootPath."default.yml", $etcYmlPath);
            chmod($etcYmlPath, 0644);
        }else{
            exec("rm {$rootPath}default.yml");
        }
        
        exec("rm -r {$rootPath}cache");

        $phpSelf = $_SERVER['PHP_SELF'];
        if(preg_match("/.+\/(.+\.php)$/", $phpSelf, $matches)){
            exec("rm {$matches[1]}");
        }

        exec("/usr/local/apache2/bin/apachectl restart");
    }

    /**
     * 处理组件资源的复制
     * @param string $componentPath
     */
    protected function handleComponentsResource($componentPath)
    {
        $handle = opendir($componentPath); //打开当前目录

        //循环读取文件
        while (false !== ($file = readdir($handle)))
        {
            if($file == "." || $file == "..")
            {
                continue;
            }
            $fileFrom = $componentPath . DIRECTORY_SEPARATOR .$file;

            if (is_dir($fileFrom))
            {
                $tmpHandle = opendir($fileFrom);
                while (false !== ($tmpFile = readdir($tmpHandle)))
                {
                    if($tmpFile == "resources")
                    {
                        $resourceFrom = $fileFrom.DIRECTORY_SEPARATOR
                            .$tmpFile.DIRECTORY_SEPARATOR.$file;
                        if(is_dir($resourceFrom)){
                            TMBuilder::copyDir($resourceFrom, ROOT_PATH."src/web/components/".$file, array(".svn"));
                        }
                    }
                }
                closedir($tmpHandle);
            }
        }
        closedir($handle);   
    }
    
    /**
     * 从windows环境创建项目
     * @param array $configParamArray
     */
    public function buildProjectForWindows($configParamArray = array())
    {
        $rootPath = ROOT_PATH;

        $configArray = array();
        if(empty($configArray))
        {
            $configArray = TMYaml::load($rootPath."builder.yml");
        }

        foreach($configParamArray as $key => $configParam)
        {
            $configArray[$key] = $configParam;
        }
        
        $author = $configArray["serveradmin"];
        $nameSpace = $configArray["pathname"];
        $version = $configArray["version"];
        
        $domain = $configArray["domain-prefix"].$configArray["domain-postfix"];
        $baseUrl = $configArray["baseurl"];
        if(empty($baseUrl))
        {
            $baseUrl = "http://".$domain."/";
        }

        if(preg_match("/^([a-zA-Z]+)(.+)$/", $version, $matches))
        {
            $framework = $matches[1];
            $version = $matches[2];
        }else{
            $framework = $configArray["framework"];
        }
        
        if($this->template == null)
        {
            $this->template = MVC_PATH."builder";
        }

        if(!is_dir($this->template))
        {
            throw new TMBuilderException("version info is error");
        }

        TMBuilder::copyDir($this->template ,$rootPath, array(".svn"));

        $configExtractArray = array();
        $configExtractArray["machine_ip"] = $configArray["machine_ip"];
        $configExtractArray["appid"] = $configArray["appid"];
        $configExtractArray["namespace"] = $nameSpace;
        $configExtractArray["tamsid"] = $configArray["tams_id"];
        $configExtractArray["domain"] = $domain;
        $configExtractArray["baseurl"] = $baseUrl;
        $configExtractArray["dbname"] = $configArray["dbname"];
        $configExtractArray["pro_dbhost"] = $configArray["dbhost-online"];
        $configExtractArray["author"] = $author;
        $configExtractArray["version"] = $version;
        $configExtractArray["framework"] = $framework;
        $configExtractArray["date"] = date("Y-m-d");
        $configExtractArray["full_namespace"] = $configExtractArray["domain"] . "_" . $configExtractArray["tamsid"];

        $this->recursionCreateFile($rootPath."src", $configExtractArray);

        $this->buildSqlFile($rootPath, $configExtractArray);

        //apache conf
        $symlinkPath = "/usr/local/tads/htdocs/".$configExtractArray["full_namespace"]."_view";
        $configExtractArray["symLinkPath"] = $symlinkPath;       
        $this->recursionCreateFile($rootPath."builder/apache", $configExtractArray);
        rename($rootPath."builder/apache/apache.conf", $rootPath."builder/apache/".$configExtractArray["full_namespace"].".conf");
        
        TMBuilder::copyDir(MVC_PATH."component" ,$rootPath."src/components", array(".svn"));
        $this->handleComponentsResource($rootPath."src/components");

        unlink("{$rootPath}buildprojectforvendor.php");
        
        unlink("{$rootPath}default.yml");
        
        exec("rmdir /s/q cache");

    }
    
    /**
     * 远程创建项目
     * @param array $configArray  配置数组
     * @param boolean $isNeedApache   是否需要重启apache
     * @return void
     */
    public function buildProjectForRemote($configArray = array(), $isNeedApache = false)
    {
        $author = $configArray["serveradmin"];
        $nameSpace = $configArray["pathname"];
        $version = $configArray["version"];
        $domain = $configArray["domain-prefix"].$configArray["domain-postfix"];
        $baseUrl = $configArray["baseurl"];
        if(empty($baseUrl))
        {
            $baseUrl = "http://".$domain."/";
        }

        $version = preg_replace("/^([a-zA-Z]+)(.+)$/", "$1/$2", $version);

        if($this->template == null)
        {
            $this->template = "/usr/local/taesdk/$version/$framework/src/builder";
        }

        $rootPath = "/home/".$author."/".$nameSpace;
        if(is_dir($rootPath))
        {
            throw new TMBuilderException("project dir exsits");
        }

        TMBuilder::copyDir($this->template ,$rootPath, array(".svn"));
        exec("chmod 777 ".$rootPath." -R");

        $configExtractArray = array();
        $configExtractArray["machine_ip"] = $configArray["machine_ip"];
        $configExtractArray["appid"] = $configArray["appid"];
        $configExtractArray["namespace"] = $nameSpace;
        $configExtractArray["tamsid"] = $configArray["tams_id"];
        $configExtractArray["domain"] = $domain;
        $configExtractArray["baseurl"] = $baseUrl;
        $configExtractArray["dbname"] = $configArray["dbname"];
        $configExtractArray["pro_dbhost"] = $configArray["dbhost-online"];
        $configExtractArray["author"] = $author;
        $configExtractArray["version"] = $version;
        $configExtractArray["date"] = date("Y-m-d");
        $configExtractArray["full_namespace"] = $configExtractArray["domain"] . "_" . $configExtractArray["tamsid"];

        $this->recursionCreateFile($rootPath."src", $configExtractArray);

        $this->buildSqlFile($rootPath, $configExtractArray);

        $this->buildSvnImportFile($rootPath, $configExtractArray);

        $symlinkPath = "/usr/local/tads/htdocs/".$configExtractArray["full_namespace"]."_view";
        $configExtractArray["symLinkPath"] = $symlinkPath;
        $this->buildApacheConfig($rootPath, $configExtractArray);

        if($isNeedApache){
            exec("/usr/local/apache2/bin/apachectl restart");
        }
    }

    /**
     * 递归目录创建文件
     *
     * @param string $tplFolder     文件夹路径
     * @param array $configExtractArray   配置数组
     */
    private function recursionCreateFile($tplFolder, array $configExtractArray)
    {
        $handle = opendir($tplFolder);
        while (false !== ($file = readdir($handle)))
        {
            if(substr($file,0,1) == ".")
            {
                continue;
            }
            $fileFrom = $tplFolder . DIRECTORY_SEPARATOR .$file;
            if (is_dir($fileFrom))
            {
                $this->recursionCreateFile($fileFrom, $configExtractArray);
            }
            else if (preg_match("/^(.*?)\.tpl\.php/i", $file, $matches))
            {
                $configFilePath = $tplFolder . DIRECTORY_SEPARATOR .$matches[1];
                TMBuilder::buildFile($configFilePath, $fileFrom, $configExtractArray);
                unlink($fileFrom);
            }
        }
        closedir($handle);
    }

    /**
     * build apache config file and create soft link 建立apache confi文件,建立软连接
     *
     * @access private
     * @param  string $rootPath              the root path
     * @param  array $configExtractArray     config extract array
     * @return void
     */
    private function buildApacheConfig($rootPath, $configExtractArray)
    {
        $symlinkPath = $configExtractArray["symLinkPath"];
        $nameSpace = $configExtractArray["namespace"];

        if(!file_exists($symlinkPath))
        {
            @symlink($rootPath."src/web/", $symlinkPath);
        }

        $this->recursionCreateFile($rootPath."builder/apache", $configExtractArray);

        rename($rootPath."builder/apache/apache.conf", $rootPath."builder/apache/".$configExtractArray["full_namespace"].".conf");

        $apacheConfigFilePath = "/usr/local/apache2/conf/vhost.d/".$configExtractArray["full_namespace"].".conf";
        if(!file_exists($apacheConfigFilePath))
        {
            copy($rootPath."/builder/apache/".$configExtractArray["full_namespace"].".conf", $apacheConfigFilePath);
        }
    }

    /**
     * build template sql file in sql folder 建立sql下标准模板文件
     *
     * @access private
     * @param  string $rootPath          the root path
     * @param  array $configExtractArray     config extract array
     * @return void
     */
    private function buildSqlFile($rootPath, $configExtractArray)
    {
        $nameSpace = $configExtractArray['full_namespace'];
        
        $this->recursionCreateFile($rootPath."builder/sql", $configExtractArray);
        rename($rootPath."builder/sql/project.sql", $rootPath."builder/sql/".$nameSpace.".sql");
    }

    /**
     * 生成SVN导入脚本
     */
    private function buildSvnImportFile( $rootPath, $configExtractArray) {
        $importFilePath = $rootPath."builder/svnimport.tpl.php";
        TMBuilder::buildFile($importFilePath, $this->template."/builder/svnimport.tpl.php",$configExtractArray);

        $shFile = $rootPath . "/svnimport.sh";
        rename($importFilePath, $shFile);
        exec("chmod +x $shFile");
    }
}
