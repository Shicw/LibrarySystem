学校图书馆管理系统
===============
 + 基于ThinkPHP5和LayUI框架开发
 + Admin可对用户信息,图书类型,图书信息,图书借阅信息和专业班级信息管理维护;可办理借书和还书业务
 + 普通用户可手机登录用户Web页,可查看自己借阅中的图书,历史借阅的记录,办理续借以及修改密码操作
 + admin默认登录用户名为: admin01;密码为: 123456
 + 普通用户需由admin建立后才可登录

> ThinkPHP5的运行环境要求PHP5.4以上。

## 目录结构

~~~
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─common             公共模块目录（可以更改）
│  ├─admin              管理员模块目录
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  └─view            视图目录
│  ├─user               普通用户模块目录
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  └─view            视图目录
│  │
│  ├─command.php        命令行工具配置文件
│  ├─common.php         公共函数文件
│  ├─config.php         公共配置文件
│  ├─route.php          路由配置文件
│  ├─tags.php           应用行为扩展定义文件
│  └─database.php       数据库配置文件
│
├─public                WEB目录（对外访问目录）
│
├─thinkphp              框架系统目录
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~