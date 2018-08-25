package main

// 中介者介面
type Mediator interface {
	Mediate(Colleague) error
}

// 需要協調的類別介面
type Colleague interface {
	GetState() State
	Action(State) error
}

// data struct
type Kind int

const (
	KindHello Kind = iota
)

type State struct {
	Kind
	Payload string
}

// 中介者實體
type mediator struct {
	c1 Colleague
	c2 Colleague
	c3 Colleague
}

func (m *mediator) Mediate(c Colleague) {

	state := c.GetState()

	switch state.Kind {
	case KindHello:
		for cc := range map[Colleague]struct{}{m.c1: {}, m.c2: {}, m.c3: {}} {

		}
	}

	return nil
}
