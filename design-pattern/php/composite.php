<?php

// 組件介面
interface Component {
    public function run();
    public function add(Component $cmp);
    public function remove(Component $cmp);
}

class Composite implements Component {
    private $components = array();
    private $name = '';
    public function __construct($name) {
        $this->name = $name;
    }
    public function run() {
        yield "{$this->name} call components running...";
        foreach ($this->components as $item) {
            // 這個迴圈是用來處理Generator 
            foreach ($item->run() as $yield) {
                yield $yield;
            }
        }
    }
    public function add(Component $cmp) {
        $this->components[] = $cmp;
    }
    public function remove(Component $item) {
        // 懶得寫
    }
}

class Leaf implements Component {
    private $name = '';
    public function __construct($name) {
        $this->name = $name;
    }
    public function run() {
        yield "{$this->name} running...";
    }
    public function add(Component $item) {
        // 不能寫
        throw new RuntimeExeption("末端節點不可實作", 500);
    }
    public function remove(Component $item) {
        // 不能寫
        throw new RuntimeExeption("末端節點不可實作", 500);
    }
}

// entry point
function main() {
    $top = new Composite('top');
    $middleware = new Composite('middleware');
    $end1 = new Leaf("end-1");
    $end2 = new Leaf("end-2");
    $end3 = new Leaf("end-3");

    $top->add($middleware);

    $middleware->add($end1);
    $middleware->add($end2);
    $middleware->add($end3);

    return $top->run();
}
