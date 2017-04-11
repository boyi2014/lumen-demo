<?php 

// 	$redis = new Redis();
// 	$redis->connect('127.0.0.1',6401);
// 	// $redis->auth('maimeng_cache_redis');
	
// 	$redis->set('test','hello redis');
// 	echo $redis->get('test');


date_default_timezone_set('PRC');
	echo '过期：' . date('Y-m-d H:i:s', 1491555515);
	echo '<br/>';
	echo '刷新过期：' . date('Y-m-d H:i:s', 1492761515);
	echo '<br/>';
	echo 'notBefore：' . date('Y-m-d H:i:s', 1491551855);
	
	echo '<br/>';
	echo 'issuedAt：' . date('Y-m-d H:i:s', 1491551915);
	
	echo date('Y-m-d H:i:s', time());
	echo '<br/>';
	echo phpinfo();
	
?>