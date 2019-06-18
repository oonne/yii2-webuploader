<?php
/**
 * @link http://www.oonne.com/
 * @copyright Copyright (c) 2019 oonne
 * @license http://www.oonne.com/license/
 */

namespace oonne\webuploader;

use Yii;
use yii\base\Widget;

/**
 * @author JAY <JAY@oonne.me>
 */
class WebUploader extends Widget
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!isset(Yii::$app->i18n->translations['webuploader'])) {
            Yii::$app->i18n->translations['webuploader'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'zh-CN',
                'basePath' => '@vendor/oonne/webuploader/messages'
            ];
        }
    }
}
