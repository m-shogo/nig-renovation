# AGENTS.md

このリポジトリで作業する人と AI エージェントが最初に読むルール。

## 必ず読むもの
1. [README.md](README.md): 構成と手順
2. [docs/LESSONS.md](docs/LESSONS.md): 過去の失敗と対策。**同じ失敗をしたら、ここに追記する。**
3. [docs/DB_LOG.md](docs/DB_LOG.md): どの `.wpress` が最新か

## 守ること
- サイトは `http://localhost:3080/nig/`。サーバーと同じく `/nig/` 配下で動かす。
- 編集してよいのは `nig/`(テーマ・css・js)、`docker/`、`scripts/`、`docs/`、`acf/`。
- WordPress 本体やプラグインのファイルは直接編集しない(コンテナの volume 上にあり、復元で消える)。
- `.wpress`・プラグインの zip・`.env`・`wp-config.php` は commit しない。リポジトリは公開設定。
- コンテナ内の処理はスクリプトにして `docker compose exec -u www-data wordpress bash /scripts/...` で実行する(Windows の改行問題を避けるため)。
- WP-CLI は `-u www-data` で実行する(root だとファイルの所有者が変わる)。
- DB を変えたら、作業の最後に `export.sh` → Google Drive → `DB_LOG.md` を commit する。
- `wp-content/uploads/.htaccess`(本番の uploads を参照する検証サーバー用の設定)は消さない・書き換えない。
- バージョン(WordPress・PHP・MariaDB・All-in-One WP Migration)を変えるときは README の表と LESSONS を更新する。
