#!/usr/bin/env php
<?php

// interface
interface Game {
    public function Name();
}

// game class
class GameA implements Game {
    public function Name() {
        return "Game A";
    }
}

class GameB implements Game {
    public function Name() {
        return "Game B";
    }
}

// single factory
class GameFactory {
    public static function build($gameType) {
        $game = null;
        switch (strToUpper($gameType)) {
        case "A":
            $game = new GameA(); break;
        case "B":
            $game = new GameB(); break;
        }

        return $game;
    }
}

// entry point

$game = GameFactory::build(@$argv[1]);

var_dump($game);

// 缺點: 要增加類別都必須改動build 方法
