# PLAN(リニューアルの方針)

このリポジトリでやりたいことと、決めたこと・未決のことの記録。方針が決まったら更新する。
**実装は、ここに書いた順番以外で始めない。**

## ゴール

新デザインを、今動いているサイトの構造・データに当てはめる。`nig` テーマをそのまま有効にはしない。

- 新デザインの実装(HTML・CSS・JS): `nig/wp-content/themes/nig`(構築済み。CSS の調整はほぼ無い想定)
- 当てはめ先(今の構造・データ): `nig/wp-content/themes/samplemain`
- TOP は `nig` の `original-frontPage.php` と **TOP 用 ACF だけ** 持ってきてよい
- header・footer も `nig` のものを持ってくる。メニューは **ACF なし**(今の WordPress メニュー + WPML)
- CSS は `nig` のものを全部持ってくる想定(旧グローバル CSS は読まない)

## デザイン・仕様

- PC デザイン: https://www.figma.com/design/0BHOnrnHSBSx5QTvh94RgY/nig-renovation?node-id=15626-29173
- SP デザイン: https://www.figma.com/design/0BHOnrnHSBSx5QTvh94RgY/nig-renovation?node-id=15626-24421
- 仕様書: https://www.figma.com/design/bifb2nXzhIe9zIzpHEOXwo/nig-renovation%E4%BB%95%E6%A7%98%E6%9B%B8?node-id=15407-3

## 決めたこと(2026-10-21)

1. **テーマ**: `samplemain` を複製して別テーマにする。`samplemain` は今の状態のまま残し、比較・切り戻し用にする。
2. **header・footer のメニュー**: ACF は使わない。今の WordPress のメニュー `hdmenu`(英)・`hdmenu_ja`(日)を Walker で読み、`nig` と同じクラスの HTML を出す。
   言語切り替えは Bogo ではなく WPML の関数で書き直す。`header.php`・`header-ja.php` は同じ部品を呼ぶ。
3. **CSS**: `nig` の CSS に差し替える。旧 `/nig/css/common.css`・`navigation.css`・`index.css` は読まない。
   DB の本文(投稿・固定ページ)で使われている旧クラスの指定だけを、本文エリアに限定して移植する。
4. **TOP の一覧データ**: `original-frontPage.php` の `highlights`・`interviews` の検索条件を、今の `post`・カテゴリ・既存 CPT に読み替える。データ移行はしない。
   TOP の ACF は `acf/acf-new.json` から **TOP 関連グループだけ** 取り込む。

## 2 つのテーマの違い(調査)

| 項目 | samplemain(今・有効) | nig(新デザイン・未使用) |
|---|---|---|
| 多言語 | WPML。`header-ja.php` / `footer-ja.php`。`icl_get_languages` | Bogo 前提。`get_locale()`。1 つの header |
| グローバルナビ | メニュー `hdmenu`(136) / `hdmenu_ja`(165)。第1階層は About NIG / Research / PhD / RIC / Bioresources / Outreach / Career | フロントページ ACF(`common-menu-01` 対象者4件 + `common-menu-02` メガメニュー)。第1階層は researchers / students / public / business |
| CSS | WordPress 外 `/nig/css/*.css`。header が直接 link | テーマ内 `css/style.css`(約337KB、SCSS)。`_head_contents.php` で head にインライン出力。Meyer reset が全体に効く |
| JS / jQuery | 1.8.2(CDN) + `/nig/js/globalnavi.js` 等。`wp_head()` を呼んでいない | 3.7.1 + `js/common.js`(header の DOM 前提) + Swiper。`wp_head()` / `wp_footer()` あり |
| TOP | `show_on_front=posts`。`index.php` → `index-en.php` / `index-ja.php`。CPT `top_slider`(17) / `top_pickup`(18) / `top_important`(1) | 固定フロントページ + テンプレート `original-frontPage.php`。CPT `highlights` / `interviews`(今の DB に無い) |
| 下層ページ | `#contentsWrapper` / `#leftSlot`(サイドメニュー) / `#rightSlot` / `.box`。パン屑 `#breadcrumb`。色クラス `bgColor*` | `#global_contents` / `.content_inner` / `.gc_main` / `.block-editor_wrap`。サイドバーは別テンプレ |
| 画像 | `/nig/images/common/`(サイト外) | テーマ内 `themes/nig/images/common`・`images/home`(ロゴ SVG など) |
| 関数の読み方 | `get_template_directory()` で `my_custom/*` を読む。複製テーマならそのまま動く | `inc/*.php` 一式(CPT・Bogo・パーマリンク・検索)。**丸ごと持ってくると衝突する** |

