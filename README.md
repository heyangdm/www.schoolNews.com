## www.schoolNews.com
`注意请将thinkphp5.0中的thinkphp文件拷贝到项目根目录，方可正常运行`
### 1.项目描述
- 这是一个实现学校图书管理的DEMO系统，后端技术栈apache2.4.39 + mysql5.7.26+php7,前端技术栈 layui + vue2.0 + bootstrap3 + jquery
### 2.项目基本结构

#### 1.网站基本构成

| 身份                                   | 权限                                                         |
| -------------------------------------- | ------------------------------------------------------------ |
| 超级管理员 （账号：admin ，密码123456) | 后台网站：1.公告2.用户3.个人中心4.消息推送5.反馈消息处理     |
| 管理员                                 | 后台网站：1.公告2.学校协议3.订单管理4.书籍管理5.用户管理6.个人中心，7.学生反馈信息 |
| 教师                                   | 后台网站：1.公告2.学校协议3.订单管理4.书籍管理5.用户管理6.个人中心，7.学生反馈信息 |
| 学生                                   | 前台网站：1.登录注册2.书籍借阅3.反馈信息4.查看公告           |

#### 2.数据库设计

- schoolnews库名

| 表名               | 字段                                                         |      |
| ------------------ | :----------------------------------------------------------- | ---- |
| tb_user            | uid，username，password1，password2，user_date，identity...  |      |
| tb_school          | id，conect_id，school_name，school_tel，school_phone...      |      |
| tb_book            | bookid，book_name，book_banner，book_sale，sid...            |      |
| tb_agreement       | id，title，content，conect_id，author，create_time...        |      |
| tb_feedback        | id，conect_phone，feedback_msg，uid，feedback_date...        |      |
| tb_msg             | id，msg_title，msg_content，connect_id，author...            |      |
| tb_news            | id，author，notice_title，content，uid，banner...            |      |
| tb_order           | order_id，book_id，order_status，create_time，order_strat_time... |      |
| tb_salse           | id，salse_name，sid，conect_admin，is_del                    |      |
| tb_school_feedback | id，uid，school_msg_content，feek_name，feek_email...        |      |

### 3.项目部分展示

