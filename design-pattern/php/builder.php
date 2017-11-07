# 建構者模式
<?php

// 先忘掉抽象工廠
// 過早優化會死很慘
// 再從工廠方法裡的商品細節增加點複雜度來看建構者模式
// 剛剛的可樂, 薯條, 漢堡點餐流程增加細節

// 目標: 能接受點餐 如 

// 一樣從介面開始定義
interface ISetsBuilder {
    // 飲料
    public function makeBeverage();
    // 取得價格
    public function makeBurger();
    // 製作
    public function makeDessert();
}

class SetsABuilder implements ISetsBuilder {
    public function makeBeverage() {
        return "可樂";
    }
    public function makeBurger() {
        return "大麥克"; // 思考: 不同的漢堡再加入一層建構者模式?
    }
    public function makeDessert() {
        return "薯條";
    }
}

class SetsBBuilder implements ISetsBuilder {
    public function makeBeverage() {
        return "紅茶";
    }
    public function makeBurger() {
        return "麥香魚";
    }
    public function makeDessert() {
        return "沙拉";
    }
}

// Director
class SetsDirector {
    private $builder;
    public function __construct(ISetsBuilder $builder) {
        $this->builder = $builder;
    }
    public function make() {
        $items = array();
        $items[] = $this->builder->makeBurger();
        $items[] = $this->builder->makeBeverage();
        $items[] = $this->builder->makeDessert();

        return join($items, " + ");
    }
}

// entry point
function main($flags) {
    
    $usage = $flags->usage;
    $type = array_shift($flags->args);

    $builder = 'Sets'.strtoupper($type).'Builder';

    // 安全檢查
    if ( ! class_exists($builder)) {
        return $usage('[A/B]');
    }

    $director = new SetsDirector(new $builder);
    return $director->make();
}
// 使用場景: 當物件初始化的步驟繁瑣時,避免在各處佈滿初始化細節
// 優點: 不管客戶點何種套餐, 建構者物件不會忘了給
// 缺點: 如果該套餐沒附飲料, 你還是需要給一個空實作的飲料方法
