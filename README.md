# NExT Hide Private Post Prefix

ログインユーザーに対しても、非公開記事をフロントエンドのすべてのクエリから除外する WordPress プラグインです。

## 概要

WordPress のデフォルト動作では、ログインユーザーには非公開記事が表示されます。このプラグインを有効化すると、ログイン済みのユーザーにも未ログインユーザーと同じ挙動が適用されます。

メインループだけでなく、Query Loop ブロックや Advanced Query Loop などのセカンダリクエリにも適用されます。

## 動作仕様

| 場所 | 未ログイン | ログイン済み（本プラグイン有効時） |
|------|-----------|----------------------------------|
| 投稿一覧（ホーム） | 非表示 | 非表示 |
| アーカイブページ | 非表示 | 非表示 |
| 検索結果 | 非表示 | 非表示 |
| Query Loop ブロック | 非表示 | 非表示 |
| Advanced Query Loop | 非表示 | 非表示 |
| 記事の直接URL | WordPress 標準 | WordPress 標準（閲覧可能） |
| 管理画面 | — | 影響なし（通常通り表示） |
| REST API | — | 影響なし |

## インストール

1. プラグインディレクトリ (`wp-content/plugins/`) に `next-Hide-Private-Post-Prefix` フォルダごと配置する
2. WordPress 管理画面 > プラグイン から「NExT Hide Private Post Prefix」を有効化する

## 要件

- WordPress 6.1 以上
- PHP 7.0 以上

## ライセンス

GPL-2.0-or-later — 詳細は [GNU GPL v2.0](https://www.gnu.org/licenses/gpl-2.0.html) を参照してください。
