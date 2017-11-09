---
layout: post
title:  go简单文件上传
author: 承鹏辉
category: go
---

```
go语言文件上传 而且go带指针非常好用
go很简洁，像php一样好学
又有很强大的协程
值得好好学习下

```


### 正文

```
package main 
import (
	"io"
	"log"
	"net/http"
	"os"
	"fmt"
	"html/template"
)
const (
	UPLOAD_DIR = "./uploads/"
)
func main(){
	http.HandleFunc("/upload",uploadHandler)
	http.HandleFunc("/",indexHandler)
	err := http.ListenAndServe(":8090",nil)
	if err != nil{
		log.Fatal(err.Error())
	}
}

func indexHandler(w http.ResponseWriter,r *http.Request) {
	t,err := template.ParseFiles("index.html")
	if err != nil{
		os.Exit(5)
	}
	t.Execute(w,nil)
	return
}

func uploadHandler(w http.ResponseWriter,r *http.Request) {
	//获取文件内容 要这样获取
    file, head, err := r.FormFile("file")
    
    if err != nil {
        fmt.Println(err)
        return
    }
    defer file.Close()
    //创建文件
    fW, err := os.Create(UPLOAD_DIR + head.Filename)
    if err != nil {
        fmt.Println("文件创建失败")
        return
    }
    defer fW.Close()
    _, err = io.Copy(fW, file)
    if err != nil {
        fmt.Println("文件保存失败")
        return
    } else {
    	fmt.Println("文件保存成功")
    }
    w.Write([]byte("上传成功"))
}



```


### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```


@承鹏辉
