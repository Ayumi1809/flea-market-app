# アプリケーション名

    Flea Market App

## 環境構築

    Dockerビルド
        ・GitHubで新規リポジトリを作成
        ・ローカルリポジトリを作成
            mkdir flea-market-app
            cd flea-market-app
            git init
        ・docker-compose up -d --build
    Laravel環境構築
        ・docker-compose exec php bash
        ・composer install
        ・cp .env.example .env、環境変数を変更
        ・php artisan key:generate
        ・php artisan migrate
        ・php artisan db:seed

## 開発環境

## 仕様技術（実行環境）

## ER図

## URL
