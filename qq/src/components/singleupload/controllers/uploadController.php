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
 * uploadController
 *
 * @package components.singleupload.controllers
 * @author  lynkli <lynkli@tencent.com>
 * @version uploadController.php 2009-10-10 by lynkli
 */
class uploadController extends TMComponentController
{

    /**
     * 上传图片界面
     *
     * @return string 上传图片界面HTML
     */
    function uploadAction()
    {
        $view = new TMView();
        $data = array();
        $appName = $this->request->getGetParameter("app", "");
        $callbackFunName = $this->request->getGetParameter("callbackFunName", "");
        if (empty($appName))
        {
            return 'no application name!';
        }

        $data['app'] = $appName;
        $data['callbackFunName'] = $callbackFunName;
        $singleUpload = TMSingleuploadComponent::getInstance($appName);
        $config = $singleUpload->getConfig();
        $data['i18n'] = $config['i18n']['upload'];
        $data['enableFromQZone'] = isset($config['enableFromQZone']) ? $config['enableFromQZone'] : false;

        return $view->renderFile($data, $this->componentDir . "templates/upload.php");
    }

    /**
     * 保存图片到服务器，并返回图片保存数据
     *
     * @return string 结果页面
     */
    function douploadAction()
    {
        TMBrowserCache::nonCache();

        $templateFile = $this->componentDir . "templates/uploadresult.php";

        $appName = $this->request->getGetParameter("app", "");
        $callbackFunName = $this->request->getGetParameter("callbackFunName", "");
        if (empty($appName))
        {
            return 'no application name!';
        }

        $singleUpload = TMSingleuploadComponent::getInstance($appName);
        
        $data = $singleUpload->doUpload($this->request);
        $data['uploadResultJson'] = json_encode($data['uploadResult']);
        $data['app'] = $appName;
        $data['callbackFunName'] = $callbackFunName;
        
        $view = new TMView();
        return $view->renderFile($data, $templateFile);
    }

    /**
     * 裁剪图片界面
     *
     * @return string 裁剪图片界面HTML
     */
    function cutAction()
    {
        TMBrowserCache::nonCache();

        $data = array();
        $view = new TMView();
        $service = new TMService();

        $templateFile = $this->componentDir . "templates/cut.php";

        $appName = $this->request->getGetParameter("app", "");
        if (empty($appName))
        {
            return 'no application name!';
        }

        $data['app'] = $appName;
        $singleUpload = TMSingleuploadComponent::getInstance($appName);
        $callbackFunName = $this->request->getGetParameter("callbackFunName", "");
        $config = $singleUpload->getConfig();

        $fileid = $this->request->getGetParameter('fileid', 0);
        if (empty($fileid))
        {
            $data['message'] = $config['i18n']['cut']['noId'];
            return $view->renderFile($data, $templateFile);
        }

        $data['fileid'] = $fileid;
        
        //$dbsetting = empty($config['save2db']['insert']) ? $config['save2db']['update'] : $config['save2db']['insert'];
        
        $fResult = $service->select(array("FFileId"=>$fileid), "FUrl", "Tbl_File");
        if (empty($fResult) || empty($fResult[0]['FUrl']))
        {
            $data['message'] = $config['i18n']['cut']['getInfoFailed'];
            return $view->renderFile($data, $templateFile);
        }

        $data['orig_url'] = $config['downloadPath'] . $fResult[0]['FUrl'];
        $data['orig_path'] = $orig_path = $config['uploadPath'] . $fResult[0]['FUrl'];
        
        $origsize = @getimagesize($orig_path);
        if (!is_array($origsize))
        {
            $data['message'] = $config['i18n']['cut']['getImageSizeFailed'];
            return $view->renderFile($data, $templateFile);
        }

        $data['origsize'] = array("w"=>$origsize[0],"h"=>$origsize[1]);
        $fixedsize = $data['origsize'];
        $sWidth = (int) $config['cutinfo']['selector']['width'];
        $sHeight = (int) $config['cutinfo']['selector']['height'];
        if ($data['origsize']['w'] > $sWidth)
        {
            $fixedsize['w'] = $sWidth;
            $fixedsize['h'] = round($data['origsize']['h'] * $sWidth / $data['origsize']['w']);
        }

        if ($fixedsize['h'] > $sHeight)
        {
            $fixedsize['w'] = round($fixedsize['w'] * $sHeight / $fixedsize['h']);
            $fixedsize['h'] = $sHeight;
        }
        $data['fixedsize'] = $fixedsize;
        $data['fixedrate'] = $data['origsize']['w'] == 0 ? 1 : ($fixedsize['w'] / $data['origsize']['w']);

        $data['i18n'] = $config['i18n']['cut'];
        $data['thumb'] = $config['thumb'];
        $data['callbackFunName'] = $callbackFunName;

        return $view->renderFile($data, $templateFile);
    }

    /**
     * 执行图片裁剪，返回裁剪图片结果json数据
     *
     * @return string 裁剪图片结果数据json
     */
    function docutAction()
    {
        TMBrowserCache::nonCache();

        $data = array();
        $view = new TMView();
        $service = new TMService();

        $templateFile = $this->componentDir . "templates/cutresult.php";

        $appName = $this->request->getPostParameter("app", "");
        if (empty($appName))
        {
            return json_encode(array("success"=>false, "message"=>"no application name!", "url"=>""));
        }

        $data['app'] = $appName;
        $singleUpload = TMSingleuploadComponent::getInstance($appName);
        $config = $singleUpload->getConfig();

        $fileid = $this->request->getPostParameter('fileid', 0);
        if (empty($fileid))
        {
            return json_encode(array("success"=>false, "message"=>$config['i18n']['docut']['noId'], "url"=>""));
        }

        $data['fileid'] = $fileid;
        $fResult = $service->select(array("FFileId"=>$fileid), "FUrl", "Tbl_File", 0, 1);
        if (empty($fResult) || empty($fResult[0]['FUrl']))
        {
            return json_encode(array("success"=>false, "message"=>$config['i18n']['docut']['getInfoFailed'], "url"=>""));
        }

        $file = $fResult[0]['FUrl'];
        $arr = explode("/", $file);
        $fileNameIdx = count($arr) - 1;
        $arr[$fileNameIdx] = $config['thumb']['pix'] . $arr[$fileNameIdx];
        $mini = implode("/", $arr);
        $srcFile = $config['uploadPath'] . $file;
        $dscFile = $config['uploadPath'] . $mini;

        TMGraph::cut($srcFile, $_POST['w'], $_POST['h'], $_POST['x'], $_POST['y'], $dscFile, $config['thumb']['width'], $config['thumb']['height']);

        return json_encode(array(
            "success"=>true,
            "message"=>"",
            "url"=>$config['downloadPath'] . $file,
            "mini"=>$config['downloadPath'] . $mini,
            "id"=>$fileid
        ));
    }
}