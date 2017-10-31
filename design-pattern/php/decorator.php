<?php

interface ProductionDecorator {
    public function with(ProductionDecorator $product);
    public function getContext();
    public function getPrice();
}

// 變形
class Product implements ProductionDecorator {
    private $name = '';
    private $price = 0;
    private $upstream = null;
    public function __construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }
    public function with(ProductionDecorator $product) {
        $this->upstream = $product;
        return $this;
    }
    public function getContext() {
        return $this->name.($this->upstream ? ' + '.$this->upstream->getContext():'');
    }
    public function getPrice() {
        return $this->price + ($this->upstream ? $this->upstream->getPrice() : 0);
    }
}

// 原本應該把各種產品分各自class 撰寫

function main($flags) {

    $usage = $flags->usage;
    $args = $flags->args;

    if ( ! count($args) || preg_match('/^[^A-E\s]$/i', join($args, ''))) {
        yield $usage('[A..E[A..E[A..E]]]');
        return;
    }
    
    // 產品列表
    $productList = array(
        'A' => array('大麥克', 50),
        'B' => array('麥香魚', 45),
        'C' => array('可樂', 20),
        'D' => array('紅茶', 15),
        'E' => array('薯條', 30),
    );

    $args = array_map(function($str){return strtoupper($str);}, $args);
    $item = $productList[array_shift($args)];
    $meal = new Product($item[0], $item[1]);
    foreach ($args as $str) {
        $item = $productList[$str];
        $meal = (new Product($item[0], $item[1]))->with($meal);
    }

    yield $meal->getContext();

    yield $meal->getPrice();
}