## 破綻しやすい点(ここを無視すると壊れる)

### 1. `nig` テーマを有効にしない
Bogo・空の CPT・別 IA・別ラッパーのまま全ページが崩れる。比較用の参照テーマとしてだけ残す。

### 2. header/footer と下層テンプレの HTML 契約が違う
今の header は `#site-base` などを開き、footer で閉じる。下層は自分で `.mainWrapp` `#contentsWrapper` `#leftSlot` `#rightSlot` を開いて閉じる。
`nig` は header で `.global_wrapper` を開き、footer で閉じ、本文は `#global_contents`。
**header/footer だけ差し替えると、下層 60 ファイルの枠が新 CSS の対象外になり、本文が崩れる。**
下層テンプレを一度に書き直さない。本文ブロックを新デザインの main 枠で包み、中の ID は当面残す。

### 3. 旧 CSS と新 CSS を同時に読まない
新 CSS は `html, body, h1, a, ul, table` をリセットする。旧 `common.css` は `#header` `body` に広い指定がある。両方読むと header が先に壊れる。
旧指定は `#global_contents` など本文枠の中だけに移植する。

### 4. jQuery を二重にしない
旧 JS(`globalnavi.js` 等)は旧 header の DOM 専用なので、新 header では捨てる。新 header は jQuery 3.7.1 + `common.js` だけ。
セミナー一覧だけ本番 JS(`www.nig.ac.jp/nig_dynacom/seminar_data_bioweb.js`)を今の TOP が読んでいる。新 TOP に載せるかは別判断。

### 5. `nig/inc/*.php` を丸ごとコピーしない
`inc/custom.php` は `highlights` 等の CPT と Bogo 用フックを登録する。今のパーマリンクと衝突しうる。
header/footer/TOP に必要な関数だけ抜き出す。`nig_get_seminars_category_id` や Bogo の `lang` クエリ引数は使わない。WPML は `suppress_filters => false` のまま現在言語で絞る。

### 6. `acf-new.json` を全部入れない
今の ACF は `MainImageSlider` / `PickupSlider` / `important` / `Title` / `subTitle` / `イベント用`。
`acf-new.json` にはブロック・セミナー・研究室・対象者トップなど下層用も入っている。全部入れると管理画面が膨らみ、下層をまだ直していないのにフィールドが出る。
入れるのは TOP 用だけ。

- 入れてよい: `トップ_スライダー`(`top_slider-01`)、`トップ_重要なお知らせ`(`top_notice-*`)、`トップ_コンテンツ`(`top_scientists-*` 等)
- 入れない: `メニュー`(`common-menu-*`。Walker で代替)、カスタムブロック一式、セミナー、研究室、対象者トップ

ACF の出し先は「固定フロントページ」または `original-frontPage.php`。今は `show_on_front=posts` でフロントページが無いので、**英日の固定ページを作ってから** 取り込む。

### 7. メニューの情報設計が新デザインと一致していない
今の第1階層は 7 項目(About NIG / Research / PhD / RIC / Bioresources / Outreach / Career)。項目タイトルに `<img>` が入っている。
新 header の第1階層は対象者 4 項目(`researchers/` `students/` `public/` `business/`)。これらのスラッグの固定ページは今の DB に無い。
**今のメニューを Walker で出すと、新デザインの枠に旧 IA が入る。** 対象者 4 リンクをそのまま出すと 404 になる。
メニューの組み直しと対象者ページの新設は、header 差し替えより後の段階にする。最初は旧 7 項目を新 HTML に載せる。

