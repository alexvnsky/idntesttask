<?php
/**
 * DefaultController class file
 *
 * @author Alex <alexvnsky@gmail.com>
 * @link http://www.vsky.com.ua/
 * @date: 09.09.12
 */
class DefaultController extends Controller
{
    /*Ajax Controller*/
    public function filters() {
        return array(
            'ajaxOnly',
        );
    }

    /*Action Validate Chat Model*/
    public function actionValidate()
    {
        $model = new Chat();
        //validate chat model
        echo CActiveForm::validate($model);
        Yii::app()->end();
    }

    /*Action Add new message to database*/
    public function actionAddMessage(){
        $model = new Chat();
        if(!Yii::app()->user->isGuest)
            $model->user_id = Yii::app()->user->id;
        $dataArr = array();
        parse_str($_POST['form'], $dataArr);

        //HTML tags should be forbidden or correctly escaped.
        $message = stripslashes($dataArr['Chat']['message']);
        $message = htmlspecialchars($message, ENT_NOQUOTES, 'UTF-8');
        $model->message = $message;

        if($model->save()){
            echo CJSON::encode(array(
                'status' => 'success',
            ));
        } else
            echo CJSON::encode(array(
                'status' => 'error',
                'content' => Yii::t('app', 'An error occurred')
            ));
    }
    /*Action for get the last 15 chat messages*/
    public function actionGetMessages(){
        $data = $this->getLastChatMessages();
        if($data == '')
            $data = Yii::t('app', 'No messages in chat');
        echo CJSON::encode(array(
            'status' => 'success',
            'content' => $data
        ));
    }
    /*Action for get html of the last 15 chat messages */
    public function getLastChatMessages(){
        $criteria = new CDbCriteria();
        $criteria->with = array('user');
        $criteria->limit = 15;
        $criteria->order = 't.id DESC';
        $model = Chat::model()->findAll($criteria);
        if($model !== null){
            $model = array_reverse($model);
            $data = $this->renderPartial('application.modules.chat.components.views._chat_message_template',array('data' => $model), true, true);
        } else
            $data = '';
        return $data;
    }
}