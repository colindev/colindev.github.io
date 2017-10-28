# 原型模式 (副本模式)
<?php

// 考卷
class Exam {
    private $name = '';
    private $context = array();
    public function setContext($question, $aswer) {
        $this->context[$question] = $aswer;
    }

    public function test() {
        yield 'input your name:';

        $this->name = trim(fgets(STDIN));

        // 我們這先不探討沒有考題時的錯誤處理
        $numPerQuestion = 100/count($this->context);
        $gotNum = 0;

        foreach ($this->context as $question => $aswer) {
            yield $question;

            if (trim(fgets(STDIN)) == $aswer) {
                $gotNum += $numPerQuestion;
                yield 'O';
            } else {
                yield 'X';
            }
        }

        yield "{$this->name} got {$gotNum}";
    }
}

function main() {
    
    $exam = new Exam();
    $exam->setContext('1 + 1 = ?', '2');
    $exam->setContext('2 + 2 = ?', '4');
    $exam->setContext('1 + 2 + 3 = ?', '6');

    // 模擬3個學生考試
    for ($i = 0; $i < 3; $i++) {
        $cloneExam = clone $exam;
        foreach ($cloneExam->test() as $lien) {
            yield $lien;
        }
    }
}

// 不同語言有 clone 方法設計, 這邊就不深入探討其他語言的實作
