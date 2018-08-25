package main

import (
	"bufio"
	"bytes"
	"errors"
	"fmt"
	"io"
	"os"
	"strconv"
)

// 只支援+-()的計算機
type Expression interface {
	Interpret(*stack, *bytes.Buffer) (bool, error)
}

type op int

func (x op) wrap(n int) int {
	return int(x) * n
}

const (
	add = op(1)
	sub = op(-1)
)

type stack struct {
	num int
	op  op
}

func newStack() *stack {
	return &stack{op: add}
}

var (
	numRune = numExpr{
		'0': {},
		'1': {},
		'2': {},
		'3': {},
		'4': {},
		'5': {},
		'6': {},
		'7': {},
		'8': {},
		'9': {},
	}
	opRune = opExpr{
		'+': add,
		'-': sub,
	}
	spaceRune = spaceExpr{
		' ': {},
	}
	bracketsRune = bracketsExpr{
		start: '(',
		end:   ')',
	}
)

type opExpr map[rune]op

func (ex opExpr) Interpret(stk *stack, buf *bytes.Buffer) (bool, error) {
	r, _, err := buf.ReadRune()
	fmt.Printf(">> [%v] %v\n", r, err)
	if err != nil {
		buf.UnreadRune()
		return false, err
	}
	n, exists := ex[r]
	if !exists {
		buf.UnreadRune()
		return false, nil
	}
	stk.op = n
	return true, nil
}

type spaceExpr map[rune]struct{}

func (ex spaceExpr) Interpret(stk *stack, buf *bytes.Buffer) (bool, error) {

	var (
		err   error
		r     rune
		match = false
	)

	for {
		r, _, err = buf.ReadRune()
		if err == io.EOF {
			buf.UnreadRune()
			return match, nil
		} else if err != nil {
			buf.UnreadRune()
			return match, err
		}
		if _, exists := ex[r]; !exists {
			buf.UnreadRune()
			return match, nil
		}
		match = true
	}
}

type numExpr map[rune]struct{}

func (ex numExpr) Interpret(stk *stack, buf *bytes.Buffer) (bool, error) {

	var (
		r   rune
		err error
	)

	runes := []byte{}
	for {
		r, _, err = buf.ReadRune()
		if err != nil {
			if len(runes) == 0 {
				return false, err
			}
			break
		}
		if _, exists := ex[r]; !exists {
			buf.UnreadRune()
			break
		}
		runes = append(runes, byte(r))
	}
	n, err := strconv.Atoi(string(runes))
	stk.num = stk.num + stk.op.wrap(n)
	return true, err
}

type bracketsExpr struct {
	start, end rune
}

func (ex bracketsExpr) Interpret(stk *stack, buf *bytes.Buffer) (processed bool, err error) {

	var (
		r        rune
		startBuf = 0
		subBuf   *bytes.Buffer
	)

	r, _, _ = buf.ReadRune()
	if r != ex.start {
		buf.UnreadRune()
		return false, nil
	}

	for {
		r, _, err = buf.ReadRune()
		if err == io.EOF {
			return processed, nil
		}
		if err != nil {
			return processed, err
		}

		if r == ex.end {
			startBuf--
			if startBuf < 0 {
				return processed, errors.New("error end brackets")
			}
			if startBuf == 0 {
				if subBuf != nil {
					subStk := newStack()
					_, err := bracketsRune.Interpret(subStk, subBuf)
					if err != nil && err != io.EOF {
						return processed, err
					}
					stk.num = stk.num + stk.op.wrap(subStk.num)
					subBuf = nil
					processed = true
				}
			}
			continue
		}
		if startBuf > 0 {
			subBuf.WriteRune(r)
			continue
		}
		if r == ex.start {
			if startBuf == 0 {
				subBuf = bytes.NewBuffer(nil)
			}
			startBuf++
			continue
		}
	}
}

func compile(its []Expression, buf *bytes.Buffer) (int, error) {

	var (
		processed    bool
		cntProcessed int
		err          error
	)
	stk := newStack()
	for {
	Parse:
		cntProcessed = 0
		for _, it := range its {
			fmt.Printf("processed by %T\n", it)
			processed, err = it.Interpret(stk, buf)
			if processed {
				fmt.Printf("processed by %T with %v, remain [%s]\n", it, err, buf.String())
				cntProcessed++
			}
		}
		if cntProcessed == 0 {
			r, _, _ := buf.ReadRune()
			if buf.Len() > 0 {
				return 0, fmt.Errorf("unknow rune[%v]", r)
			}
			break
		}
		goto Parse
	}

	return stk.num, err
}

func main() {

	buf := bufio.NewReader(os.Stdin)
	for {
		line, _, err := buf.ReadLine()
		if err != nil {
			fmt.Println(err)
			return
		}

		n, err := compile([]Expression{numRune, spaceRune, bracketsRune, opRune}, bytes.NewBuffer(line))
		fmt.Println("=", n, " error=", err)
	}
}
