<?php
/**
 * @link http://www.oonne.com/
 * @copyright Copyright (c) 2019 oonne
 * @license http://www.oonne.com
 */
namespace oonne\webuploader;

use Yii;

/**
 * 图片上传按钮
 * 在你想要出现“选择文件”的地方，放下如下代码：
 * <?= Upload::widget() ?>
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
		$this->registerClientScript($this->url);
		return $this->render('upload');
	}

	/**
	 * Registers Webuploader assets
	 */
	protected function registerClientScript($url)
	{
		$view = $this->getView();
		UploadAsset::register($view);
		$js = "
            var uploadUrl = '".$url."';
        ";
		$view->registerJs($js, $view::POS_HEAD);
	}
}
