<?php

use Lib\Container;
use Src\Car;
use Src\Door;
use Src\Engine;
use Src\Wheel;

require_once __DIR__ . '/vendor/autoload.php';


// $door = new Door();
// $engine = new Engine();
// $wheel = new Wheel();
// $car = new Car($engine, $wheel, $door);
// $car->run();

$container = Container::getInstance();
$container->bind('name', '汽车一号');
$container->make(Car::class)->run();
