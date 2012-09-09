<?php
/**
 * ChatWidget class file
 *
 * @author Alex <alexvnsky@gmail.com>
 * @link http://www.vsky.com.ua/
 * @date: 09.09.12
 */
class ChatWidget extends CWidget{

    /**
     * @var string the base script URL for all list view resources (e.g. javascript, CSS file).
     * Defaults to null, meaning using the integrated list view resources (which are published as assets).
     */
    public $baseScriptUrl;

    /**
     * @var string the URL of the CSS file used by this list view. Defaults to null, meaning using the integrated
     * CSS file. If this is set false, you are responsible to explicitly include the necessary CSS file in your page.
     */
    public $cssFile;

    /**
     * Initializes the chat.
     */
    public function init()
    {
        parent::init();

        if($this->baseScriptUrl===null)
            $this->baseScriptUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.chat.components.assets'), false, -1, 1).'/chatwidget';

        if($this->cssFile!==false)
        {
            if($this->cssFile===null)
                $this->cssFile=$this->baseScriptUrl.'/chat.css';
            Yii::app()->getClientScript()->registerCssFile($this->cssFile);
        }
    }

    /**
     * Registers necessary client scripts.
     */
    public function registerClientScript()
    {
        //$id=$this->getId();
        $cs=Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($this->baseScriptUrl.'/chat.js',CClientScript::POS_END);
    }

    public function run(){
        $this->registerClientScript();
        $model = new Chat();
        $params = array(
            'model' => $model
        );
        $this->render('chat', $params);
    }
}