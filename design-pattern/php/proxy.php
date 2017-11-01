<?php

interface IReceiver {
    public function receive($msg, $from);
}

class TargetUser implements IReceiver {
    public function receive($msg, $from) {
        return "Target user receive {$msg} from {$from}";
    }
}

class Proxy implements IReceiver {
    private $user;
    public function __construct() {
        // 這邊使用直接但違反開放封閉原則的寫法
        // 可以利用其他設計模式消除掉這個問題
        $this->user = new TargetUser();
    }
    public function receive($msg, $from) {
        return $this->user->receive($msg, $from);
    }
}

// entry point
function main() {
    
    $proxy = new Proxy();
    return $proxy->receive('Hello', 'some one');
}
