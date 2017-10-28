# 抽象工廠
<?php

// 狀況: 產品套餐推出養生版
// 產品介面
interface IBurger {
    public function __tostring();
}

class BurgerHealth implements IBurger {
    public function __tostring() {
        return "素食漢堡";
    }
}

class BurgerNormal implements IBurger {
    public function __tostring() {
        return "正常漢堡";
    }
}

interface IBeverage {
    public function __tostring();
}

class BeverageHealth implements IBeverage {
    public function __tostring() {
        return "無糖可樂";
    }
}

class BeverageNormal implements IBeverage {
    public function __tostring() {
        return "正常可樂";
    }
}

// 介面工廠
interface IFactory {
    public function MakeBurger();
    public function MakeBeverage();
}

// 一般套餐
class NormalSetsFactory implements IFactory {
    public function MakeBurger() {
        return new BurgerNormal();
    }
    public function MakeBeverage() {
        return new BeverageNormal();
    }
}

class HealthSetsFactory implements IFactory {
    public function MakeBurger() {
        return new BurgerHealth();
    }
    public function MakeBeverage() {
        return new BeverageHealth();
    }
}

// 與工廠方法差異點在於
// 1. 分類多了一層 健康/正常 套餐
// 2. 假設餐點都是一對一 例如 可樂分 無糖/一般

// entry point
function main($flags) {

    $usage = $flags->usage;
    $type = array_shift($flags->args);

    $factory = null;
    switch (strToUpper($type)) {
    // 一般套餐工廠
    case "A": $factory = new NormalSetsFactory(); break;
    // 健康套餐工廠
    case "B": $factory = new HealthSetsFactory(); break;
    }

    // 安全檢查
    if ( ! $factory) {
        return $usage('[A/B]
    A = 一般餐點
    B = 健康餐點');
    }
    
    return $factory->MakeBurger()." + ".$factory->MakeBeverage();
}
// 優點: 封裝建構細節, 要增加另一系列商品很快
// 缺點: 如果要增加附餐, 要改很多, 如果商品系列多那就得改更多
