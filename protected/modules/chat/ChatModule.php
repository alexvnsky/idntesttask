<?php
/**
 * ChatModule class file
 *
 * @author Alex <alexvnsky@gmail.com>
 * @link http://www.vsky.com.ua/
 * @date: 09.09.12
 */

class ChatModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'chat.models.*',
			'chat.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
