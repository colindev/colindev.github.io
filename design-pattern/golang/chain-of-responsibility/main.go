package main

import (
	"flag"
	"fmt"
)

const (
	Notice = 1 << iota
	Warning
	Fatal
)

// 需要處理的物件
type Payload struct {
	Level int
	Msg   string
}

// interface
type Logger interface {
	// 以回傳代替真的寫入
	Handle(Payload)
}

// golang 不需顯式聲明介面
// 這是個很方便很彈性的特性
type NoticeLogger struct {
	next Logger
}

func (l *NoticeLogger) Handle(p Payload) {
	if p.Level&Notice > 0 {
		fmt.Println("[notice]", p.Msg)
	}

	if l.next != nil {
		l.next.Handle(p)
	}
}

type WarningLogger struct {
	next Logger
}

func (l *WarningLogger) Handle(p Payload) {
	if p.Level&Warning > 0 {
		fmt.Println("[warning]", p.Msg)
	}

	if l.next != nil {
		l.next.Handle(p)
	}
}

type FatalLogger struct {
	next Logger
}

func (l *FatalLogger) Handle(p Payload) {
	if p.Level&Fatal > 0 {
		fmt.Println("[fatal]", p.Msg)
	}

	if l.next != nil {
		l.next.Handle(p)
	}
}

// entry point
func main() {
	p := Payload{}
	flag.IntVar(&p.Level, "l", Notice, "level")
	flag.Parse()
	p.Msg = flag.Arg(0)

	var log Logger
	log = &FatalLogger{}
	log = &WarningLogger{next: log}
	log = &NoticeLogger{next: log}

	log.Handle(p)
}
