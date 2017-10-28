# 工廠方法模式
<?php
// sets interface
interface ISets {
    public function __toString();
}

// classes
class SetsA implements ISets {

    private $items = array(
        '可樂',
        '薯條',
        '大麥克',
    );

    public function __tostring() {
        return join($this->items, " + ");
    }
}

class SetsB implements ISets {

    private $items = array(
        '紅茶',
        '沙拉',
        '麥香魚',
    );

    public function __tostring() {
        return join($this->items, " + ");
    }
} 

// sets factory
interface IFactory {
    public function make();
}

class SetsAFactory implements IFactory {
    public function make() {
        return new SetsA();
    }
}

class SetsBFactory implements IFactory {
    public function make() {
        return new SetsB();
    }
}

// entry point =======================================
function main($flags) {

    $usage = $flags->usage;
    $setsType = array_shift($flags->args);
    $factoryName = 'Sets'.strToUpper($setsType).'Factory';
    
    // 安全檢查
    if ( ! class_exists($factoryName)) {
        return $usage('[A/B]');
    }
    
    // 取得工廠實體
    $factory = new $factoryName;
    // 用共同界面方法產生套餐
    return $factory->make();
}
// 優點: 相對於簡單工廠, 工廠方法避免了增加產品需要更動工廠的麻煩
// 缺點: 重現了簡單工廠想避免使用者(工程師)認識各類型產品的類別名稱
// 要認識各個工廠名稱
