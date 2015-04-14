# Workerboy

Workerboy是Workerman3在Laravel5下的封装。

装上以后就可以用Laravel的artisan命令来管理workerman了。

## 依赖
 本产品依赖 PHP的"ext-sockets" 扩展。Windows下就别想了。
 为了提升性能，推荐安装"ext-libevent" 扩展。看这里： http://www.workerman.net/install 

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


## Workerman 3.0 

GitHub: [https://github.com/walkor/workerman](https://github.com/walkor/workerman)

Home page:[http://www.workerman.net](http://www.workerman.net)

Documentation:[http://doc3.workerman.net](http://doc3.workerman.net)