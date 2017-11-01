<?php

// 需要隱藏細節的類別
// 負責做漢堡的員工
class EmployeeA {
    public function makeBurger() {
        return '大麥克';
    }
}
// 負責裝飲料的員工
class EmployeeB {
    public function makeBeverage() {
        return '可樂';
    }
}

// 外部依賴介面
interface IStoreCounter {
    public function getBurger();
    public function getBeverage();
}

// 實作類別
class Counter implements IStoreCounter {
    private $employeeBurger;
    private $employeeBeverage;
    public function __construct() {
        $this->employeeBurger = new EmployeeA();
        $this->employeeBeverage = new EmployeeB();
    }
    public function getBurger() {
        return $this->employeeBurger->makeBurger();
    }
    public function getBeverage() {
        return $this->employeeBeverage->makeBeverage();
    }
}

// entry point
function main() {
    $counter = new Counter();

    yield $counter->getBurger();
    yield $counter->getBeverage();

}
