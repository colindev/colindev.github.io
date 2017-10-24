#!/usr/bin/env php
<?php

// interface
interface ISets {
    public function __tostring();
}

// game class
class SetsA implements ISets {
    public function __tostring() {
        return "薯條+可樂+大麥克";
    }
}

class SetsB implements ISets {
    public function __tostring() {
        return "紅茶+沙拉+雞塊";
    }
}

// single factory
class Factory {
    public static function build($setsType) {
        $sets = null;
        switch (strToUpper($setsType)) {
        case "A":
            $sets = new SetsA(); break;
        case "B":
            $sets = new SetsB(); break;
        }

        return $sets;
    }
}

// entry point

$myOrder = Factory::build(@$argv[1]);

echo $myOrder, PHP_EOL;

// 缺點: 要增加類別都必須改動build 方法
// 違反開放封閉原則
