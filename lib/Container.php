<?php
namespace Lib;

use ReflectionClass;

class Container
{
    /**
    * @var Singleton
    */
    private static $instance;

    /**
     * 映射容器
     *
     * @var array
     */
    protected $map = [];

    /**
    * 不允许从外部调用以防止创建多个实例
    * 要使用单例，必须通过 Singleton::getInstance() 方法获取实例
    */
    private function __construct()
    {
    }

    /**
    * 防止实例被克隆（这会创建实例的副本）
    */
    private function __clone()
    {
    }

    /**
    * 防止反序列化（这将创建它的副本）
    */
    private function __wakeup()
    {
    }


    public function bind($className, $classObj = null) : bool
    {
        if(is_null($classObj)) {
            return false;
        } elseif(is_array($className)) {
            foreach($className as $name => $obj) {
                $this->bind($name, $obj);
            }
            return true;
        }

        if ($classObj instanceof \closure) {
            $this->data[$className] = call_user_func($classObj);
            return true;
        } elseif ($classObj instanceof String) {
            $obj = $this->ref($className);
            if($obj) {
                $this->data[$className] = $obj;
                return true;
            }
            return false;
        } else {
            $this->data[$className] = $classObj;
            return true;
        }

        return false;

    }

    /**
     * 获取容器中的对象
     *
     * @param string $className
     * @return mixed
     */
    public function make(string $className)
    {
        if(isset($this->data[$className])) {
            return $this->data[$className];
        }

        // 使用反射获取对应对象
        $obj = $this->ref($className);
        if($obj) {
            $this->bind($className, $obj);
        }

        return $obj;
    }

    public function ref(string $className)
    {
        $refClass = new ReflectionClass($className);

        // 不可实例化的就直接退出
        if(!$refClass->isInstantiable()) {
            return null;
        }

        if($constructMethod = $refClass->getConstructor()) {
            $params = [];
            foreach ($constructMethod->getParameters() as $key => $paramName) {
                if($className = $paramName->getClass()->name) {
                    $params[$key] = $this->make($className);
                } elseif($className = $paramName->getName()) {
                    $params[$key] = $this->make($className);
                }
            }
            return $refClass->newInstanceArgs($params);
        }

        return $refClass->newInstanceWithoutConstructor();

    }


    // --------------------------------static methods--------------------------------
    /**
     * 生成单例对象
     *
     * @return static::class
     */
    public static function getInstance()
    {
        if(is_null(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }




}
