<?php
/**
 * @author Alex <alexvnsky@gmail.com>
 * @link http://www.vsky.com.ua/
 * @date: 09.09.12
 */
?>
<?php foreach($data as $message):?>
<p><span class="author"><?php echo ($message->user) ? $message->user->username : Yii::t('app', 'Guest');?>
    <span>(<?php echo Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm", $message->create_date); ?>)</span>
        </span>
    <br /><?php echo $message->message; ?>
</p>
<?php endforeach;?>
