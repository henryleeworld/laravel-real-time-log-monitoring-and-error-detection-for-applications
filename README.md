# Laravel 11 應用程式的即時日誌監測和錯誤偵測

引入 saasscaleup 的 laravel-log-alarm 套件來擴增應用程式的即時日誌監測和錯誤偵測，可以透過電子郵件和 Slack 提供即時監測、錯誤偵測和即時通知，幫助您掌握日誌。

## 使用方式
- 把整個專案複製一份到你的電腦裡，這裡指的「內容」不是只有檔案，而是指所有整個專案的歷史紀錄、分支、標籤等內容都會複製一份下來。
```sh
$ git clone
```
- 將 __.env.example__ 檔案重新命名成 __.env__，如果應用程式金鑰沒有被設定的話，你的使用者 sessions 和其他加密的資料都是不安全的！
- 當你的專案中已經有 composer.lock，可以直接執行指令以讓 Composer 安裝 composer.lock 中指定的套件及版本。
```sh
$ composer install
```
- 產⽣ Laravel 要使用的一組 32 字元長度的隨機字串 APP_KEY 並存在 .env 內。
```sh
$ php artisan key:generate
```
- 在瀏覽器中輸入已定義的路由 URL 來訪問，例如：http://127.0.0.1:8000。
- 你可以經由 `/log-monitoring` 來進行日誌監測。

----

## 畫面截圖
![](https://imgur.com/2jtdD7h.png)
> 第 5 次錯誤日誌訊息後，將發送電子郵件通知
