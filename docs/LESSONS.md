# LESSONS(失敗と対策の記録)

同じミスを繰り返さないための記録。新しい失敗をしたら、ここに追記してから作業を続ける。
書き方: 何が起きたか / 原因 / 対策(仕組みで防いだか、ルールで防ぐか)。

## 環境構築(2026-10-20)

### All-in-One WP Migration の復元が「Unlimited Extension is out of date」で止まる
- 原因: 本体 7.112 は Extension 2.88 以上が必要。手元の zip は 2.83。
- 対策: 本体を 7.111 に固定(`AI1WM_VERSION`)。Extension を更新したら `.env` で本体のバージョンも上げる。
  対応表は `https://plugins.svn.wordpress.org/all-in-one-wp-migration/tags/<版>/lib/model/class-ai1wm-extensions.php` の `requires` で確認できる。

### `.wpress` を入れても CSS・JS・画像が出ない
- 原因: `/nig/css`・`/nig/js`・`/nig/images` などは WordPress の外にあり、`.wpress` に含まれない。
- 対策: `css`・`js` は `nig/` で Git 管理し、コンテナにマウントする。`images`・`pdf` などは Apache で検証サーバーへ転送する。
  新しく WordPress 外のフォルダを使う場合は、Git に入れるか転送対象に足すかを決めて `apache.conf` か `docker-compose.yml` に反映する。

### uploads の画像が出ない(.wpress にメディアが入っていない)
- 原因: 検証サーバーは uploads を持たず、`wp-content/uploads/.htaccess` で本番へ転送している。そのため書き出しにもメディアが入らない。
  さらにこの `.htaccess` の `RewriteEngine on` が上位ディレクトリの転送ルールを打ち消していた。
- 対策: 転送ルールは `.htaccess` ではなく Apache の仮想ホスト設定(`docker/wordpress/apache.conf`)に書く。
  `uploads/.htaccess` は検証サーバー用の大事なファイルなので消さない・書き換えない。

### 固定ページが 404 になる
- 原因: Docker イメージ既定の `.htaccess` が `RewriteBase /` のまま。WP-CLI の `rewrite flush --hard` は Apache の設定を検出できず書き直さない。
- 対策: `restore.sh` で `/nig/` 用の `.htaccess` を毎回書き出す。

### ポート 3000 / 3001 が使えない
- 原因: 別案件の browser-sync(既定で 3000 / 3001)と衝突。
- 対策: nig は 3080、phpMyAdmin は 8081 にした。
  **ポートは復元前に決める。** 復元時に本文中の URL にポート番号が埋め込まれるため、後から変えるとリンクが壊れる。変えた場合は復元し直す。

### Windows の PowerShell からコンテナへ複数行のコマンドを渡すと壊れる
- 原因: 改行が CRLF になり、`.htaccess\r` のような余計なファイルができたり、`/dev/null\r` でエラーになったりする。
- 対策: コンテナ内の処理は `scripts/*.sh` に書いて `bash /scripts/xxx.sh` で実行する。`.gitattributes` で全テキストを LF に統一している。
  旧サーバーのコピーも CRLF になっていた(内容は同じ)。改行の違いを差分と勘違いしないこと。

### 書き出したファイル名の時刻が 9 時間ずれる
- 原因: コンテナが UTC だった。
- 対策: `docker-compose.yml` で `TZ: Asia/Tokyo` を設定。

## 環境構築(2026-10-21 Mac)

### 初回の `restore.sh` が「'ai1wm' is not a registered wp command」で止まる
- 原因: `wp plugin install --activate` が「already active」と出したのに、DB 上は有効になっていなかった。
- 対策: `restore.sh` で install の後に `wp plugin activate` を明示的に実行する。止まった場合は再実行すればよい。

### `/nig/images/` の画像が出ない(.wpress に入っていない・検証サーバーは Basic 認証)
- 原因: `/nig/images/` は WordPress の外なので `.wpress` に含まれない。転送先の検証サーバーは Basic 認証で 401 を返す。
- 対策: 検証サーバーへの転送をやめた。`nig/images/` は各自で置き、`nig/images/common` だけ Git 管理する。
  `nig/images/` はマウントしないとコンテナから見えない(`docker-compose.yml`)。

### uploads をフォルダごとマウントすると復元したファイルが隠れる
- 原因: `wp-content/uploads/` には `.wpress` から復元した `.htaccess`・`2026/`・`cache/` がある。
- 対策: `nig/wp-content/uploads/` は年ごとにマウントする。新しい年の画像を置いたら `docker-compose.yml` に 1 行足す。
  Git に入れる uploads 画像は `.gitignore` に 1 ファイルずつ列挙する。

## 運用ルール(破綻を防ぐため)

- DB は Git に入れない。正本はローカル。受け渡しは `export.sh` → Google Drive → `DB_LOG.md` の順。
- **書き出して push していない DB の変更があるまま、別の場所で作業を始めない。** 復元すると手元の DB は丸ごと置き換わり、書き出していない変更は消える。両方で編集した場合、片方の変更は手で入れ直すしかない。
- 復元はテーマ(`nig/wp-content/themes/samplemain`)も上書きする。復元前に commit しておき、復元後は `git status` で確認する。
- `.wpress` を検証サーバーに入れると、ユーザーも含めて全部置き換わる。入れる前に検証サーバー側で書き出しておく。
- 旧サーバーのコピー(`C:\htdocs\_nig-renovation-old`)は参照専用。`wp-config.php` を含むので Git に入れない。
