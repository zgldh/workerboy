# Workerboy

Workerboy是Workerman3在Laravel5下的封装。

装上以后就可以用Laravel的artisan命令来管理workerman了。

## 依赖
 PHP 5.4

 本产品依赖 PHP的"ext-sockets" 扩展。Windows下就别想了。

 为了提升性能，推荐安装"ext-libevent" 扩展。看这里： http://www.workerman.net/install 

## 更新
 - v0.15 增加了“凭证”机制，用于同步Web服务器用户ID和Socket服务器ClientId。具体请查看下面凭证机制说明。
 - v0.14 修复了多workerman应用间互相冲突的bug。 请注意新的config格式： ```/vendor/zgldh/workerboy/config/workerboy.php```
请注意每个应用的```start.php```里面需要给每个worker配置config。具体请看应用模板文件```/vendor/zgldh/workerboy/templates/\GatewayBusinessWorkerApplication/start.php```

## 用法

 1. `composer require zgldh/workerboy`。
 2. 在`config\app.php`的 `providers`数组添加 `zgldh\workerboy\WorkerBoyServiceProvider`。
 3. `php artisan vendor:publish` 将workerboy.php配置项输出到config目录下。
 4. `php artisan workerboy:create MyApplication` 新建一个Workerman3应用。
 5. 编辑`Event.php`文件。写业务逻辑。
 6. 在`config/workerboy.php`的`applications`数组里增加刚刚添加的Workerman3应用。
 7. `php artisan workerboy:start --deamon` 启动所有Workerman3应用。


## 命令

 - ### create
  `workerboy:create ApplicationName` 创建一个Workerman3应用，默认是Gateway/BusinessWorker模式。
  `workerboy:create ApplicationName --worker-only` 使用纯Worker模式创建一个Workerman3应用。

 - ### start
  `workerboy:start` 启动Workerman3所有的应用。默认是调试模式。
  `workerboy:start --deamon` 以daemon方式启动。

 - ### stop
  `workerboy:stop` 停止系统。

 - ### restart
  `workerboy:stop` 重启系统。

 - ### reload
  `workerboy:reload` 平滑重启。

 - ### status
  `workerboy:status` 查看状态。

具体请看： http://doc3.workerman.net/install/start-and-stop.html


## 凭证机制

  开发者常遇痛点：WebSocket连上了，如何告诉Socket服务器当前连上的是Web服务器里的哪个用户呢？ 直接在连接的时候写上UserId？那太不保险了。
  Workerboy为了解决该问题而产生了凭证机制，用于同步Web服务器用户ID和Socket服务器ClientId。
  
  一共3步：
  
  1. 在做WebSocket的页面输出一个凭证：
    ```
        <script>
            var WORKERBOY_CREDENTIAL = <?php echo json_encode(\zgldh\workerboy\WorkerBoy::getInstance()->outputCredential()); ?>;
        </script>
    ```
   2. 在WebSocket连接时将凭证传过去：
    ```
        var ws = new WebSocket('ws://' + window.location.host + ':8685');
        ws.onopen = function () {
            ws.send(JSON.stringify({
                type                : "login",
                workerboy_credential: WORKERBOY_CREDENTIAL
            }));
        };
    ```
   3. 在Event.php里面验证凭证：
    ```
        $workerBoy = WorkerBoy::getInstance();
        $credential = @$message_data['workerboy_credential'];
        $userId = $workerBoy->validateCredential($credential);
    ```    
    这样你就拿到了当前Socket连接用户在Web服务器里的UserId了。
   
   凭证机制默认使用Laravel的Session进行储存，你可以修改`config/workerboy.php`的`credential_processor`项来自定义凭证机制。

## Workerman 3.0 

GitHub: [https://github.com/walkor/workerman](https://github.com/walkor/workerman)

Home page:[http://www.workerman.net](http://www.workerman.net)

Documentation:[http://doc3.workerman.net](http://doc3.workerman.net)