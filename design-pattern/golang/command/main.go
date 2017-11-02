package main

import (
	"fmt"
)

// 命令介面
type Command interface {
	Exec()
}

// 包裝工作A執行命令
type CommandWorkA struct {
	Command // 可寫可不寫, 寫了是顯式宣告實作介面
	*Receiver
}

func (c *CommandWorkA) Exec() {
	c.Receiver.DoWorkA()
}
func NewCommandWorkA(r *Receiver) Command {
	return &CommandWorkA{Receiver: r}
}

type CommandWorkB struct {
	// Command 不宣告實作但是實際有實作有相同的方法
	// 鴨式定型
	*Receiver
}

func (c *CommandWorkB) Exec() {
	c.Receiver.DoWorkB()
}
func NewCommandWorkB(r *Receiver) Command {
	return &CommandWorkB{Receiver: r}
}

// 實際功能執行者
type Receiver struct{}

func (r *Receiver) DoWorkA() {
	fmt.Println("do work a")
}
func (r *Receiver) DoWorkB() {
	fmt.Println("do work b")
}

// 假設今天功能需要擴充, 額外加了一個功能進來
type PlugReceiver struct{}

func (pr *PlugReceiver) DoSomethingElse() {
	fmt.Println("pluger do something...")
}

// 再增加一個命令包裹器
type CommandPlug struct {
	Receiver *PlugReceiver
}

func (c *CommandPlug) Exec() {
	c.Receiver.DoSomethingElse()
}

// 命令收集器
type CommandCollecter struct {
	commands map[Command]bool
}

func (cc *CommandCollecter) Add(c Command) {
	// 僅看單線程,先不做Lock
	cc.commands[c] = true
}
func (cc *CommandCollecter) Remove(c Command) {
	delete(cc.commands, c)
}
func (cc *CommandCollecter) SendCommand() {
	for c := range cc.commands {
		c.Exec()
	}
}
func NewCollecter() *CommandCollecter {
	return &CommandCollecter{
		commands: map[Command]bool{},
	}
}

func main() {

	// 建構一個命令收集器, 在餐廳範例內屬於服務生角色
	collecter := NewCollecter()

	// 建構一個命令接收者(廚師)
	receiver := &Receiver{}

	// 建構兩筆命令 (點菜)
	cmdA := NewCommandWorkA(receiver)
	cmdB := NewCommandWorkB(receiver)

	// 建構擴充命令
	cmdX := &CommandPlug{Receiver: &PlugReceiver{}}

	// 服務收收單
	collecter.Add(cmdA)
	collecter.Add(cmdB)
	collecter.Add(cmdX)
	// 取消一筆
	collecter.Remove(cmdA)

	// 確認送單
	collecter.SendCommand()
}
