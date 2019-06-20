<?php
/**
 * @link http://www.oonne.com/
 * @copyright Copyright (c) 2019 oonne
 */

namespace oonne\webuploader;

use Yii;
use yii\base\Widget;

/**
 * @author JAY <JAY@oonne.com>
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
                'sourceLanguage' => 'en',
                'basePath' => '@vendor/oonne/yii2-webuploader/messages'
            ];
        }
    }
}
