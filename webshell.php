<?php

/**
 * websocket服务器（使用swoole）
 * 使用ssh登录服务器
 */
class Ws{
    private $shell;
    private $connection;
    private $isConnection;

    private $ws;
    public function __construct(){
        //监听0.0.0.0:8081端口
        $this->ws = new Swoole\WebSocket\Server('0.0.0.0', 8081);

        $this->ws->on("open", [$this, "onOpen"]);
        $this->ws->on("message", [$this, "onMessage"]);
        $this->ws->on("close", [$this, "onClose"]);

        $this->ws->start();
    }

    //监听WebSocket连接打开事件
    public function onOpen($ws, $request){
        var_dump($request->fd, $request->server);
    }

    //监听WebSocket消息事件
    public function onMessage($ws, $frame){
        $data = json_decode($frame->data, true);
        switch(key($data)){
            case "data":    //输入命令
                fwrite($this->shell[$frame->fd], $data['data']);
                usleep(800);
                while($line = fgets($this->shell[$frame->fd])){
                    $ws->push($frame->fd, $line);
                }
            break;
            case "auth":    //登录
                if($this->loginSSH($data["auth"], $frame)){
                    $ws->push($frame->fd, "连接中...");
                    while($line = fgets($this->shell[$frame->fd])){
                        $ws->push($frame->fd, $line);
                    }
                } else {
                    $ws->push($frame->fd, "登录失败");
                }
            break;
            default:
                //清理空白行
                if($this->isConnection[$frame->fd]){
                    while($line = fgets($this->shell[$frame->fd])){
                        $ws->push($frame->fd, $line);
                    }
                }
            break;
        }
    }

    //监听WebSocket连接关闭事件
    public function onClose($ws, $fd){
        $this->isConnection[$fd] = false;
        echo "client-{$fd} is closed\n";
    }

    //ssh登录
    public function loginSSH($auth, $frame){
        //通过SSH连接服务器
        $this->connection[$frame->fd] = ssh2_connect($auth['server'], $auth['port']);
        //验证身份（登录）
        if(ssh2_auth_password($this->connection[$frame->fd], $auth['user'], $auth['password'])){
            //使用流的方式打开shell
            $this->shell[$frame->fd] = ssh2_shell($this->connection[$frame->fd], 'xterm', null, 80, 24, SSH2_TERM_UNIT_CHARS);
            sleep(1);   //延迟执行一秒等待服务器
            $this->isConnection[$frame->fd] = true;
            return true;
        } else {
            return false;
        }
    }
}

new ws();




<!--使用"npm install xterm"安装xterm-->
<!doctype html>
  <html>
    <head>
      <link rel="stylesheet" href="node_modules/xterm/dist/xterm.css" />
      <script src="node_modules/xterm/dist/xterm.js"></script>
      <script src="node_modules/xterm/dist/addons/attach/attach.js"></script>
      <script src="node_modules/xterm/dist/addons/fit/fit.js"></script>
      <style>
      body {font-family: Arial, Helvetica, sans-serif;}

      input[type=text], input[type=password], input[type=number] {
          width: 100%;
          padding: 12px 20px;
          margin: 8px 0;
          display: inline-block;
          border: 1px solid #ccc;
          box-sizing: border-box;
      }

      button {
          background-color: #4CAF50;
          color: white;
          padding: 14px 20px;
          margin: 8px 0;
          border: none;
          cursor: pointer;
          width: 100%;
      }

      button:hover {
          opacity: 0.8;
      }

      .serverbox {
          padding: 16px;
          border: 3px solid #f1f1f1;
          width: 25%;
          position: absolute;
          top: 15%;
          left: 37%;
      }
      </style>
    </head>
    <body>

      <div id="terminal" style="width:100%; height:90vh;visibility:hidden"></div>
      
      <script>
        var resizeInterval;
        //连接websocket服务器
        var wSocket = new WebSocket("ws:服务器地址:8081");
        Terminal.applyAddon(attach);  // Apply the `attach` addon
        Terminal.applyAddon(fit);  //Apply the `fit` addon
        var term = new Terminal({
				  cols: 80,
				  rows: 24
        });
        term.open(document.getElementById('terminal'));

        function ConnectServer(){
          //ssh登录服务器需要的信息
          var server = "";      //服务器地址
          var port = 22;      //ssh端口
          var user = "";      //账号
          var password = "";      //密码
          
          document.getElementById("terminal").style.visibility="visible";
          var dataSend = {"auth":
                            {
                            "server":server, //document.getElementById("server").value,
                            "port":port, //document.getElementById("port").value,
                            "user":user, //document.getElementById("user").value,
                            "password":password, //document.getElementById("password").value
                            }
                          };
          wSocket.send(JSON.stringify(dataSend));

          term.fit();
          term.focus();
        }       

        wSocket.onopen = function (event) {
          console.log("打开连接");
          term.attach(wSocket,false,false);
          window.setInterval(function(){
            wSocket.send(JSON.stringify({"refresh":""}));
          }, 700);
        };

        wSocket.onerror = function (event){
          term.detach(wSocket);
          alert("Connection Closed");
        }        
        
        term.on('data', function (data) {
          var dataSend = {"data":data};
          wSocket.send(JSON.stringify(dataSend));
          //Xtermjs with attach dont print zero, so i force. Need to fix it :(
          if (data=="0"){
            term.write(data);
          }
        })
        
        //Execute resize with a timeout
        window.onresize = function() {
          clearTimeout(resizeInterval);
          resizeInterval = setTimeout(resize, 400);
        }
        // Recalculates the terminal Columns / Rows and sends new size to SSH server + xtermjs
        function resize() {
          if (term) {
            term.fit()
          }
        }

    window.onload=ConnectServer;
      </script>
    </body>
  </html>
