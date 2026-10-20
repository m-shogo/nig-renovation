# nig-renovation

国立遺伝学研究所サイト(`/nig/`)のローカル開発環境。Docker で検証サーバーと同じ構成を再現し、All-in-One WP Migration の `.wpress` でデータを入れる。
Windows・Mac(Apple Silicon 含む)で同じ手順で動く。

- サイト: http://localhost:3080/nig/
- 管理画面: http://localhost:3080/nig/wp-admin/ (ログインは検証サーバーと同じアカウント)
- phpMyAdmin: http://localhost:8081/

作業ルールは [AGENTS.md](AGENTS.md)、過去の失敗と対策は [docs/LESSONS.md](docs/LESSONS.md) を必ず読むこと。

## 構成

| 項目 | バージョン | 備考 |
|---|---|---|
| WordPress | 6.9.5 | 検証サーバーと同じ。`restore.sh` が固定する |
| PHP | 8.3 | 検証サーバーは 8.3.35 |
| MariaDB | 10.11 | 検証サーバーは 10.11.18 |
| All-in-One WP Migration | 7.111 | 7.112 以降は Unlimited Extension 2.88 以上が必要 |
| Unlimited Extension | 2.83 | `data/plugins/` に置く(Git 管理外) |

```
nig-renovation/
├─ docker-compose.yml
├─ docker/wordpress/        # Dockerfile, php.ini, Apache 設定(ローカルに無いファイルの転送)
├─ scripts/
│   ├─ restore.sh           # .wpress から環境を作る/戻す
│   └─ export.sh            # 今の DB とファイルを .wpress に書き出す
├─ nig/                     # サーバーの /nig/ と同じ構造(Git 管理するものだけ)
│   ├─ css/  js/            # WordPress 外の静的ファイル
│   └─ wp-content/themes/
│       ├─ samplemain/      # 現在の有効テーマ
│       └─ nig/             # リニューアル用テーマ(.wpress には入っていない。Git が正本)
├─ acf/                     # ACF フィールド定義の JSON(参考保管)
├─ data/                    # Git 管理外。Google Drive で共有
│   ├─ backups/             # .wpress(コンテナの ai1wm-backups と同じフォルダ)
│   └─ plugins/             # all-in-one-wp-migration-unlimited-extension.zip
└─ docs/
    ├─ DB_LOG.md            # どの .wpress が最新かの記録
    └─ LESSONS.md           # 失敗と対策の記録
```

### Git に入れるもの・入れないもの

| 入れる | 入れない(理由) |
|---|---|
| Docker 設定、スクリプト、手順書 | `.wpress`(500MB 超。GitHub は 1 ファイル 100MB まで。DB はマージできない) |
| テーマ `samplemain`・`nig` | Unlimited Extension の zip(有料プラグイン。リポジトリが公開のため) |
| `nig/css`、`nig/js` | `images`・`pdf` など(合計 4GB 超。検証サーバーから表示する) |
| ACF の JSON | `wp-config.php`、`.env`(パスワード) |

### ローカルに無いファイルの扱い

`.wpress` にはメディアが入っていない(検証サーバー自体が uploads を本番から表示している)。
ローカルに実ファイルが無い場合だけ、Apache が次へ 302 転送する(`docker/wordpress/apache.conf`)。

- `/nig/wp-content/uploads/...` → 本番 `https://www.nig.ac.jp`
- `/nig/images/`・`pdf/`・`download/`・ファビコンなど → 検証サーバー `https://nig-new.codiadev8.site`

ローカルに同じパスのファイルを置けば、そちらが優先される。

## 初回セットアップ(Windows / Mac 共通)

前提: Docker Desktop と Git が入っていること。

1. clone する

   ```bash
   git clone https://github.com/m-shogo/nig-renovation.git
   cd nig-renovation
   ```

2. Google Drive の共有フォルダから次の 2 つを取ってきて置く
   - 最新の `.wpress`(`docs/DB_LOG.md` の一番下の行)→ `data/backups/`
   - `all-in-one-wp-migration-unlimited-extension.zip` → `data/plugins/`

3. 起動して復元する(初回はイメージ作成と復元で 5〜10 分)

   ```bash
   docker compose up -d --build
   docker compose exec -u www-data wordpress bash /scripts/restore.sh
   ```

4. http://localhost:3080/nig/ を開いて確認する。

## 毎日の作業の流れ

DB は家と会社で同時に編集できない。**必ず一方通行で受け渡す。**

### 作業を始めるとき

```bash
git pull
```

`docs/DB_LOG.md` の一番下のファイルが手元で最後に復元/書き出ししたものと違う場合:

1. Google Drive からそのファイルを `data/backups/` に置く
2. `docker compose exec -u www-data wordpress bash /scripts/restore.sh <ファイル名>`
3. `git status` でテーマが変わっていないか確認する(復元はテーマも上書きする)

### 作業を終えるとき

管理画面で内容を変えた場合(テーマだけの変更なら 1 は不要):

1. 書き出す(場所は `home` / `office` / `mac` など)

   ```bash
   docker compose exec -u www-data wordpress bash /scripts/export.sh home
   ```

2. 作られた `data/backups/nig-YYYYMMDD-HHMM-home.wpress` を Google Drive にアップロードする
3. commit して push する

   ```bash
   git add docs/DB_LOG.md nig
   git commit -m "..."
   git push
   ```

## よく使うコマンド

```bash
docker compose up -d          # 起動
docker compose stop           # 停止(データは残る)
docker compose exec -u www-data wordpress wp plugin list   # WP-CLI
docker compose logs -f wordpress                          # ログ
docker compose exec wordpress tail -f /var/www/html/nig/wp-content/debug.log
```

全部作り直す(DB と WordPress 本体を消す。`nig/` と `data/` は消えない):

```bash
docker compose down -v
docker compose up -d --build
docker compose exec -u www-data wordpress bash /scripts/restore.sh
```
