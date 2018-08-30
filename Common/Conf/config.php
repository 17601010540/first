<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'   => 'mysql',         // 数据库类型我们是mysql，就对于的是mysql

	'DB_HOST'   => '180.76.168.254',   // 服务器地址，就是我们配置好的php服务器地址，也可以使用localhost，

	'DB_NAME'   => 'enetpulse',  // 数据库名：mysq创建的要连接我们项目的数据库名称

	'DB_USER'   => 'admin',           // 用户名：mysql数据库的名称

	'DB_PWD'    => 'Enetpulse123..',                 //mysql数据库的 密码

	'DB_PORT'   => 3306,            // 端口服务端口一般选3306

	'DB_PREFIX' => '',            //  数据库表前缀

	'DB_CHARSET'=> 'utf8',         // 字符集

	'DB_DEBUG'  =>  TRUE,         // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
);