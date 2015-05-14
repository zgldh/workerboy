<?php namespace App\WorkermanApps\{WorkermanAppName};
use \Workerman\Worker;

$global_uid        = 0;
$connections_array = array();

// 当客户端连上来时分配uid，并保存连接，并通知所有客户端
function handler_connection($connection)
{
	global $connections_array, $global_uid;
	// 为这个链接分配一个uid
	$connection->uid = ++ $global_uid;
	// 保存连接
	$connections_array[ $connection->uid ] = $connection;
}

// 当客户端发送消息过来时，转发给所有人
function handle_message($connection, $data)
{
	global $connections_array;
	foreach ($connections_array as $conn)
	{
		$conn->send("user[{$connection->uid}] said: $data");
	}
}

// 当客户端断开时，从连接数组中删除
function handle_close($connection)
{
	global $connections_array;
	unset($connections_array[ $connection->uid ]);
}


// 创建一个文本协议的Worker监听2347接口
$text_worker = new Worker("Text://0.0.0.0:2347");

// 只启动1个进程，这样方便客户端之间传输数据
$text_worker->count = 1;

$text_worker->onConnect = 'handler_connection';
$text_worker->onMessage = 'handle_message';
$text_worker->onClose   = 'handle_close';
$text_worker->setStoreConfig($config['store']);