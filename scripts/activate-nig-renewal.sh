#!/usr/bin/env bash
# samplemain のメニュー割り当てを nig-renewal に写して有効にする。
# 使い方: docker compose exec -u www-data wordpress bash /scripts/activate-nig-renewal.sh
set -euo pipefail

if [ "$(id -u)" = "0" ]; then
  echo "root ではなく -u www-data を付けて実行してください" >&2
  exit 1
fi

cd /var/www/html/nig

wp theme is-installed nig-renewal
wp eval '
$mods = get_option("theme_mods_samplemain");
if (!is_array($mods)) { fwrite(STDERR, "theme_mods_samplemain がありません\n"); exit(1); }
update_option("theme_mods_nig-renewal", $mods);
echo "copied theme_mods\n";
'
wp theme activate nig-renewal
echo "stylesheet=$(wp option get stylesheet)"
wp eval 'foreach ((array) get_nav_menu_locations() as $loc => $id) { echo "$loc=$id\n"; }'
