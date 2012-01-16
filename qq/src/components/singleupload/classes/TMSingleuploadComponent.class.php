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
 * 单文件（图片）上传组件
 *
 * @package components.singleupload.classes
 * @author  lynkli <lynkli@tencent.com>
 * @version TMSingleuploadComponent.php 2009-10-12 by lynkli
 */
class TMSingleuploadComponent
{
    private $appName = "default";
    private $config = array();

    private static $instances = array();

    /**
     * 获取TMLotteryComponent当前对象
     *
     * @param array $parameters
     * @return TMSingleuploadComponent
     */
    public static function getInstance($app='default')
    {
        if (empty(self::$instances[$app]))
        {
            $class = __CLASS__;
            self::$instances[$app] = new $class($app);
        }

        return self::$instances[$app];
    }

    /**
     * 构造函数
     *
     * @param array $parameters 选项数组 包含 appName:应用名，即需要采用配置文件的名字
     */
    function __construct($appName='default')
    {
        $this->appName = $appName;
    }

    /**
     * 获取配置
     *
     * @return array 配置数组
     */
    public function getConfig()
    {
        if (empty($this->config))
        {
            $this->config = TMBasicConfigHandle::getInstance()->execute(TMDispatcher::getComponentsDir('singleupload') . 'config/' . $this->appName . ".yml");
        }
        
        $this->checkUploadPath($this->config);
        
        return $this->config;
    }
    
    private function checkUploadPath(&$config)
    {
        $datedir = date('Ymd') . '/';

        $config['datedir'] = $datedir;
        if (!isset($config['uploadPath'])) {
            $config['uploadPath'] = TMConfig::get("upload", "upload_path");
        }
        
        if (!isset($config['downloadPath'])) {
            $config['downloadPath'] = TMConfig::get("upload", "download_path");
        }
        
        if (!is_dir($config['uploadPath'] . $config['datedir']))
        {
            @mkdir($config['uploadPath'] . $config['datedir'], 0755);
        }
    }
    
    private function getConfigForUpload($config)
    {
        $configForUpload = array();
        $configForUpload['UPLOAD_ONE_DAY'] = isset($config['onedayLimit']) ? (int) $config['onedayLimit'] : 0;
        //$configForUpload['AUDIO_MAX_SIZE'] = 0;
        $configForUpload['FILE_MAX_SIZE'] = (int) $config['maxSize'];
        $configForUpload['UPLOAD_PATH'] = $config['uploadPath'] . $config['datedir'];
        if (isset($config['water']))
        {
            $configForUpload['WATER_PATH'] = $config['water']['path'];
            $configForUpload['WATER_POINT'] = $config['water']['position'];
            $configForUpload['WATER_X_POS'] = $config['water']['x'];
            $configForUpload['WATER_Y_POS'] = $config['water']['y'];
        }
        if (isset($config['thumb'])) {
            $configForUpload['THUMB_WIDTH'] = $config['thumb']['width'];
            $configForUpload['THUMB_HEIGHT'] = $config['thumb']['height'];
        }
        foreach ($config['errors'] as $key=>$value)
        {
            $configForUpload[$key] = $value;
        }
        $configForUpload['errors'] = $config['i18n']['errors'];
        $configForUpload['validatedTypes'] = $config['types'];
        
        return $configForUpload;
    }
    
