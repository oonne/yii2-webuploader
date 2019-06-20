<?php
/**
 * @link http://www.oonne.com/
 * @copyright Copyright (c) 2019 oonne
 */
namespace oonne\webuploader;

use yii\web\AssetBundle;

/**
 * @author JAY <JAY@oonne.com>
 */
class UploadAsset extends AssetBundle
{
	public $sourcePath = '@vendor/oonne/yii2-webuploader/assets';

	public $css = [
	  	'css/webuploader.css',
	  	'css/upload.css',
	];

	public $js = [
		'js/webuploader.withoutimage.min.js',
		'js/upload.js',
	];

	public $depends = [
		'yii\web\JqueryAsset'
	];
}
