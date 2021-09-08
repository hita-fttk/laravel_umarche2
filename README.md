## udemy Laravel講座
## ダウンロード方法
git clone
git clone https://github.com/hita-fttk/laravel_umarche2

git clone　ブランチを指定してダウンロードする場合
git clone -b ブランチ名https://github.com/hita-fttk/laravel_umarche2

もしくはZIPファイルでダウンロードしてください。

## インストール方法



## インストール後の実施事項


画像のダミーデータは
public/imagesフォルダ内に
sample1.jpg~sample6.jpgとして保存しています。

php artisan storage:linkでstorageフォルダにリンク後、
storage/app/public/productsフォルダ内に保存すると表示されます。 
(productsフォルダがない場合は作成をしてください)

ショップの画像も表示する場合は
storage/app/public/shopsフォルダを作成し画像を保存してください。

