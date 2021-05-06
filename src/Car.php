<?php
namespace Src;

class Car
{
    protected $engine = null;

    protected $wheels = null;

    protected $doors = null;

    protected $name = '';

    public function __construct(Engine $engine, Wheel $wheel, Door $door, string $name)
    {
        $this->engine = $engine;
        $this->wheels = $wheel;
        $this->doors = $door;
        $this->name = $name;
    }

    public function run()
    {
        echo $this->name,'启动中...',PHP_EOL;
        $this->engine->run();
        $this->wheels->run();
        $this->doors->run();
    }

}