    private function callUploadService($request, $fileParameterName, $config, $configForUpload, &$data)
    {
        $uploadServ = new UploadService();
        try
        {
            $fileArray = $uploadServ->initialize($request,$fileParameterName,$config['uploadType'], $configForUpload);
            if (isset($config['onedayLimit']))
            {
                $uploadServ->validateOneDayCount($data['qq'], 'FQQ', 'Tbl_File', $configForUpload);
            }
            $uploadServ->validateCount($fileArray, 1, 1, $configForUpload);
            $uploadServ->validate($fileArray);
            $fileName = $data['qq'] . "_" . date("YmdHis");
            $handler = array();
            if (isset($config['water']))
            {
                $handler[] = 'water';
            }

            $saveFileNameArray = $uploadServ->upload($fileArray,$fileName,$handler,$config['uploadPath'] . $config['datedir']);
            $saveFileName= $saveFileNameArray[0];

            if (!empty($config['maxImageSize']))
            {
                $arr = getimagesize($config['uploadPath'] . $config['datedir'] . $saveFileName);
                if (!$arr)
                {
                    $data['uploadResult'] = array("success"=>false, "message"=>$config['i18n']['doupload']['getImageSizeFailed'], "url"=>"");
                    return false;
                }
                if (!empty($config['maxImageSize']['width']))
                {
                    if (!empty($config['maxImageSize']['width']['min']) && $arr[0] < (int)$config['maxImageSize']['width']['min'])
                    {
                        $data['uploadResult'] = array("success"=>false, "message"=>sprintf($config['i18n']['doupload']['imageWidthMin'], $config['maxImageSize']['width']['min']), "url"=>"");
                        return false;
                    }

                    if (!empty($config['maxImageSize']['width']['max']) && $arr[0] > (int)$config['maxImageSize']['width']['max'])
                    {
                        $data['uploadResult'] = array("success"=>false, "message"=>sprintf($config['i18n']['doupload']['imageWidthMax'], $config['maxImageSize']['width']['max']), "url"=>"");
                        return false;
                    }
                }

                if (!empty($config['maxImageSize']['height']))
                {
                    if (!empty($config['maxImageSize']['height']['min']) && $arr[1] < (int)$config['maxImageSize']['height']['min'])
                    {
                        $data['uploadResult'] = array("success"=>false, "message"=>sprintf($config['i18n']['doupload']['imageHeightMin'], $config['maxImageSize']['height']['min']), "url"=>"");
                        return false;
                    }

                    if (!empty($config['maxImageSize']['height']['max']) && $arr[1] > (int)$config['maxImageSize']['height']['max'])
                    {
                        $data['uploadResult'] = array("success"=>false, "message"=>sprintf($config['i18n']['doupload']['imageHeightMax'], $config['maxImageSize']['height']['max']), "url"=>"");
                        return false;
                    }
                }

                if (!empty($config['maxImageSize']['multiply']))
                {
                    if (!empty($config['maxImageSize']['multiply']['min']) && $arr[0] * $arr[1] < (int)$config['maxImageSize']['multiply']['min'])
                    {
                        $data['uploadResult'] = array("success"=>false, "message"=>$config['i18n']['doupload']['imageMultiplyMin'], "url"=>"");
                        return false;
                    }

                    if (!empty($config['maxImageSize']['multiply']['max']) && $arr[0] * $arr[1] > (int)$config['maxImageSize']['multiply']['max'])
                    {
                        $data['uploadResult'] = array("success"=>false, "message"=>$config['i18n']['doupload']['imageMultiplyMax'], "url"=>"");
                        return false;
                    }
                }
            }

            if (!empty($config['thumb']))
            {
                $scale = (!isset($config['thumb']['scale']) || $config['thumb']['scale'] == "1") ? true : false;
                $stretch = (!isset($config['thumb']['stretch']) || $config['thumb']['stretch'] == "1") ? true : false;
                TMGraph::makeThumb($config['uploadPath'] . $config['datedir'] . $saveFileName, $config['thumb']['width'], $config['thumb']['height'], $stretch, $scale);
            }
        }
        catch (TMUploadException $ue)
        {
            $data['uploadResult'] = array("success"=>false, "message"=>$ue->getMessage(), "url"=>"");
            return false;
        }
        
        $data['saveFileName'] = $saveFileName;
        
        return true;
    }
    
