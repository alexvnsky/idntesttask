<?php
/**
 * @author Alex <alexvnsky@gmail.com>
 * @link http://www.vsky.com.ua/
 * @date: 09.09.12
 */
?>
<script>
    $(document).ready(function() {

        var element = $('#chat-content').jScrollPane();
        var apiJsp = element.data('jsp');

        window.getChats = function(callback){
            if($('#chat-toogle-btn').hasClass('active')){
                window.getMessages();
            }
            //Set a timeout for the next request
            var nextRequest = 5000;
            setTimeout(callback,nextRequest);
        };

        //Self-fulfilling timeout function
        (function getChatsTimeoutFunction(){
            window.getChats(getChatsTimeoutFunction);
        })();

        $('#chat-toogle-btn').click(function() {
            $(this).toggleClass("active");
            if($('#chat-toogle-btn').hasClass('active')){
                $("#chat-wrapper").animate({right:'400px'},500);
                window.getMessages();
            } else
                $("#chat-wrapper").animate({right:0},500);
            return false;
        });

        window.getMessages = function(){
            $.ajax({
                type: 'POST',
                url: "<?php echo Yii::app()->createUrl('/chat/default/GetMessages')?>",
                data: {<?php echo Yii::app()->request->csrfTokenName ?>: "<?php echo Yii::app()->request->csrfToken ?>"},
                dataType : 'json',
                beforeSend: function(){
                    //$('.chat-data').hide();
                    $('#chat-loader').show();
                },
                complete: function(){
                    $('#chat-loader').hide();
                    //$('.chat-data').show();
                },
                success:function(data){
                    if (data.status == 'success'){
                        apiJsp.getContentPane().html(data.content);
                        apiJsp.reinitialise();
                        apiJsp.scrollToBottom();
                    } else
                        $('#chat-data').html('<?php echo Yii::t('app', 'Error loading chat');?>');
                }
            });
        };

    });
</script>
<div id="chat-block">

    <div id="chat-wrapper">
        <div id="chat-head">
            <a id="chat-toogle-btn" href="#"></a>
        </div>

        <div id="chat-inner">
            <div id="chat-content">
                <div id="chat-loader" style="display: none"></div>
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
                                $.ajax({
                                    type: "POST",
                                    url: "' . Yii::app()->createUrl('/chat/default/addMessage') . '",
                                    data: {' . Yii::app()->request->csrfTokenName . ': "' . Yii::app()->request->csrfToken . '", form:$("#chat-form").serialize()},
                                    dataType : "json",
                                    success:function(data){
                                        if (data.status == "success"){
                                           window.getMessages();
                                        } else
                                            alert(data.content)
                                    }
                                });
                            }
                            return false;
                        }'
                    )
                ));
                ?>

                <?php echo $form->textField($model, 'message', array('class' => 'message-field')); ?>
                <?php echo CHtml::submitButton(Yii::t('app', 'Send'), array('class' => 'chat-send-btn')); ?>
                <?php echo $form->error($model, 'message'); ?>
                <?php $this->endWidget(); ?>
            </div>
        </div>

    </div>
</div>