### 8. TOP のデータ対応表

| 新 TOP の枠 | 新テーマが読むもの | 今あるデータ | 読み替え |
|---|---|---|---|
| メインスライダー | ACF `top_slider-01`(画像 ID) | CPT `top_slider` + ACF `top_slider_image` / `top_slider_link` | 当面は新 ACF に手入力。自動変換は後回し可 |
| Important | ACF `top_notice-*` | CPT `top_important` | 同上 |
| Our Research | CPT `highlights` + `highlights_cat` | カテゴリ `research-highlights`(322) / `collaboration-pr`(27) | `post` + カテゴリ。固定表示は `_custom_pickup` か既存の運用に合わせる |
| Interviews | CPT `interviews` | **相当データ無し** | 枠を出さないか、空のまま。投稿タイプは作らない |
| News & Events | 通常投稿 + 親カテゴリタブ | `information`(198) `events`(21) `prizes-and-awards`(70) `whats-new`(69) `important-notice`(27) など | `post` のまま。タブは今ある親カテゴリ。`seminar` カテゴリは英語側に見当たらない(今の TOP は外部 JS) |
| Our Scientists | ACF `top_scientists-*` | 無し(手入力) | ACF のまま |
| Pickup 等 | ACF | CPT `top_pickup` | 必要なら後で対応 |

`original-frontPage.php` の `'lang' => $lang` と `get_post_type_archive_link('highlights')` は必ず消す/差し替える。

### 9. `wp_head()` が今は無い
今の header は `wp_head()` を呼んでいない。新 header では呼ぶ。WPML・プラグイン・管理バーの CSS/JS が突然出る。想定内として確認する。旧 IE 条件付きコメントと XML 宣言は捨てて HTML5 にする。

### 10. テーマ画像とサイト画像は別物
新ロゴ・対象者アイコンは `themes/nig/images/`。旧ナビ画像は `/nig/images/common/`。複製テーマに `nig` テーマの `images/` `ico/` `webfonts/` をコピーする。`/nig/images/` のマウントとは混ぜない。

### 11. Docker のマウント
`docker-compose.yml` は今 `samplemain` と `nig` だけマウントしている。複製テーマを有効にする前に、そのフォルダのマウントを足す。足さないとコンテナから見えず、復元で消える。

### 12. 言語ディレクトリは今と逆に組んである
`nig` は日本語が `/`、英語が `/en/`。今の WPML は英語が `/nig/`、日本語が `/nig/ja/`。
`_header.php` の `nig_switch_lang_path()` と `_footer.php` の `$lang_path` は使わず、`icl_get_languages()` の URL をそのまま使う。`home_url()` の上に `/en` を足すと二重になる。
英語ロゴ幅の CSS は `body.en-US`(Bogo)前提。WPML では付かないので、`body.eng` か `html[lang=en]` に付け替える。

### 13. `/nig/` が抜ける URL を直す
新テーマの検索 `action="/"`、言語切替の `HTTP_HOST` 直結、SCSS の `$imageDir: '/wp/wp-content/themes/nig/images/'`、`site.webmanifest` のルート相対パスは、このサイトでは動かない。`home_url()` かテーマ URI に直す。
今の検索は Google カスタム検索ページ(`/googlesearch_ja`)へ飛ばしている。新 header の `?s=` に変えると検索結果のページが変わる。段階 3 では今の送り先を維持する。

### 14. テーマを複製するとメニュー割り当てが空になる
割り当ては `theme_mods_samplemain` に保存されている。複製後に `hdmenu` / `hdmenu_ja` と各 `sidemenu_*` を付け直す。段階 0 のチェック項目。
`samplemain/style.css` に `Theme Name:` が無い。複製時に必ず書く。

