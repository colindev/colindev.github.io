package main

import (
	"bufio"
	"fmt"
	"io"
	"os"
	"strings"
	"sync"
)

// 群組宣告(demo)
type (
	// 主題
	Subject interface {
		Register(Observer)
		Unregister(Observer)
		Notify()
		SetState(Payload) Subject
		GetState() Payload
	}

	// 訂閱者
	Observer interface {
		Update(Subject)
	}

	// 依照不同語言選擇的資料介面
	Payload struct {
		Type int
		Data string
	}
)

type StdinSubject struct {
	// 順便demo 繼承
	sync.RWMutex
	state     Payload
	observers map[Observer]bool
}

func (ss *StdinSubject) Register(o Observer) {
	ss.Lock()
	defer ss.Unlock()
	ss.observers[o] = true
}
func (ss *StdinSubject) Unregister(o Observer) {
	ss.Lock()
	defer ss.Unlock()
	delete(ss.observers, o)
}
func (ss *StdinSubject) Notify() {
	ss.RLock()
	defer ss.RUnlock()
	for o := range ss.observers {
		go func(o Observer) {
			o.Update(Subject(ss))
		}(o)
	}
}
func (ss *StdinSubject) SetState(p Payload) Subject {
	ss.Lock()
	defer ss.Unlock()
	ss.state = p
	return ss
}
func (ss *StdinSubject) GetState() Payload {
	ss.RLock()
	defer ss.RUnlock()
	return ss.state
}

type ObserverA struct{}

func (*ObserverA) Update(s Subject) {
	p := s.GetState()
	fmt.Printf("observer A receive [%d] %s\n", p.Type, p.Data)
}

type ObserverX struct {
	name string
}

func (o *ObserverX) Update(s Subject) {
	p := s.GetState()
	fmt.Printf("observer [%s] receive [%d] %s\n", o.name, p.Type, p.Data)
}

func main() {
	fmt.Println("Usage: command [int(type)] [string(data)]")

	// 主題實體
	subject := &StdinSubject{observers: map[Observer]bool{}}

	// 觀察者A
	a := &ObserverA{}

	// 觀察者X * 2
	x1 := &ObserverX{"ox1"}
	x2 := &ObserverX{"ox2"}

	// 註冊觀察者
	subject.Register(a)
	subject.Register(x1)
	subject.Register(x2)

	// 順便展示一下golang 交談式命令
	stdin := bufio.NewReader(os.Stdin)
	for {
		// 無窮迴圈
		line, _, err := stdin.ReadLine()
		if err != nil {
			if err != io.EOF {
				fmt.Println(err)
				return
			}
			fmt.Println("bye")
			return
		}

		str := string(line)
		if str == "quit" {
			fmt.Println("bye")
			return
		}
		// 初始化payload
		p := Payload{}
		fmt.Fscan(strings.NewReader(str), &p.Type, &p.Data)

		subject.SetState(p).Notify()
	}

}
