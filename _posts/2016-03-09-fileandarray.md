---
layout: post
title: PHP 数组&&文件操作
category: php
---

```
博文都是原创  复制请谨慎
学习记录下
```

### 筛选出ip为内网的元素，根据ip合并，count值相加（结果仍存在count内），最后按照count值逆序排序。 

```
	$items = [  
		["ip"=>"10.1.1.3", "count"=>123],  
		["ip"=>"210.33.182.1", "count"=>34],  
		["ip"=>"82.12.193.2", "count"=>2],  
		["ip"=>"10.2.124.1", "count"=>20],  
		["ip"=>"10.1.1.3", "count"=>29],  
		["ip"=>"101unknow", "count"=>345],  
		["ip"=>"10.2.124.1", "count"=>20],  
		["ip"=>"10.80.19.92", "count"=>20],  
	];  
     
	function _isPrivate($ip) { //检查内网ip是否正确  
		$i = explode('.', $ip);   
		if ($i[0] == 10) return true;   
		if ($i[0] == 172 && $i[1] > 15 && $i[1] < 32) return true;   
		if ($i[0] == 192 && $i[1] == 168) return true;   
		return false;   
	}   
   
	$newlist = array(); //新的数组  
   
	foreach( $items as $v ) {  
		if ( _isPrivate($v['ip']) ) {  
			$ip = $v['ip'];  
			//判断在新数组中是否已经存在该下标的数据  
			if (array_key_exists($ip,$newlist)) { 
				$newlist[$ip]['count']+=$v['count']; //存在累加  
			} else {  
				$newlist[$ip]['count']=$v['count']; //不存在放入数组  
			}  
		}  
	}  
	//按数字降序排列  
	array_multisort($newlist,SORT_DESC,SORT_NUMERIC); 
	print_r($newlist);
	
```

### 已知docx文件的前4个字节的内容是 504B0304（16进制），判断一个文件是否正常的docx文件? 

```
	function fileTyep ( $filename ) {  
		$f = fopen($filename,'rb');  
		$byte = fread($f,4);  
		fclose($f);  
		$code = unpack('H8',$byte);  
		if ( strtoupper($code[1] == '504B0304' )){  
			return true;  
		} else {  
			return false;  
		}  
			   
	}  
	var_dump(fileTyep('test.docx'));  

```

### 用xmlreader读取一个超过1G的大xml文件

```
	ini_set('memory_limit','-1'); //不限制  
	set_time_limit(0);//不超时  
	//生成1g文件  
	function createXml ( $fiename,$bigsize ) {  
		$f  = fopen($fiename, 'w');  
		$header = '';  
		$string = 'helloworld';  
		fwrite($f, $header);  
		   
		while ( true ) {  
			$filesize = filesize($fiename);  
			if ($filesize > $bigsize) {  
				break;  
			}  
			fwrite($f, $string);  
		}  
		$footer = "";  
		fwrite($f, $footer);  
		fclose($f);  
	}  
	   
	createXml('testxml.xml',1024*1024*1024);  
	   
	//读取xml  
	$reader = new XMLReader();  
	$reader->open('testxml.xml');   //读取xml数据  
	$i=1;  
	while ($reader->read()) {  //是否读取  
		if ($reader->nodeType == XMLReader::TEXT) {   //判断node类型  
			if($i%3){  
				echo $reader->value;    //取得node的值  
			}else{  
				echo $reader->value."" ;  
			}  
			$i++;  
		}  
	}
```

### 利用随机读写文件的原理，写一个文件分割器，能切割和合并文件

```
	class fileOperation{  
		private $filename;//文件名  
		private $n;//分割次数  
		private $filesize;//文件大小  
		private $pagesize;//计算分割后的大小  
		   
		public function __construct( $name ,$n) {  
			   
			$this->filename = $name;  
			$this->n = $n;  
		}  
		//切割文件  
		public function cutFile () {  
			$this->filesize = filesize($this->filename);  
			$this->pagesize = ceil($this->filesize/$this->n); //获取单个文件写入的文件大小  
			$f = fopen($this->filename,'rb');  
			$m = 1;//初始化  
			while( $m <= $this->n) {  
				$fr = fread( $f,$this->pagesize );//从主文件固定指针读取数据  
				$childdata = fopen($m.$this->filename,'w');//创建子文件写  
				fwrite($childdata,$fr); //往子文件写  
				$this->pagesize = ftell($f);//每次获取当前指针给全局变量  
				$m++;  
			}  
			fclose($f);  
		}  
       
		//合并文件  
		public function mergeFile ( $file,$filedata ) {  
			if (is_array($filedata)) {  
				return false;  
			}  
			$f = fopen($file,'a+');  
			foreach ( $filedata as $k=>$v ) { //循环要合并的文件  
				if ( is_array($v) ) {  
					$this->mergeFile($file,$v); //判断数组结构  
				} else {  
					if ( file_exists($v) && is_file($v) ) { //判断文件  
						$cf = fopen($v,'rb');  
						$content = '';  
						while(!feof($cf)){ //读取整个文件为止  
							$content .= fread($cf, filesize($v));//拼装读取的字符串内容  
						}  
						fclose($cf);  
						fwrite($f, $content);  
					}  
				}  
				continue;  
			}  
			fclose($f);  
		}  
	}  
	$c = new fileOperation('demo.txt',3);  
	$c->cutFile();  
	$data = array(  
		'1demo.txt',  
		'2demo.txt',  
		'3demo.txt',  
		array(  
			'4demo.txt',  
			'5demo.txt',  
		)  
	);  
	$c->mergeFile('homework.txt',$data);  
```

### 个人随笔

```
安静的等待,你若盛开,蝴蝶自来

承接高质量 网站开发 app开发 如有需要请点击About联系

```
 
作者
@承鹏辉
