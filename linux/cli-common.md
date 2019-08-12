# nc proxy server

```
# 開一個命名管道
mkfifo x

# 用nc 當 proxy
nc -k -l 127.0.0.1 8000 <x | nc [targt host]:[port] >x

# 用curl 指定nc proxy
curl -x 127.0.0.1:8000 [target host]:[port]
```

# ssh config 用nc 當proxy

[參考](https://shazi.info/%E5%88%A9%E7%94%A8ssh-proxycommand%E5%8F%8Anc%E5%81%9A%E8%B7%B3%E6%9D%BF%E7%9A%84%E9%80%A3%E7%B7%9A%E6%87%89%E7%94%A8/)
```
# 透過跳板(ssh server) 連其他server
# .ssh/config

Host alias
    Hostname real.host
    User username
    ForwardAgent yes
    Port 22
    ProxyCommand ssh [user]@[ssh server] -p [ssh server port] nc %h %p

# 備註: 可以串多台
```

```
# 透過ssh server 當http proxy
# .ssh/config

ssh -fND 127.0.0.1:8000 [ssh server]

ssh -o ProxyCommand='nc -X 5 -x 127.0.0.1:8000 %h %p' [ssh server]
```
