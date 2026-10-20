#!/usr/bin/env bash
# 使い方: docker compose exec -u www-data wordpress bash /scripts/restore.sh [ファイル名.wpress]
# 引数なしなら data/backups/ の中で一番新しい .wpress を復元する。
set -euo pipefail

BACKUP_DIR=/var/www/html/nig/wp-content/ai1wm-backups
EXT_ZIP=/data/plugins/all-in-one-wp-migration-unlimited-extension.zip

if [ "$(id -u)" = "0" ]; then
  echo "root ではなく -u www-data を付けて実行してください" >&2
  exit 1
fi

cd /var/www/html/nig

file="${1:-}"
if [ -z "$file" ]; then
  file="$(ls -t "$BACKUP_DIR"/*.wpress 2>/dev/null | head -n 1 || true)"
  [ -n "$file" ] || { echo "data/backups/ に .wpress がありません" >&2; exit 1; }
fi
file="$(basename "$file")"
[ -f "$BACKUP_DIR/$file" ] || { echo "data/backups/$file がありません" >&2; exit 1; }
[ -f "$EXT_ZIP" ] || { echo "data/plugins/ に unlimited extension の zip がありません" >&2; exit 1; }

echo "==> WordPress 本体 ($WP_VERSION)"
if ! wp core is-installed 2>/dev/null; then
  wp core install --url="$WP_URL" --title=nig --admin_user=admin --admin_password=admin \
    --admin_email=admin@example.com --skip-email
fi
if [ "$(wp core version)" != "$WP_VERSION" ]; then
  wp core update --version="$WP_VERSION" --force
fi

echo "==> All-in-One WP Migration ($AI1WM_VERSION) + Unlimited Extension"
wp plugin install all-in-one-wp-migration --version="$AI1WM_VERSION" --force --activate
wp plugin install "$EXT_ZIP" --force --activate

echo "==> 復元: $file"
wp ai1wm restore "$file" --yes

echo "==> 後処理"
cat > .htaccess <<'EOF'
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /nig/
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /nig/index.php [L]
</IfModule>
# END WordPress
EOF
wp rewrite flush
wp cache flush

echo
echo "完了: $WP_URL/"
echo "ログインは検証サーバーと同じアカウントです: $WP_URL/wp-admin/"
echo "テーマが上書きされているので、ホスト側で git status / git diff を確認してください。"
