
## 项目介绍

本项目是基于[easychen/pushdeer](https://github.com/easychen/pushdeer)项目的PHP的API，使用`swoole`重构，并移除了使用`go`编写的`push`服务。

## 待办项
- [x] 基于`phpswoole/swoole`重构容器
  - [x] 开发环境、生产环境分离
  - [x] 简化构建文件基于`mlocati/docker-php-extension-installer`脚本
- [x] 基于`hhxsv5/laravel-s`重构项目
  - [x] 引入`jwt`基于`tymon/jwt-auth`
  - [x] 移除`go`编写的`push`服务
  - [x] 移除`session`
  - [x] 升级`laravel`框架
  - [x] `apple`推送，`apns`，异步
  - [ ] `android`推送，`fcm`，异步
  - [x] 定时任务 
    - [x] 拉取最新证书，转换格式
    - [x] 清理历史消息
  - [ ] ~~优化推送，设定缓冲阈值，批量发送~~
- [x] 辅助工具
  - [x] 调试工具，`laravel/telescope` 
  - [x] ~~日志浏览，`rap2hpoutre/laravel-log-viewer`~~
  - [x] 日志浏览，`laravel-admin-ext/log-viewer`
- [x] 基于`z-song/laravel-admin`后台
  - [x] 日志浏览，基于`elasticsearch`
  - [x] 用户、设备、消息、秘钥管理
  - [ ] ~~程序参数管理~~
  - [ ] 数据可视化

## 项目使用

### 1. 生产环境

#### 1.1. 默认方式

使用方式，参考下方

```bash
# 下载项目
wget -qO- "https://github.com/lscar/pushdeer/archive/refs/heads/main.zip" | tar -xzf - && mv pushdeer-main pushdeer

# 修改权限
chown www-data:www-data -R pushdeer

# 容器构建，可以省略
# docker-compose -f docker-compose-product.yml build

# 容器启动
docker-compose -f docker-compose-product.yml up -d
```

可以不需要`nginx`可以运行，参考`docker-compose-develop.ym`修改`docker-compose-product.yml`的`LARAVELS_HANDLE_STATIC`环境变量为`true`即可。

不推荐单独使用，建议搭配`nginx`等web服务器联合使用。`nginx`的配置文件参考`docker/nginx.conf`，并启用ssl。

根据服务器性能以及用户的访问量，修改`docker-compose-product.yml`的`LARAVELS_WORKER_NUM`与`LARAVELS_TASK_WORKER_NUM`参数
* `LARAVELS_WORKER_NUM` 处理api的进程数，`cpu`核心的整数倍
* `LARAVELS_TASK_WORKER_NUM` 处理异步任务进程数，`cpu`核心的整数倍（**注**：默认使用异步队列处理消息，忽略该参数）

#### 1.2. 进阶方式

1. laravel-admin
   * 数据初始化
     ```bash
     php artisan db:seed --class=AdminTablesSeeder
     ```
   * 页面地址`http(s)://{host}/admin`，默认用户名`admin`，默认密码`admin`，建议修改默认密码。

2. ELK日志（不建议使用）
   * 开启`docker-composer`中的`elasticsearch`,`kibana`
   * 修改`env`中的`LOG_CHANNEL`的值为`elasticsearch`

### 2. 开发环境

```bash
# 克隆项目
git clone git@github.com:lscar/pushdeer.git

# 容器构建，可以省略
# docker-compose -f docker-compose-product.yml build

# 容器启动
docker-compose -f docker-compose-develop.yml up -d
```

#### 2.1. 辅助工具

逆向迁移

```bash
php artisan migrate:generate --tables="table1,table2,table3,table4,table5"
```

帮助文件

```bash
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:meta
```

更新本地化

```bash
php artisan lang:update
```

admin指令

```bash
php artisan admin:make UserController --model=App\\User
```

#### 2.2. 简化指令

```bash
function pushdeer() {
  case $1 {
      (up)
      docker-compose -f ${project_path}/docker-compose-develop.yml up -d
      ;;
      (down)
      docker-compose -f ${project_path}/docker-compose-develop.yml down
      ;;
      (stop)
      docker-compose -f ${project_path}/docker-compose-develop.yml stop
      ;;
      (dump)
      docker exec -it pushdeer-app-1 bash -c 'php /app/artisan dump-server'
      ;;
      (app)
      docker exec -it pushdeer-app-1 bash
      ;;
      (*)
      echo 'not support now'
      ;;
  }
}
```

语法参考：[《ZshGuide》](https://github.com/goreliu/zshguide)

#### 2.3. 信息安全

```bash
brew install git-filter-repo

git filter-repo --invert-paths --path PATH-TO-YOUR-FILE-WITH-SENSITIVE-DATA
```
   
信息参考：[《Removing sensitive data from a repository》](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/removing-sensitive-data-from-a-repository)