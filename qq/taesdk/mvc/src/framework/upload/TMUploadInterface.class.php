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
 * The upload interface
 *
 * @package sdk.mvc.src.framework.upload
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMUploadInterface.class.php 2008-10-20 by ianzhang
 */
interface TMUploadInterface {
    /**
     * Move the tmp upload file to the disk file
     *
     * @param string $filename         the file name
     * @param integer $fileMode        the created file mode(example: 0644, 0755)
     * @param boolean $create          whether the new file is created if the file doesn't exist
     * @param integer $dirMode         the directory mode(example: 0644, 0755 )
     *
     * @return return string $filePath
     * @throws TMUploadException
     */
    public function upload($filename, $fileMode = 0644, $create = true, $dirMode = 0755);

    /**
     * Validate the single upload operation
     *
     * @throws TMUploadException
     */
    public function validate();

    /**
     * Handle the upload file (example: compress, water mark, thumb and so on)
     *
     * @param string $filename         the source file path
     * @param array $methodArray       the handle method array,
     *                                 example: array("water" => true, "thumb" => true)
     *
     * @return string $filePix         the file name pix
     * @throws TMUploadException
     */
    public function handle($filename, array $methodArray =array());
}