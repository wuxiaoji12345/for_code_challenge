<?php

namespace backend\controllers;
use common\helpers\UploadOss;
use crazydb\ueditor\Uploader;
use yii\web\UploadedFile;

class UeditorController extends \crazydb\ueditor\UEditorController
{
    public function init()
    {
        parent::init();
        //do something
        //这里可以对扩展的访问权限进行控制
    }

    public function actionUploadImage()
    {
        $config = [
            'pathFormat' => $this->config['imagePathFormat'],
            'maxSize' => $this->config['imageMaxSize'],
            'allowFiles' => $this->config['imageAllowFiles']
        ];
        $fieldName = $this->config['imageFieldName'];
        $url = $this->uploadFile($fieldName);
        $up = new Uploader($fieldName, $config);
        $result = $up->getFileInfo();
        $result['url'] = $url;
        return $this->show($result);
    }

    protected function uploadFile($filedName)
    {
        $imgObj = UploadedFile::getInstanceByName($filedName);
        if(empty($imgObj)) {
            return false;
        }

        $ossUpload = new UploadOss();
        $ossUpload->fileobj = $imgObj;

        return  $ossUpload->uploadOss();
    }
}