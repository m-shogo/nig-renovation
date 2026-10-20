#!/usr/bin/env bash
# 使い方: docker compose exec -u www-data wordpress bash /scripts/export.sh <場所: home|office|mac など>
# data/backups/nig-YYYYMMDD-HHMM-<場所>.wpress を作り、docs/DB_LOG.md に追記する。
set -euo pipefail

BACKUP_DIR=/var/www/html/nig/wp-content/ai1wm-backups
place="${1:-}"
[ -n "$place" ] || { echo "場所を指定してください (例: home / office / mac)" >&2; exit 1; }

if [ "$(id -u)" = "0" ]; then
  echo "root ではなく -u www-data を付けて実行してください" >&2
  exit 1
fi

cd /var/www/html/nig

before="$(ls -t "$BACKUP_DIR"/*.wpress 2>/dev/null | head -n 1 || true)"
wp ai1wm backup --exclude-cache --exclude-spam-comments
created="$(ls -t "$BACKUP_DIR"/*.wpress | head -n 1)"
if [ "$created" = "$before" ]; then
  echo "新しい .wpress が見つかりません" >&2
  exit 1
fi

name="nig-$(date +%Y%m%d-%H%M)-${place}.wpress"
mv "$created" "$BACKUP_DIR/$name"

printf '| %s | %s | %s |\n' "$(date '+%Y-%m-%d %H:%M')" "$place" "$name" >> /docs/DB_LOG.md

echo
echo "作成: data/backups/$name"
echo "次の作業:"
echo "  1. data/backups/$name を Google Drive の共有フォルダへアップロード"
echo "  2. git add docs/DB_LOG.md nig && git commit && git push"
