#!/usr/bin/env php
# 工廠方法模式
<?php
// sets interface
interface ISets {
    public function setItem($item);
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
    die($factory." not found");
}

$factory = new $factoryName;
$sets = $factory->make();
echo $sets, PHP_EOL;
