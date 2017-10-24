#!/usr/bin/env php
# 工廠方法模式
<?php
// sets interface
interface ISets {
    public function setItem($item);
    public function __tostring();
}

// classes
class Sets implements ISets {

    private $items = array();
    public function setItem($item) {
        $this->items[] = $item;
    }

    public function __tostring() {
        return join($this->items, " + ");
    }
}

// sets factory
interface Factory {
    public function make();
}

class SetAFactory implements Factory {
    public function make() {
        $sets = new Sets();
        $sets->setItem("可樂");
        $sets->setItem("薯條");
        $sets->setItem("大麥克");

        return $sets;
    }
}

class SetBFactory implements Factory {
    public function make() {
        $sets = new Sets();
        $sets->setItem("紅茶");
        $sets->setItem("沙拉");
        $sets->setItem("麥香魚");

        return $sets;
    }
}

// entry point
$factoryName = 'Set'.strToUpper($argv[1]).'Factory';

// 安全檢查
if ( ! class_exists($factoryName)) {
    echo "Usage: {$argv[0]} [A/B]\n";
    die("\033[31m[".$factoryName."] not found\033[m\n");
}

$factory = new $factoryName;
$sets = $factory->make();
echo $sets, PHP_EOL;

// 優點: 相對於簡單工廠, 工廠方法避免了增加產品需要更動工廠的麻煩
// 缺點: 重現了簡單工廠想避免使用者(工程師)認識各類型產品的類別名稱
// 要認識各個工廠名稱
