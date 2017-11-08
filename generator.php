#!/usr/bin/env php
<?php

function times(Generator $g, $n){
    foreach ($g as $item) {
        if ($n <= 0) {
            return;
        }
        $n--;
        yield $item;
    }
}

function filter(Generator $g, Closure $c) {
    foreach ($g as $item) {
        if ($c($item)) {
            yield $item;
        }
    }
}

function num(Generator $g, $n) {
    for ($i = 1; $i <= $n; $i++) {
        yield $i;
    }
}

function nature($start = 0) {
    if ($start < 0) {
        throw new RuntimeException('arg[0] must be nature');
    }
    while (true) {
        yield $start;
        $start++;
    } 
}

function head(Generator $g, $n) {
    for ($i = 0; $i < $n; $i++) {
        yield $g->current();
        $g->next();
    }
}

function tail(Generator $g, $n) {
    $cache = [];
    foreach ($g as $item) {
        if ($n <= 0) {
            array_shift($cache);
        }
        $n--;
        $cache[] = $item;
    }
    foreach ($cache as $item) {
        yield $item;
    }
}

// ==============================
// 這種手法也是一種middleware

// 輸出所有自然數
$items = nature();
// 30次
$items = times($items, 30);
// 取前20次
$items = head($items, 20);
// 取後10次
$items = tail($items, 10);
// 只取能被2整除的數
$items = filter($items, function($n){return $n % 2 == 0;});

foreach ($items as $item) {
    echo $item, PHP_EOL;
}
