
### Network

Docker network mode 有以下四種模式

- Brige (預設)
- Host
- None
- Container

```
## 啟動一個名為web 的nginx container
# docker run --rm -it -p 8000:80 --name web nginx
```

```
## build 一個簡單的curl image
# docker build -t curl curl/
```

```
## 使用--link 將兩個container 關聯起來
# docker run --rm -it --link web curl http://web
```
