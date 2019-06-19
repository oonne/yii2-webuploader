<?php
/**
 * @link http://www.oonne.com/
 * @copyright Copyright (c) 2019 oonne
 * @license http://www.oonne.com
 */
namespace oonne\webuploader;

use Yii;

/**
 *
 * @author JAY <JAY@oonne.me>
 */
class Upload extends WebUploader
{
    public $url;
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		// 生成随机id，允许在同个页面中使用多个示例
		$hash = rand(10000, 99999);

		$this->registerClientScript($this->url, $hash);
		return $this->render('upload', ['hash'=>$hash]);
	}

	/**
	 * Registers Webuploader assets
	 */
	protected function registerClientScript($url, $hash)
	{
		$view = $this->getView();
		UploadAsset::register($view);
		$js = "initUploader('".$url."', ".$hash.")";
		$view->registerJs($js, $view::POS_READY);
	}
}
