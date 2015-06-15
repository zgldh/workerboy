<?php namespace App\WorkermanApps\{WorkermanAppName};
/**
 *
 * 主逻辑
 * 主要是处理 onMessage onClose 三个方法
 * @author walkor <walkor@workerman.net>
 *
 */
use \GatewayWorker\Lib\Gateway;

class Event
{
    /**
     * 有消息时
     * @param int $clientId
     * @param string $message
     */
    public function onMessage($clientId, $message)
    {
        // 获取客户端请求
        $messageData = json_decode($message, true);
        if (!$messageData) {
            return;
        }

        $workerBoy = \zgldh\workerboy\WorkerBoy::getInstance();

        switch ($messageData['type']) {
            case 'login':
//                Workerboy v0.15 的新功能，可以根据前端传来的凭证找到对应的用户ID
//                详见： https://github.com/zgldh/workerboy
//                $credential = @$message_data['workerboy_credential'];
//                $userId = $workerBoy->validateCredential($credential);
                Gateway::sendToCurrentClient('{"type":"welcome","id":' . $clientId . '}');
                break;
            // 更新用户
            case 'update':
                // 转播给所有用户
                Gateway::sendToAll(
                    json_encode(
                        array(
                            'type'       => 'update',
                            'id'         => $clientId,
                            'angle'      => $messageData["angle"] + 0,
                            'momentum'   => $messageData["momentum"] + 0,
                            'x'          => $messageData["x"] + 0,
                            'y'          => $messageData["y"] + 0,
                            'life'       => 1,
                            'name'       => isset($messageData['name']) ? $messageData['name'] : 'Guest.' . $clientId,
                            'authorized' => false,
                        )
                    )
                );

                return;
            // 聊天
            case 'message':
                // 向大家说
                $newMessage = array(
                    'type'    => 'message',
                    'id'      => $clientId,
                    'message' => $messageData['message'],
                );

                return Gateway::sendToAll(json_encode($newMessage));
        }
    }

    /**
     * 当用户断开连接时
     * @param integer $clientId 用户id
     */
    public function onClose($clientId)
    {
        // 广播 xxx 退出了
        GateWay::sendToAll(json_encode(array('type' => 'closed', 'id' => $clientId)));
    }
}