### 15. 本文クラスの移植量
公開本文で使われている主な旧クラス(件数の目安): `bold` 1435、`text` 1158、`pb20` 880、`italic` 656、`caption_text` 518、`arrow` 系 517、`qaQ`/`qaA` 226、`image_right` 146、`coursesTalbe` 108、`icon_text` 95。
定義の多くは `common.css` だけにある。段階 2 で本文枠へ移す対象の中心。
新 header の `.list` `.title` `.menu` は、旧 `common.css` の同名指定と衝突する。旧 CSS を残したまま新 header を出すと崩れる。

### 16. `#site-base` とパン屑の色
パン屑の色帯は `#site-base .bgColor*` で効いている。新 header で `#site-base` をやめるなら、色指定を本文枠側へ移す。
`#leftSlot` は今も `add_style.css` で非表示。サイドバー復活は段階 6。

### 17. Walker は 1 種類では足りない
新 header のナビは 3 系統。SP アコーディオン(`common-menu-02` 相当)、PC メガメニュー(`common-menu-01` 相当)、補助リンク。HTML とクラスが違う。同じ `hdmenu` から出すなら Walker を分けるか、深さで出し分ける。
並びは `button` → `a` → `div.wrapper` を守る。`common.js` は `next().next()` で開閉する。
`href` の無い `<a>` が 1 つあると `common.js` 全体が止まる。URL 空の項目は `<span>` か `href="#"` にする。
`modaal` が無いと同じく全体が止まる。header だけでは使わなくても読み込む。

### 18. 英語 header に `wp_head()` / `wp_footer()` が無い
日本語だけある。新 header で両方入れると、英語ページに WPML の hreflang とプラグイン CSS が初めて出る。想定内として確認する。
`insert_custom_tmpl()` は header から呼ばれ、メインループを 1 件進める。新 header では呼ばない。本文は出るが `in_the_loop()` の状態は変わる。

### 19. その他
- `inc/custom.php` のタクソノミー rewrite slug はすべて `category`。標準の `/category/` と衝突するのでファイルごと入れない。
- 新 CSS は `input[type=checkbox|radio]{display:none}` が全体に効く。旧フォームのチェックが見えなくなる。本文枠の外、または除外セレクタが必要。
- `home.css` は `body.page-template-original-frontPage` に依存する。テンプレートファイル名を変えると TOP の見た目が消える。
- Font Awesome Pro が入っている。公開リポジトリに置いてよいか確認する。
- `$NowLanguage` は `functions.php` のグローバルスコープで代入している。関数の中へ移すと null になる。
- 今のサイドメニュー location はテーマ名に紐づく。下層 HTML は出しているが画面では隠している。

## やってはいけないこと

- `nig` テーマを有効にする
- `samplemain` を直接編集する
- `acf-new.json` を全件インポートする
- `nig/inc/custom.php` や `nig/functions.php` を丸ごとコピーする
- `highlights` / `interviews` の CPT を作って 1790 件を移す
- 旧 CSS と新 CSS を同時に読む
- jQuery 1.8.2 と 3.7.1 を同時に読む
- パーマリンクや WPML の言語ディレクトリを変える
- 対象者 4 ページが無い状態で、その URL を header の主リンクにする
- header/footer/CSS/TOP/下層を同じ commit で一度に替える

## 破綻しない進め方(この順だけ)

各段階の終わりに、英 TOP・日 TOP・下層固定ページ 1 つ・投稿 1 つ・カテゴリ 1 つを目視する。前の段階に戻せること。

### 段階 0: 複製して見た目を変えない
1. `samplemain` を `nig-renewal`(仮)にコピーする。`style.css` に `Theme Name: nig-renewal` を書く(元ファイルには無い)。
2. `docker-compose.yml` にマウントを足してコンテナを再作成する。README も同じ commit で更新する。
3. ローカルだけ新しいテーマを有効にする。`samplemain` は残す。
4. 外れたメニュー割り当て(`hdmenu` / `hdmenu_ja` / `sidemenu_*`)を付け直す。
5. 今と同じ見た目なら成功。ここで初めて「触ってよいテーマ」ができる。

