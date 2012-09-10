<?php
/**
 * @author Alex <alexvnsky@gmail.com>
 * @link http://www.vsky.com.ua/
 * @date: 09.09.12
 */
?>
<div id="chat-block">

    <div id="chat-wrapper">
        <div id="chat-head">
            <a id="chat-toogle-btn" href="#"></a>
        </div>

        <div id="chat-inner">
            <div id="chat-content">
                <div id="chat-data"></div>
            </div>

            <div id="chat-bottom">
                <?php $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'chat-form',
                    'action' => Yii::app()->createUrl('/chat/default/validate'),
                    'enableAjaxValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'validateOnChange' => false,
                        'validateOnType' => false,
                        'afterValidate' => 'js:function(form, data, hasError){
                            if(!hasError){
                                chat.addMessage();
                            }
                            return false;
                        }'
                    )
                ));
                ?>

                <?php echo $form->textField($model, 'message', array('class' => 'message-field')); ?>
                <?php echo CHtml::submitButton(Yii::t('app', 'Send'), array('class' => 'chat-send-btn')); ?>
                <div id="chat-loader" style="display: none"></div>
                <?php echo $form->error($model, 'message'); ?>
                <?php $this->endWidget(); ?>
            </div>
        </div>

    </div>
</div>
