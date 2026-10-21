# PLAN(リニューアルの方針)

このリポジトリでやりたいことと、決めたこと・未決のことの記録。方針が決まったら更新する。

## ゴール

新デザインを、今動いているサイトの構造に当てはめる。

- 新デザインの実装(HTML・CSS・JS): `nig/wp-content/themes/nig`(構築済み。CSS の調整はほぼ無い想定)
- 当てはめ先(今の構造・データ): `nig/wp-content/themes/samplemain`
- TOP は `nig` の `original-frontPage.php` と ACF をそのまま持ってきてよい
- header・footer も `nig` のものを持ってくる。ただし **ACF なし** で、今の構造(WordPress のメニュー・WPML)で動かす
- CSS は `nig` のものを全部持ってくる想定

## デザイン・仕様

- PC デザイン: https://www.figma.com/design/0BHOnrnHSBSx5QTvh94RgY/nig-renovation?node-id=15626-29173
- SP デザイン: https://www.figma.com/design/0BHOnrnHSBSx5QTvh94RgY/nig-renovation?node-id=15626-24421
- 仕様書: https://www.figma.com/design/bifb2nXzhIe9zIzpHEOXwo/nig-renovation%E4%BB%95%E6%A7%98%E6%9B%B8?node-id=15407-3

## 2 つのテーマの違い(2026-10-21 調査)

| 項目 | samplemain(今) | nig(新デザイン) |
|---|---|---|
| 多言語 | WPML(`icl_get_languages`、`header-ja.php`・`footer-ja.php` で言語ごとに分ける) | Bogo(`get_locale`)。1 つの header で両言語 |
| グローバルナビ | WordPress のメニュー `hdmenu`(英)・`hdmenu_ja`(日) | フロントページの ACF リピーター(`common-menu-01`・`02` など) |
| CSS | WordPress 外の `/nig/css/common.css`・`navigation.css`・`add_style.css`・`index.css` | テーマ内の `css/style.css`(SCSS から生成。`_head_contents.php` で head にインライン出力) |
| jQuery | 1.8.2(Google CDN) | 3.7.1(テーマ内) |
| TOP | `index-en.php`・`index-ja.php`。CPT `top_slider`・`top_pickup`・`top_important` | `original-frontPage.php`。CPT `highlights`・`interviews`、タクソノミー `highlights_cat` と ACF |
| 投稿データ | `post` 1790 件(カテゴリで分類)、`page` 626 件 | 上記 CPT 前提。**今の DB には存在しない** |

## 未決(相談中)

1. テーマの持ち方: samplemain を直接編集するか、子テーマにするか、複製するか
2. CSS: 旧 CSS を残して上書きするか、差し替えるか
3. TOP の一覧データ: `highlights`・`interviews` を今の `post` とカテゴリに読み替えるか、CPT を作ってデータを移すか
4. header・footer のメニュー: WordPress のメニューから新デザインの HTML を出す方法(Walker)でよいか

## 決めたこと

(まだ無い)
