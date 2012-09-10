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
     * @var bool
     * If this is set false, JScrollPane plugin will not be included
     */
    public $registerJScrollPane = true;

    /**
     * @var int
     * refresh messages time in milisec.
     */
    public $updateTime = 5000;

    /**
     * @var array an array with options for extension.
     */
    public $options = array();

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

        if($this->registerJScrollPane){
            //Register jScrollPane plugin default css
            Yii::app()->getClientScript()->registerCssFile($this->baseScriptUrl.'/jScrollPane/jScrollPane.css');
        }

        $this->options = array(
            'getMessagesUrl' => Yii::app()->createUrl('/chat/default/getMessages'),
            'addMessageUrl' =>  Yii::app()->createUrl('/chat/default/addMessage'),
            'registerJScrollPane' => $this->registerJScrollPane
        );

    }

    /**
     * Registers necessary client scripts.
     */
    public function registerClientScript()
    {
        $id=$this->getId();
        $cs=Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($this->baseScriptUrl.'/chat.js',CClientScript::POS_END);

        if($this->registerJScrollPane){
            //Register jScrollPane plugin js
            $cs->registerScriptFile($this->baseScriptUrl.'/jScrollPane/jScrollPane.min.js');
            $cs->registerScriptFile($this->baseScriptUrl.'/jScrollPane/jquery.mousewheel.js');
        }

        //csrfToken Validation
        if( Yii::app()->request->enableCsrfValidation )
        {
            // Include jQuery cookie plugin
            $cs->registerCoreScript( 'cookie' );

            // Add CSRF cookie
            Yii::app()->request->getCsrfToken();

            // Set CSRF token name for extension
            $this->options['csrfTokenName'] = Yii::app()->request->csrfTokenName;
        }

        $cs->registerScript( 'chatwidget', "
            chat.init();
            window.getChats = function(callback){
                if($('#chat-toogle-btn').hasClass('active')){
                    chat.getMessages();
                }
                //Set a timeout for the next request
                var nextRequest = " . $this->updateTime . ";
                setTimeout(callback,nextRequest);
            };

            //Self-fulfilling timeout function
            (function getChatsTimeoutFunction(){
                window.getChats(getChatsTimeoutFunction);
            })();

            $('#chat-toogle-btn').click(function() {
                $(this).toggleClass('active');
                if($('#chat-toogle-btn').hasClass('active')){
                    $('#chat-wrapper').animate({right:'400px'},500);
                    chat.getMessages();
                } else
                    $('#chat-wrapper').animate({right:0},500);
            return false;
            });
            ", CClientScript::POS_END );

        // Register additional options for filter
        if( $this->options !== array() )
        {
            // Initialize script variable
            $js = '';

            // Go through all options
            foreach( $this->options as $option => $value )
            {
                // Encode option value
                $value = CJavaScript::encode( $value );

                // Add option to script variable
                $js .= "chat.{$option} = {$value};";
            }


            // Register options for an extension
            $cs->registerScript( $id, $js, CClientScript::POS_END );
        }

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