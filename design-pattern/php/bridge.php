# 橋接模式
<?php

interface IApp {
    public function getID();
    public function run();
    public function stop();
}

class AppA implements IApp {
    public function getID() {
        return 'aaa';
    }
    public function run() {
        return 'app "A" running...';
    }
    public function stop() {
        return 'app "A" stopped';
    }
}

class AppB implements IApp {
    public function getID() {
        return 'bbb';
    }
    public function run() {
        return 'app "B" running...';
    }
    public function stop() {
        return 'app "B" stopped';
    }
}

abstract class AbsPhone {
    abstract public function installApp(IApp $app);
    abstract public function runApp($appID);
    abstract public function stopApp($appID);
}

class PhoneA extends AbsPhone {
    private $apps = array();
    public function installApp(IApp $app) {
        // 這邊不探討裝兩次
        $this->apps[$app->getID()] = $app;
    }
    public function runApp($appID) {
        // 這邊不探討執行不存在的app問題
        return 'in phone a: '.$this->apps[$appID]->run();
    }
    public function stopApp($appID) {
        return 'in phone a: '.$this->apps[$appID]->stop();
    }
}

class PhoneB extends AbsPhone {
    private $apps = array();
    public function installApp(IApp $app) {
        $this->apps[$app->getID()] = $app;
    }
    public function runApp($appID) {
        return 'in phone b: '.$this->apps[$appID]->run();
    }
    public function stopApp($appID) {
        return 'in phone b: '.$this->apps[$appID]->stop();
    }
}

// entry point
function main() {
    
    $appA = new AppA();
    $appB = new AppB();

    $phoneA = new PhoneA();
    $phoneA->installApp($appA);
    $phoneA->installApp($appB);
    yield $phoneA->runApp($appA->getID());
    yield $phoneA->stopApp($appA->getID());
    yield $phoneA->runApp($appB->getID());
    yield $phoneA->stopApp($appB->getID());
    
    yield str_repeat('-', 15);
        
    $phoneB = new PhoneB();
    $phoneB->installApp($appA);
    $phoneB->installApp($appB);
    yield $phoneB->runApp($appA->getID());
    yield $phoneB->stopApp($appA->getID());
    yield $phoneB->runApp($appB->getID());
    yield $phoneB->stopApp($appB->getID());
}
