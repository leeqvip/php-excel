PHP-Excel
====

一个基于[PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet)的拓展包，简化了Excel的导出和导入。

## 安装

通过`composer`安装这个扩展：

```
composer require techone/php-excel
```

## 快速开始

创建一个导出类：

```php

use TechOne\Excel\Concerns\FromArray;

class UsersExport implements FromArray
{
    public function array()
    {
        return [
        	[1, 2, 3], 
        	[1, 2, 3]
        ];
    }
}

```

可以这样调用：

```php
Excel::download(new UsersExport(), 'data.xlsx');
```

## 协议

PHP-Excel采用 [Apache 2.0 license](LICENSE) 开源协议发布。

## 联系

有问题请提交 Issues：https://github.com/techoner/php-excel/issues