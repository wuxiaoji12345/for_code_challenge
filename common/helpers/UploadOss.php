<?php
namespace common\helpers;

use common\components\Helper;
use OSS\Core\OssException;
use OSS\OssClient;
use yii\base\BaseObject;
use yii\base\Model;
use Yii;
use yii\db\Exception;

//use Imagine\Image\ImageInterface;

class UploadOss
{

    public $ossclient;
    public $imgaction=null;
    public $filename;
    public $fileobj;
    public $otheroptions;
    public $bucket;
    public $objname;
    public $deletesource=false;   //是否删除原始记录
    public $returnparams;   //返回值参数

    public $x=0;
    public $y=0;
    public $w;
    public $h;


    static $resize  =   "image/resize";
    static $crop    =   'image/crop';




    function __construct()
    {
        $ossparams =   Yii::$app->params['oss'];
        $id         =   $ossparams['accessKeyId'];
        $key        =   $ossparams['accessKeySecret'];
        $endpoint   =   $ossparams['endpoint'];
        $this->bucket       =   $ossparams['name'];
        $this->objname      =   $ossparams['objname'];
        $this->ossclient    =   new OssClient($id, $key, $endpoint);
    }


    public function uploadOss($file_info = [])
    {

        try{
            if(!$file_info){
                //判断 类型 对象 或者 文件路径
                if($this->fileobj instanceof  BaseObject==1)
                {
                    $ext    =   $this->fileobj->extension;
                    $file   =   $this->fileobj->tempName;
                }else{
                    $ext    =   pathinfo($this->fileobj)['extension'];
                    $file   =   $this->fileobj;
                }

                if(!$this->filename)
                {
                    $filename   =   date("Ymd").DIRECTORY_SEPARATOR.time().'-'.uniqid(). '.'.$ext;
                }else{
                    $filename   =   $this->filename;
                }

                $filename   =   $this->objname.DIRECTORY_SEPARATOR.$filename;
            } else {
                $filename = $file_info['file_name'];
                $file = $file_info['file'];
            }
            $result     =   $this->ossclient->uploadFile($this->bucket,$filename,$file);
            $requesturl =   $result['oss-request-url'];
            //判断是否删除原始记录
            if($this->deletesource)  unlink($this->fileobj);
            //判断图片处理类型
            if($this->imgaction)
            {
                //判断是不是图片 如果不是 返回 原始url
                if(Helper::getImagetype($requesturl))
                {

                    switch($this->imgaction)
                    {
                        case self::$crop:
                            $suffixopstions =   [
                                '?x-oss-process='.$this->imgaction,
                                'x_'.$this->x,
                                'y_'.$this->y,
                                'w_'.$this->w,
                                'h_'.$this->h
                            ];
                            $suffix =  implode(',',$suffixopstions);
                            $resurl    =    $requesturl.$suffix;
                            break;
                        default:
                            $resurl    =    $requesturl;
                            break;
                    }

                }
            }else{
                $resurl =    $requesturl;
            }
            if($this->returnparams)
            {
                return [$this->returnparams=>$resurl];
            }else{
                return $resurl;
            }



        }catch (OssException $e)
        {
            throw new Exception($e->getMessage());
        }
    }





}