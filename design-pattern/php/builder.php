#!/usr/bin/env php
# 建構者模式
<?php

// 先忘掉抽象工廠
// 過早優化會死很慘
// 再從工廠方法裡的商品細節增加點複雜度來看建構者模式
// 剛剛的可樂, 薯條, 漢堡點餐流程增加細節

// 一樣從介面開始定義
interface IItem {
    // 取得品名
    public function name();
    // 取得價格
    public function price();
    // 製作
    public function make();
}


