# 湖南安全职业技术学院2020届录取通知书查询网页
> 利用爬虫技术将官网的录取信息保存至数据库，重写一个自适应 h5 网页提供一键式查询录取通知书以及物流！

> 开发语言：php，html5，css，shell

> 开发环境：php7，mysql

### 数据部署
> 1，复制 default.env 为 .env 文件，配置数据库相关信息

> 2，执行 ` cd data && php getdata.php ` 爬取数据！

> 3，执行 setDataBash.php 创建数据表，整理数据

### 开发
> 执行 ` php -S localhost:8668 -t ./ `

> 访问 <http://localhost:8668>
