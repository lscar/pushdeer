
## 项目使用

1. 生成密钥

    ```bash
    php artisan jwt:secret
    php artisan key:generate
    ```

2. 帮助文件

    ```bash
    php artisan ide-helper:generate
    php artisan ide-helper:models
    php artisan ide-helper:meta
    ```

3. 迁移文件

    ```bash
    php artisan migrate:generate --tables="table1,table2,table3,table4,table5"
    ```

4. zsh的简化指令

    ```bash
    function pushdeer() {
        case $1 {
            (up)
            cd /Users/scar/Code/pushdeer && docker-compose -f ./docker-compose-develop.yml up -d
            ;;
            (down)
            cd /Users/scar/Code/pushdeer && docker-compose -f ./docker-compose-develop.yml down
            ;;
            (stop)
            cd /Users/scar/Code/pushdeer && docker-compose -f ./docker-compose-develop.yml stop
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

5. 信息安全

   ```bash
   brew install git-filter-repo
   git filter-repo --invert-paths --path PATH-TO-YOUR-FILE-WITH-SENSITIVE-DATA
   ```
   
   信息参考：[《Removing sensitive data from a repository》](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/removing-sensitive-data-from-a-repository)