    private function save2db($config, $data)
    {
        if (empty($config['save2db'])) {
            return 0;
        }
        
        $dbsetting = empty($config['save2db']['insert']) ? $config['save2db']['update'] : $config['save2db']['insert'];
        
        $service = new TMService();
        $dbData = array();
        //链接地址
        if (isset($dbsetting['urlField']))
        {
            $dbData[$dbsetting['urlField']] = $config['datedir'] . $data['saveFileName'];
        }
        //缩略图链接地址
        if (isset($dbsetting['miniField']) && !empty($config['thumb']))
        {
            $dbData[$dbsetting['miniField']] = $config['datedir'] . $config['thumb']['pix'] . $data['saveFileName'];
        }
        //描述
        if (isset($dbsetting['descField']))
        {
            $dbData[$dbsetting['descField']] = $data['desc'];
        }
        //时间
        if (isset($dbsetting['timeField']))
        {
            TMService::setTimeForUpdateOrInsert($dbData, $dbsetting['timeField']);
        }
        //日期
        if (isset($dbsetting['dateField']))
        {
            TMService::setDateForUpdateOrInsert($dbData, $dbsetting['dateField']);
        }
        
        foreach ($dbsetting['otherDefaultValue'] as $k=>$v)
        {
                $dbData[$k] = $v;
        }
        
        if (isset($config['save2db']['insert']))
        {
            if (isset($dbsetting['qqField']))
            {
                $dbData[$dbsetting['qqField']] = $data['qq'];
            }
            $service->insert($dbData, $dbsetting['table']);
            return $service->getInsertId();
        }
        else
        {
            $condition = array();
            if (isset($dbsetting['conditionField']))
            {
                $condition[$dbsetting['conditionField']] = $data['qq'];
            }
            foreach ($dbsetting['staticCondition'] as $k=>$v)
            {
                    $condition[$k] = $v;
            }
            
            $service->update($dbData, $condition, $dbsetting['table']);
            return 0;
        }
    }
    
    private function validate($request, $config, &$data, $qq)
    {
        if (empty($data['qq'])) {
            try
            {
                $data['qq'] = TMAuthUtils::getUin();
            }
            catch (TMException $te)
            {
                $data['uploadResult'] = array("success"=>false, "message"=>$te->getMessage(), "url"=>"");
                return false;
            }
        } else {
            $data['qq'] = $qq;
        }
        
        $data['desc'] = $request->getParameter('fileDesc', '');
        $descMaxLength = empty($config['descMaxLength']) ? 80 : $config['descMaxLength'];
        if (TMUtil::getStringLength($data['desc']) > $descMaxLength) {
            $data['uploadResult'] = array("success"=>false, "message"=>sprintf($config['i18n']['doupload']['imageHeightMax'], $descMaxLength), "url"=>"");
            return false;
        }
        
        return true;
    }
    
    /**
     * 执行上传
     *
     * @param TMWebRequest $request
     * @return array
     */
    public function doUpload($request, $fileParameterName='fileSrc', $qq='')
    {
        $config = $this->getConfig();
        
        $configForUpload = $this->getConfigForUpload($config);

        $data['i18n'] = $config['i18n']['doupload'];
        
        if (!$this->validate($request, $config, $data, $qq))
        {
            return $data;
        }
        
        if (!$this->callUploadService($request, $fileParameterName, $config, $configForUpload, & $data))
        {
            return $data;
        }
        
        $r = $this->save2db($config, $data);
        $id = is_numeric($r) ? $r : 0;
        
        $rData = array(
            "success" => true,
            "message" => "",
            "url" => $config['downloadPath'] . $config['datedir'] . $data['saveFileName'],
            "desc" => $data['desc'],
            "id" => $id
            );
        if (!empty($config['thumb']))
        {
            $rData['mini'] = $config['downloadPath'] . $config['datedir'] .  $config['thumb']['pix'] . $data['saveFileName'];
        }
        
        $data['uploadResult'] = $rData;
        return $data;
    }
}