### 段階 1: 資産をテーマへ移す(まだ出さない)
1. `nig` から `css/` `js/` `images/` `ico/` `webfonts/` `template-parts/` を複製テーマへコピーする。
2. `functions.php` は `samplemain` のものを残す。enqueue 用に薄いファイルを 1 つ足すだけ。
3. この段階ではまだ旧 header が旧 CSS を読む。サイトは変わらない。

### 段階 2: 下層の枠だけ先に新 CSS へ乗せる
1. 旧 `/nig/css/*.css` と旧 `/nig/js/*.js` の読み込みをやめる。
2. 新 `style.css` + `common.js` + jQuery 3.7.1 を読む。
3. `pagetemp-*.php` / `category.php` / `single.php` などの本文を `#global_contents` で包む。中の `#leftSlot` `#rightSlot` `.box` は残す。
4. 旧 CSS から本文用(`.listlink_box` `.icon_text` `#leftSlot` パン屑 色クラスなど)だけを、本文枠向けに移植する。
5. **header/footer はまだ旧のまま**。下層の本文が読めることを先に確認する。ここで崩れるのは CSS 移植不足なので、範囲を足す。

### 段階 3: header / footer
1. `nig` の `_header.php` / `_footer.php` を移植する。`get_field('common-menu-*')` は使わない。SP 用と PC 用で Walker を分け、`hdmenu` / `hdmenu_ja` から出す。
2. 言語切り替えは `icl_get_languages()` の URL をそのまま使う。`/en` を足す処理は捨てる。
3. `header-ja.php` / `footer-ja.php` は同じ部品を呼ぶ薄いファイルにする。`get_header($NowLanguage['load'])` の呼び出し元は触らない。
4. 対象者 4 リンクは、該当ページができるまで出さないか、既存の近いページへ仮置きする。404 を量産しない。
5. 英日とも `wp_head()` / `wp_footer()` を入れる。英語ページに WPML とプラグインの CSS が出ることを確認する。
6. メニュー項目の `<img>` は Walker で外す。`href` の無い `<a>` は出さない。`modaal` は読む。
7. 検索の送り先は当面 `/googlesearch_ja` のまま。`action="/"` にはしない。
8. `insert_custom_tmpl()` は新 header から呼ばない。

### 段階 4: TOP
1. 英語・日本語の固定フロントページを作り、WPML で対にし、テンプレートを `original-frontPage.php`(または `front-page.php`)にする。
2. `show_on_front` を `page` にする。この瞬間に旧 `index-en.php` / `index-ja.php` は使われなくなる。
3. ACF は TOP 用グループだけインポートする。スライダー・お知らせ・研究者は管理画面から入れる。
4. `original-frontPage.php` のクエリを上の対応表どおり `post` + カテゴリに変える。`interviews` はデータが無ければ枠を出さない。
5. `list-*.php` はカード HTML 用に残してよい。中のタクソノミー名だけ今のに合わせる。

### 段階 5: 情報設計(別作業)
Figma の対象者 4 柱と今の 7 柱をどう対応させるか決めてから、メニューを組み直し、固定ページを足す。段階 3 の header はメニューを差し替えるだけで追従できる状態にしておく。

### 段階 6: 下層の見た目
サイドバー・パン屑・本文タイポを新デザインに寄せる。段階 2 で残した旧クラスを、ここで少しずつ新モジュールへ移す。最初のリリースには必須にしない。

## 複製テーマの名前

仮: `nig-renewal`。フォルダも Theme Name もこれにする。`nig` は参照用のまま残す。

## 未決(実装前に決めること)

- 対象者 4 リンクを、段階 3 で隠すか、既存ページへ仮置きするか
- Interviews 枠を出さないか、別コンテンツで埋めるか
- セミナーを新 TOP に載せるか(今は外部 JS。カテゴリ `seminar` は英語側に無い)
- 旧 `top_slider` を新 ACF へ変換するか、管理画面で入れ直すか
- Figma の IA を段階 5 でいつやるか
- Font Awesome Pro を公開リポジトリに置いてよいか
- 検索を Google カスタム検索のままにするか、WordPress 検索に変えるか
