# PHP 设计模式系列 —— 多例模式（Multiton）

 Posted on [2015年12月12日2015年12月12日][0] by [学院君][1]

### **1、模式定义**

[多例模式][2]和[单例模式][3]类似，但可以返回多个实例。比如我们有多个数据库连接，MySQL、SQLite、Postgres，又或者我们有多个日志记录器，分别用于记录调试信息和错误信息，这些都可以使用多例模式实现。

### **2、UML类图**

![多例模式][4]

### **3、示例代码**

#### **[Multiton][5].php**

```php
<?php

namespace DesignPatterns\Creational\Multiton;

/**
 * Multiton类
 */
class Multiton
{
    /**
     *
     * 第一个实例
     */
    const INSTANCE_1 = '1';

    /**
     *
     * 第二个实例
     */
    const INSTANCE_2 = '2';

    /**
     * 实例数组
     *
     * @var array
     */
    private static $instances = array();

    /**
     * 构造函数是私有的，不能从外部进行实例化
     *
     */
    private function __construct()
    {
    }

    /**
     * 通过指定名称返回实例（使用到该实例的时候才会实例化）
     *
     * @param string $instanceName
     *
     * @return Multiton
     */
    public static function getInstance($instanceName)
    {
        if (!array_key_exists($instanceName, self::$instances)) {
            self::$instances[$instanceName] = new self();
        }

        return self::$instances[$instanceName];
    }

    /**
     * 防止实例从外部被克隆
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * 防止实例从外部反序列化
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}
```


[0]: http://laravelacademy.org/post/2519.html
[1]: http://laravelacademy.org/post/author/nonfu
[2]: http://laravelacademy.org/tags/%e5%a4%9a%e4%be%8b%e6%a8%a1%e5%bc%8f
[3]: http://laravelacademy.org/tags/%e5%8d%95%e4%be%8b%e6%a8%a1%e5%bc%8f
[4]: ../img/927f0bf6-9ed3-4367-b5ee-f386ffd50756.png
[5]: http://laravelacademy.org/tags/multiton