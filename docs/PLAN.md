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

## 決めたこと(2026-10-21)

1. **テーマ**: `samplemain` を複製して別テーマにする。`samplemain` は今の状態のまま残し、比較・切り戻し用にする。
2. **header・footer のメニュー**: ACF は使わない。今の WordPress のメニュー `hdmenu`(英)・`hdmenu_ja`(日)を Walker で読み、`nig` と同じクラスの HTML を出す。
   言語切り替えは Bogo ではなく WPML の関数で書き直す。`header.php`・`header-ja.php` は同じ部品を呼ぶ。
3. **CSS**: `nig` の CSS に差し替える。旧 `/nig/css/common.css`・`navigation.css`・`index.css` は読まない。
   DB の本文(投稿・固定ページ)で使われている旧クラスの指定だけを、本文エリアに限定して移植する。
4. **TOP の一覧データ**: `original-frontPage.php` の `highlights`・`interviews` の検索条件を、今の `post`・カテゴリ・`top_slider`・`top_pickup`・`top_important` に読み替える。データ移行はしない。
   TOP の ACF は `acf/acf-new.json` を取り込んで使う。

## 未決

- 複製したテーマの名前(フォルダ名)
- 作業の順番
