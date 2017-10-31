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

function main() {
    // 產品列表
    $meal = new Product('大麥克', 50);
    $meal = (new Product('可樂', 25))->with($meal);
    $meal = (new Product('薯條', 30))->with($meal);

    yield $meal->getContext();

    yield $meal->getPrice();
}
