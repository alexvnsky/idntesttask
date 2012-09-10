Chat
===========

Ajax Yii Chat Repository


Requirements
------------

* Tested with Yii 1.1.12, should work in earlier versions

Resources
---------

* Found a bug or want a feature? [Report it on github](https://github.com/alexvnsky/idntesttask/issues)
* [Code on github](https://github.com/alexvnsky/idntesttask)
* E-Mail the author: Alex <[alexvnsky@gmail.com](mailto:alexvnsky@gmail.com)>
* demo on [http://vsky.com.ua/chat](http://vsky.com.ua/chat)

Quickstart
----------

Add module to your application config (optional config values are commented):

~~~php
<?php

    // ...
    'import'=>array(
		 // ...
        'application.modules.chat.models.*',
         // ...
	),
    // ...
    // ...
    'modules'=>array(
        // ...
        'chat',
        // ...
    ),
    // ...
~~~

Create database tables:
You can use the database migration provieded by this repository (protected/data/schema.mysql.sql) or create a table (example for mysql):

~~~sql
 CREATE TABLE IF NOT EXISTS tbl_chat (
   id int(11) NOT NULL AUTO_INCREMENT,
   create_date timestamp NULL DEFAULT CURRENT_TIMESTAMP,
   user_id int(11) DEFAULT NULL,
   message varchar(100) NOT NULL,
   PRIMARY KEY (id),
   KEY FK_chat_author (user_id)
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
~~~
You might also want to add a foreign key for `userId` column that references you user tables pk:

~~~sql
  ALTER TABLE tbl_chat
    ADD CONSTRAINT tbl_chat_ibfk_1 FOREIGN KEY (user_id) REFERENCES tbl_user (id) ON DELETE CASCADE ON UPDATE RESTRICT;
~~~

Finally add chat to your view template:

~~~php
<?php $this->widget('application.modules.chat.components.ChatWidget'); ?>
~~~