<?php
/**
 * @link https://entero.co.in/
 * @copyright Copyright (c) 2012 Entero Software Solutions Pvt.Ltd
 * @license https://entero.co.in/license/
 */

namespace codexten\yii\components;

use enyii\base\Component;
use enyii\email\common\models\EmailTemplate;
use Yii;
use yii\base\Exception;
use yii\mail\MessageInterface;
use function str_replace;

/**
 * Class Mailer
 *
 * @package enyii\email\common\components
 */
class Mailer extends Component
{
    /**
     * @var string
     */
    public $templatePath;
    /**
     * @var string
     */
    public $code;

    public $from = null;

    /**
     * @var mixed
     */
    public $to;
    /**
     * @var string
     */
    public $subject;
    /**
     * @var array
     */
    public $params = [];
    /**
     * @var array
     */
    public $attachments = [];
    /**
     * @var EmailTemplate
     */
    private $_template;

    public function init()
    {
        parent::init();
        if (!Yii::$app->params['mail.template.path']) {
            throw new Exception('Template path Configuration not found');
        }
        $this->templatePath = Yii::getAlias(Yii::$app->params['mail.template.path']);
    }

//    private function getHtmlBody()
//    {
//        if (($this->_template = EmailTemplate::findOne(['code' => $this->code])) == null) {
//            throw new Exception('Template not Found');
//        }
//        $this->subject = $this->subject ?: $this->_template->subject;
//
//        $find = [];
//        $replace = [];
//        foreach ($this->params as $key => $value) {
//            $find[] = '{' . $key . '}';
//            $replace[] = $value;
//        }
//
//        $content = str_replace($find, $replace, $this->_template->htmlWithCss);
//
//        return str_replace('{content}', $content, $this->_template->layout->html);
//    }

    public function getHtmlBody()
    {
        $file = file_get_contents($this->templatePath . DIRECTORY_SEPARATOR . $this->code . '.html');
        $this->subject = ucwords(str_replace('-', ' ', $this->code));
        $find = [];
        $replace = [];
        foreach ($this->params as $key => $value) {
            $find[] = '{' . $key . '}';
            $replace[] = $value;
        }
        $content = str_replace($find, $replace, $file);

        return str_replace('{content}', $content, '{content}');
    }

    /**
     * @return bool
     */
    public function send()
    {
        $message = $this->getMessage();

        return $message->send();
    }

    /**
     * @return mixed
     */
    public function queue()
    {
        $message = $this->getMessage();

        return $message->queue();
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        $message = Yii::$app->mailer->compose();
        $message->setHtmlBody($this->getHtmlBody());
        $message->setFrom($this->from ?: Yii::$app->params['mail.sender']);
        $message->setTo($this->to);
        $message->setSubject($this->subject);

        if ($this->attachments) {
            $message->attach($this->attachments);
        }

        return $message;
    }
}
