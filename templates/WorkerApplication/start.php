<?php namespace App\WorkermanApps\{WorkermanAppName};
use \Workerman\Worker;

$globalUid        = 0;
$connectionsArray = array();

// 当客户端连上来时分配uid，并保存连接，并通知所有客户端
function handler_connection($connection)
{
	global $connectionsArray, $globalUid;
	// 为这个链接分配一个uid
	$connection->uid = ++ $globalUid;
	// 保存连接
	$connectionsArray[ $connection->uid ] = $connection;
}

// 当客户端发送消息过来时，转发给所有人
function handle_message($connection, $data)
{
	global $connectionsArray;
	foreach ($connectionsArray as $conn)
	{
		$conn->send("user[{$connection->uid}] said: $data");
	}
}

// 当客户端断开时，从连接数组中删除
function handle_close($connection)
{
	global $connectionsArray;
	unset($connectionsArray[ $connection->uid ]);
}


// 创建一个文本协议的Worker监听2347接口
$textWorker = new Worker("Text://0.0.0.0:2347");

// 只启动1个进程，这样方便客户端之间传输数据
$textWorker->count = 1;

$textWorker->onConnect = 'handler_connection';
$textWorker->onMessage = 'handle_message';
$textWorker->onClose   = 'handle_close';