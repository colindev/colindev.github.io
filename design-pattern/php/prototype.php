# 原型模式 (副本模式)
<?php

// 考卷
class Exam {
    private $name = '';
    private $context = array();
    private $num = 0;
    public function setContext($question, $aswer) {
        $this->context[$question] = $aswer;
    }

    public function test() {
        yield 'input your name:';

        $this->name = trim(fgets(STDIN));

        // 我們這先不探討沒有考題時的錯誤處理
        $numPerQuestion = 100/count($this->context);

        foreach ($this->context as $question => $aswer) {
            yield $question;

            if (trim(fgets(STDIN)) == $aswer) {
                $this->num += $numPerQuestion;
            }
        }
    }

    public function count() {
        return "{$this->name} got {$this->num}";
    }
}

function main() {
    
    $exam = new Exam();
    $exam->setContext('1 + 1 = ?', '2');
    $exam->setContext('2 + 2 = ?', '4');
    $exam->setContext('1 + 2 + 3 = ?', '6');


    // 模擬3個學生考試
    // 故意先把考卷填寫完後先放陣列內在下一個迴圈輸出
    $exams = array();
    for ($i = 0; $i < 3; $i++) {
        $cloneExam = clone $exam;
        foreach ($cloneExam->test() as $lien) {
            yield $lien;
        }
        $exams[] = $cloneExam;
    }

    // 最後老師批改分數
    foreach ($exams as $cloneExam) {
        yield $cloneExam->count();
    }
}

// 不同語言有 clone 方法設計, 這邊就不深入探討其他語言的實作
