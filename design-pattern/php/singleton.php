# 獨體模式
<?php

class Singleton {
    private static $instance = null;
    public static function instance() {
        // 注意: 單線程成式才能這樣簡單寫
        if (null === self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private $createTime = 0;
    private function __construct() {
        // 私有化建構式
        $this->createTime = microtime(1);
    }
    public function __toString() {
        return (string)$this->createTime;
    }
}

class NormalClass {

    private $createTime = 0;
    public function __construct() {
        $this->createTime = microtime(1);
    }
    public function __toString() {
        return (string)$this->createTime;
    }
}

// entry point
function main() {

    // 每秒建構一次 共三次
    for ($i = 0; $i < 3; $i++) {
        $singleton = Singleton::instance();
        $normal = new NormalClass();
        yield "singleton: {$singleton} .. normal: {$normal}";

        sleep(1);
    }
}

// 優點: 防呆, 防止二次建構你只需要建構一次用到底的物件 如 DB連線
// 缺點: 不利於單元測試, 一旦使用了獨體可能造成程序有上下文關聯
// 思考: 以 IoC 取代
