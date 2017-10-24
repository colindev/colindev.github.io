#!/usr/bin/env php
# 抽象工廠
<?php

// 狀況: 產品套餐推出養生版
// 產品介面
interface IBurger {
    public function __construct($name);
    public function __tostring();
}

class BurgerVegetarian implements IBurger {
    private $name = '';
    public function __construct($name) {
        $this->name = $name;
    }
    public function __tostring() {
        return "素食".$this->name;
    }
}

class BurgerNormal implements IBurger {
    private $name = '';
    public function __construct($name) {
        $this->name = $name;
    }
    public function __tostring() {
        return $this->name;
    }
}

interface IBeverage {
    public function __construct($name);
    public function __tostring();
}

class BeverageNoSugar implements IBeverage {
    private $name = '';
    public function __construct($name) {
        $this->name = $name;
    }
    public function __tostring() {
        return "無糖".$this->name;
    }
}

class BeverageNormal implements IBeverage {
    private $name = '';
    public function __construct($name) {
        $this->name = $name;
    }
    public function __tostring() {
        return $this->name;
    }
}

// 介面工廠
interface AbsFactory {
    public function MakeBurger($name);
    public function MakeBeverage($name);
}

// 一般套餐
class NormalSetsFactory implements AbsFactory {
    public function MakeBurger($name) {
        return new BurgerNormal($name);
    }
    public function MakeBeverage($name) {
        return new BeverageNormal($name);
    }
}

class HealthSetsFactory implements AbsFactory {
    public function MakeBurger($name) {
        return new BurgerVegetarian($name);
    }
    public function MakeBeverage($name) {
        return new BeverageNoSugar($name);
    }
}

// 以上架構屬於抽象工廠

$factory = null;
switch (strToUpper($argv[1])) {
case "A": $factory = new NormalSetsFactory(); break;
case "B": $factory = new HealthSetsFactory(); break;
}

// 安全檢查
if (!$factory || count($argv) < 4) {
    die("Usage: {$argv[0]} [A/B] [Burger] [Beverage]\n");
}

echo $factory->MakeBurger((string)$argv[2]), " + ", $factory->MakeBeverage((string)$argv[3]), PHP_EOL;

// 優點: 封裝建構細節, 要增加另一系列商品很快
// 缺點: 如果要增加附餐, 要改很多, 如果商品系列多那就得改更多
