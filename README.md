Webuploader
============
Yii2 extension for large file chunk upload. Base on WebUploader.

基于[Webuploader](http://fex.baidu.com/webuploader/) 的Yii2大文件上传扩展。[Webuploader](http://fex.baidu.com/webuploader/) 是由Baidu WebFE(FEX)团队开发的一个简单的以HTML5为主，FLASH为辅的现代文件上传组件，支持图片上传和文件上传。本扩展仅实现大文件分片上传功能。


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist oonne/yii2-webuploader "*"
```

or add

```
"oonne/yii2-webuploader": "*"
```

to the require section of your `composer.json` file.


Usage
-----

* Add Upload widget in the view, for example:
```php
<?php use oonne\webuploader\Upload; ?>
<?= Upload::widget(['url'=>'/upload/upload']) ?>
```
Enter the service processing path in "url";

* Add a upload controller, for example:
```php
<?php
namespace backend\controllers;

use Yii;
use yii\web\Response;
use yii\web\UploadedFile;
use oonne\webuploader\UploadServer;

class UploadController extends \yii\rest\Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON
        ];
        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'upload' => ['post'],
        ];
    }

    public function actionUpload()
    {
        $fileData = Yii::$app->request->post();
        $file = UploadedFile::getInstanceByName('file');
        $fileRet = UploadServer::uploadFile($file, $fileData, Yii::$app->params['temppath'], Yii::$app->params['filepath']);

        return [
            'Ret' => 0,
            'Filename' => $fileRet['file_name'],
            'Url' => '$downloadUrl',
        ];
    }
}

```