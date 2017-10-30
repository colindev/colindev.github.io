<?php
// 轉接器模式
namespace systemA {
// 假設快餐店的庫存系統是以這個介面設計產品
interface IProduction {
    public function getName();
    public function getPrice();
    public function getCookbook();
}

// 這邊偷懶一下, 用抽象類別處理共同方法
// 請注意, 這個類別不會出現在UML圖中
abstract class Burger implements IProduction {
    protected $name = '';
    protected $price = 0;
    public function getName() {
        return $this->name;
    }
    public function getPrice() {
        return $this->price;
    }
}

class BurgerA extends Burger {
    protected $name = 'Burger A';
    protected $price = 50;
    public function getCookbook() {
        return 'put "a" behind "Burger"';
    }
}
class BurgerB extends Burger {
    protected $name = 'Burger A';
    protected $price = 75;
    public function getCookbook() {
        return 'put "b" behind "Burger"';
    }
}
}

namespace systemB {
// 由於寫menu系統的工程師也同時在開發輸出列表的
interface IProduction {
    public function getDetail();
}

class Menu {
    private $products = array();
    public function addItem(IProduction $item) {
        $this->products[] = $item;
    }

    public function display() {
        $ret = array();
        foreach ($this->products as $item) {
            $ret[] = $item->getDetail();
        }

        return join($ret, PHP_EOL);
    }
}
}

// entry point
// ok 這下好了, 兩邊的介面兜不起來
// 轉接器出場了
namespace {

class ProductionAdaptor implements systemB\IProduction {
    private $product = null;
    public function __construct(systemA\IProduction $item) {
        $this->product = $item;
    }
    public function getDetail() {
        return join(array(
            $this->product->getName(),
            $this->product->getPrice(),
            $this->product->getCookbook(),
        ), PHP_EOL);
    }
}

function main() {
    
    $menu = new systemB\Menu();
    $menu->addItem(new ProductionAdaptor(new systemA\BurgerA()));
    $menu->addItem(new ProductionAdaptor(new systemA\BurgerB()));

    return $menu->display();
}

}
