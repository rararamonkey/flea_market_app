# Flea Market App

Laravelを使用して開発したフリマアプリです。

ユーザーは会員登録・ログイン後に商品を出品、購入できます。
また、いいね機能、コメント機能、マイリスト機能、プロフィール編集機能を実装しています。

決済機能にはStripeを使用し、メール認証にはLaravel Fortifyを使用しています。

## 環境構築

### Dockerビルド

```bash
git clone git@github.com:rararamonkey/flea_market_app.git
cd flea_market_app
docker compose up -d --build
```

### Laravel環境構築

```bash
docker compose exec php bash
composer install
cp .env.example .env
```

### .env 設定

`.env` のDB接続・メール・Stripe設定を以下に設定してください。

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="test@example.com"
MAIL_FROM_NAME="${APP_NAME}"

STRIPE_KEY=pk_test_各自のStripe公開キー
STRIPE_SECRET=sk_test_各自のStripeシークレットキー
```

### アプリケーションキー作成・マイグレーション

```bash
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan config:clear
```

## 使用技術

* PHP 8.1
* Laravel 8
* MySQL 8.0
* Nginx
* Docker
* Laravel Fortify
* Stripe
* MailHog
* Laravel Dusk
  
## URL

* 開発環境：http://localhost
* phpMyAdmin：http://localhost:8080
* MailHog：http://localhost:8025

## テストアカウント

以下のテストアカウントは認証済みです。
そのままログインして動作確認できます。

### 出品者ユーザー
- メールアドレス：seller@test.com
- パスワード：password

### 購入者ユーザー
- メールアドレス：buyer@test.com
- パスワード：password

本アプリに管理者機能は実装しておりません。
一般ユーザーのみ利用可能です。

## メール認証

本アプリでは Laravel Fortify のメール認証機能を使用しています。

メール送信確認には MailHog を使用しています。

会員登録後、認証メールが MailHog に送信されます。

以下のURLから MailHog を開き、受信したメール内の認証リンクをクリックしてください。

MailHog：http://localhost:8025

## Stripe

Stripe決済を使用しています。

Stripe決済機能を利用するため、Stripeダッシュボードから取得したテスト用APIキーを `.env` に設定してください。

### APIキー取得方法

1. Stripe Dashboard にログイン
2. テストモードをONにする
3. 「開発者」→「APIキー」を開く
4. 公開可能キー（pk_test_...）とシークレットキー（sk_test_...）を取得
5. `.env` に設定

```env
STRIPE_KEY=pk_test_各自のStripe公開キー
STRIPE_SECRET=sk_test_各自のStripeシークレットキー
```

### 決済確認について

カード支払いのテストカード番号は以下を使用してください。

```
4242 4242 4242 4242
有効期限：未来日
CVC：任意の3桁
```

コンビニ支払いを選択した場合、Stripeの支払い案内画面へ遷移します。

支払い案内画面まで進むことで購入処理が実行され、商品一覧では対象商品に「Sold」が表示されます。

決済画面からアプリへ戻る場合は、手動で以下へアクセスしてください。

```
http://localhost
```

## 機能一覧

* 会員登録
* ログイン
* ログアウト
* メール認証
* 商品一覧表示
* 商品詳細表示
* 商品検索
* マイリスト表示
* いいね登録・解除
* コメント投稿
* 商品購入
* 支払い方法選択
* 配送先変更
* プロフィール編集
* 商品出品

## 備考

テストアカウントは認証済みのため、そのままログインして動作確認できます。

メール認証機能を確認する場合は、新規会員登録を行い、MailHogで認証メールを確認してください。

## ER図

![ER図](./images/er.png)

## テスト実行

本アプリでは、FeatureテストとLaravel Duskによるブラウザテストを実装しています。

### Featureテスト

以下のコマンドでFeatureテストを実行できます。

```bash
php artisan test
```

### ブラウザテスト（Laravel Dusk）

Duskテストでは、購入画面で支払い方法を選択した際に、小計欄へ選択内容が反映されることを確認しています。

Duskを実行する前に、Laravelの開発サーバーを起動してください。

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

別ターミナルで以下を実行します。

```bash
php artisan dusk
```
