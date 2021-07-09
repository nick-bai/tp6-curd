# tp6-curd
基于thinkphp6的一键curd生成controller、model、validate

### 如何安装
```php
composer require nickbai/tp6-curd
```

### 如何使用
> php think curd -t table名 -c 控制器名 -p 模块名


`-t` 表名，你要基于哪个表生成 curd，比如 `x_node`,则输入 `-t node`   
`-c` 控制器名，自动生成的 `controller` 名称，比如你想要  `user` 控制器，则输入 `-c user`  
`-p` 模块名可选，比如你要生成到 `admin` 模块下，输入 `-p admin`

> 系统会自动根据你是否建立名为  `Base` 的控制器，若没有，则会自动创建一个 `base` controller