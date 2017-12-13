package main

import (
	"container/list"
	"fmt"
)

func main() {

	// golang container/list 就是一個迭代器實作
	fmt.Println(green("\ncontainer/list"))
	c := list.New()
	c.PushBack("a")
	c.PushBack("b")
	c.PushBack("c")
	c.PushBack("d")
	c.PushBack("e")
	c.PushBack("f")
	c.PushBack("g")

	// 遍歷所有items
	for item := c.Front(); item != nil; item = item.Next() {

		fmt.Printf("<%T> %v\n", item.Value, item.Value)

	}

	fmt.Println(green("\nslice"))
	s := []string{
		"A",
		"B",
		"C",
		"D",
		"E",
		"F",
		"G",
	}

	for i, item := range s {

		fmt.Println(i, "=", item)

	}

	fmt.Println(green("\nmap"))
	m := map[string]int{
		"a": 1,
		"b": 2,
		"c": 3,
		"d": 4,
		"e": 5,
		"f": 6,
		"g": 7,
	}

	for k, v := range m {

		fmt.Println(k, "=", v)

	}

}

func green(line string) string {
	return color("\033[32m", line)
}
func color(tab, line string) string {
	return fmt.Sprintf("%s%s\033[m", tab, line)
}
