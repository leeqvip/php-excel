ThinkPHP Excel
====

一个基于[PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet)的拓展包，简化了在`ThinkPHP`里导出和导入Excel。

## 安装

在`ThinkPHP`项目里，通过`composer`安装这个扩展：

```
composer require techone/think-excel
```

## 快速开始

在`app\index\export`目录下，创建一个导出类：

```php
<?php

namespace app\index\export;

use TechOne\Excel\Concerns\FromCollection;
use app\index\model\User;

class UsersExport implements FromCollection
{
    public function collection()
    {
        return User::all();
    }
}

```

在控制器里，可以这样调用：

```php
<?php

namespace app\index\controller;

use app\index\export\UsersExport;
use think\Controller;
use TechOne\Excel\Facades\Excel;

class Index extends Controller
{
    public function export()
    {
        return Excel::download(new UsersExport(), 'users.xlsx');
    }
}
```

## 协议

ThinkPHP Excel采用 [Apache 2.0 license](LICENSE) 开源协议发布。

## 联系

有问题请提交 Issues：https://github.com/techoner/think-excel/issues