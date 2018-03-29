---
layout: post
title:  go基于文本和beego框架实现简单留言板
author: 承鹏辉
category: go
---

```



```


### 上代码

```

package main

import (
	"bufio"
	"encoding/json"
	"flag"
	"fmt"
	"io/ioutil"
	"log"
	"os"
	"strconv"
	"strings"
	"time"
)

var (
	Id   int
	Name string
)

func init() {
	flag.IntVar(&Id, "id", 0, "please set id:")
	flag.StringVar(&Name, "name", "", "please set id:")
	_, err := os.Stat("data.json")
	if err != nil {
		_, err := os.Create("data.json")
		if err != nil {
			log.Fatal(err)
		}
	}

}

type Web struct {
	Id       int       `json:"id"`
	Content  string    `json:"content"`
	Name     string    `json:"name"`
	CreateAt time.Time `json:"create_at"`
}

func main() {
	flag.Parse()

	if Id <= 0 {
		fmt.Println("请输入正确的id 例如: -id=110")
		os.Exit(0)
	}
	if len(Name) == 0 {
		fmt.Println("请输入正确的name例如: -name=pick")
		os.Exit(0)
	}
	fmt.Println("请输入命令例如:list,add,edit,delete,stop")

	inputReader := bufio.NewReader(os.Stdin)

	for {
		input, _ := inputReader.ReadString('\n')
		tp := strings.Trim(input, "\r\n") //数据清洗

		switch tp {
		case "stop":
			return
		case "list":
			list()
		case "add":
			add()
		case "delete":
			delete()
		default:
			fmt.Println("请输入正确的命令例如:list,add,edit,delete,stop")
		}
	}
}

func add() {

	fmt.Println("请输入内容:")

	inputReader := bufio.NewReader(os.Stdin)
	var content string

	for {
		input, _ := inputReader.ReadString('\n')
		content = strings.Trim(input, "\r\n") //数据清洗
		break
	}

	file, err := os.Open("./data.json")
	all, err := ioutil.ReadAll(file)
	if err != nil {
		fmt.Println(err)
	}
	var www []Web
	json.Unmarshal(all, &www)

	w := Web{Id: Id, Content: content, Name: Name, CreateAt: time.Now()}
	www = append(www, w)

	setjson, err := json.Marshal(&www)
	if err != nil {
		fmt.Println(err)
	}

	err = ioutil.WriteFile("./data.json", setjson, 0666)
	if err != nil {
		fmt.Println(err)
	}
	fmt.Println("写入完成....请输入其他的命令:例如list,add,edit,delete,stop")
}

func delete() {
	inputReader := bufio.NewReader(os.Stdin)
	var content string

	fmt.Println("请输入要删除的id")

	for {
		input, _ := inputReader.ReadString('\n')
		content = strings.Trim(input, "\r\n") //数据清洗
		break
	}
	id, err := strconv.Atoi(content)
	if err != nil {
		fmt.Println("请输入正确的id")
	}
	fmt.Println("正确的id", id)
	file, err := os.Open("./data.json")
	all, err := ioutil.ReadAll(file)
	if err != nil {
		fmt.Println(err)
	}
	var www []Web
	json.Unmarshal(all, &www)
	var delwww []Web
	for _, value := range www {
		setvalue := value
		if setvalue.Id == id {
			//删除结构体中的对象
			continue
		} else {
			delwww = append(delwww, setvalue)
		}
	}

	//删除成功之后要做的事情 重新写入
	setjson, err := json.Marshal(&delwww)
	if err != nil {
		fmt.Println(err)
	}

	err = ioutil.WriteFile("./data.json", setjson, 0666)
	if err != nil {
		fmt.Println(err)
	}
	fmt.Println("删除成功....请输入其他的命令:例如list,add,edit,delete,stop")

}

func list() {
	file, err := os.Open("./data.json")
	all, err := ioutil.ReadAll(file)
	if err != nil {
		fmt.Println(err)
	}
	var www []Web
	json.Unmarshal(all, &www)
	for _, v := range www {
		fmt.Printf("id---->:%d    内容---->:%s  姓名---->:%s   时间---->:%s\n", v.Id, v.Content, v.CreateAt)
	}
}








增删改beego代码

package controllers

import (
	"fmt"

	"strconv"

	"github.com/astaxie/beego"
	"github.com/astaxie/beego/logs"
	"github.com/astaxie/beego/orm"
	"github.com/astaxie/beego/validation"
	_ "github.com/go-sql-driver/mysql"
)

var valid validation.Validation

func init() {
	orm.RegisterDataBase("default", "mysql", "xxx:xxx@tcp(127.0.0.1:3306)/666?charset=utf8")

	orm.RegisterModel(new(User))
	orm.RunSyncdb("default", false, false)

}

type MainController struct {
	beego.Controller
}

func (c *MainController) Get() {
	c.Ctx.WriteString("Hello")
}

func (c *MainController) GetUserList() {

	pagesize := beego.AppConfig.String("pagesize")
	fmt.Println(pagesize)

	page := c.GetString("page")
	name := c.GetString("name")

	var where string = "id >0"

	if name != "" {
		where += " and name = " + "'" + name + "'"
	}

	PageInt, _ := strconv.Atoi(page)

	if PageInt <= 0 {
		PageInt = 1
	}

	var userList []User
	qb, _ := orm.NewQueryBuilder("mysql")
	qb.Select("id,name,age,author").From("user").Where(where).OrderBy("id").Desc().Limit(3).Offset((PageInt - 1) * 3)

	sql := qb.String()

	o := orm.NewOrm()

	_, err := o.Raw(sql).QueryRows(&userList)
	if err != nil {
		fmt.Println(err)
	}

	if len(userList) == 0 {
		userList = []User{}
	}
	em := ErrorJson{Status: 200, Message: "成功", Data: userList}
	c.Data["json"] = em
	c.ServeJSON()

}

func (c *MainController) GetUser() {
	log := logs.NewLogger(10)
	log.SetLogger("file", `{"filename":"test.log"}`)
	log.Warning("info", "error run...")

	id := c.GetString("id")

	setId, _ := strconv.Atoi(id)

	valid = validation.Validation{}
	valid.Required(id, "id").Message("id不可以为空")
	valid.Numeric(id, "id").Message("必须是整数")
	valid.Min(setId, 0, "id").Message("id必须大于0")

	if valid.HasErrors() {
		for _, err := range valid.Errors {
			em := ErrorJson{Status: 500, Message: err.Message, Data: ""}
			c.Data["json"] = em
			c.ServeJSON()
			return
		}
	}

	var userList User
	qb, _ := orm.NewQueryBuilder("mysql")
	qb.Select("id,name,age,author").From("user").Where("id=" + id).OrderBy("id").Desc().Limit(1).Offset(0)
	sql := qb.String()

	o := orm.NewOrm()
	err := o.Raw(sql).QueryRow(&userList)

	if err != nil {
		fmt.Println(err)
	}

	if userList.Id == 0 {
		em := ErrorJson{Status: 200, Message: "成功", Data: ""}
		c.Data["json"] = em
	} else {
		em := ErrorJson{Status: 200, Message: "成功", Data: userList}
		c.Data["json"] = em
	}

	c.ServeJSON()

}

func (c *MainController) CreateMain() {

	u := User{}

	if err := c.ParseForm(&u); err != nil {
		fmt.Println(err)
	}

	valid = validation.Validation{}

	b, err := valid.Valid(&u)

	if err != nil {
		fmt.Println(b)
	}
	if !b {
		for _, err := range valid.Errors {
			em := ErrorJson{Status: 500, Message: err.Message, Data: ""}
			c.Data["json"] = em
			c.ServeJSON()
			return
		}
	} else {
		o := orm.NewOrm()

		id, err := o.Insert(&u)

		if err != nil {
			em := ErrorJson{Status: 500, Message: "添加失败", Data: ""}
			c.Data["json"] = em
			c.ServeJSON()
		}

		if id > 0 {

			em := ErrorJson{Status: 200, Message: "添加成功", Data: &u}
			c.Data["json"] = em
			c.ServeJSON()

		} else {

			em := ErrorJson{Status: 500, Message: "添加失败", Data: ""}
			c.Data["json"] = em
			c.ServeJSON()
		}
	}

}


```



### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```


@承鹏辉
