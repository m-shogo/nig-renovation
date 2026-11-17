# PLAN(リニューアルの方針)

このリポジトリでやりたいことと、決めたこと・未決のことの記録。方針が決まったら更新する。
**実装は、ここに書いた順番以外で始めない。**

## ゴール

見た目・余白・JS・動きは、組み上げ済みの `nig/wp-content/themes/nig` をそのまま使う。Figma から作り直さない。CSS も JS も書き直さない。

それを、今のローカル環境(検証サーバーの `.wpress` を復元したもの)に載せる。`nig` テーマ自体は有効にしない。`samplemain` を複製したテーマへ、`nig` の HTML・CSS・JS・画像を入れる。

メニューの中身だけは、検証環境の既存メニュー `hdmenu`(英)・`hdmenu_ja`(日)を入れる。項目は足さない。ACF のメニューは使わない。対象者 4 リンクは出さない。そのとき header の余白が潰れないようにする。

- デザイン・JS・動きの正本: `nig/wp-content/themes/nig`(`css/`、`js/common.js`、`template-parts/_header.php`、`template-parts/_footer.php`)
- TOP の正本: `original-frontPage.php`。`get_header()` と `get_footer()` を引数なしで呼ぶ。`index-en.php` と `index-ja.php` は使わない
- データの正本: 今のローカル DB。メニューは `hdmenu` / `hdmenu_ja`
- footer は `_footer.php` のまま。項目は足さない
- 旧 `/nig/css/` は読まない

## デザイン・仕様

Figma 上のファイルが正本。このリポジトリには書き出していない。

- PC(TOP): https://www.figma.com/design/0BHOnrnHSBSx5QTvh94RgY/nig-renovation?node-id=15568-3408
- SP(TOP): https://www.figma.com/design/0BHOnrnHSBSx5QTvh94RgY/nig-renovation?node-id=6009-1464
- 仕様書: https://www.figma.com/design/bifb2nXzhIe9zIzpHEOXwo/nig-renovation%E4%BB%95%E6%A7%98%E6%9B%B8?node-id=15407-3

## 決めたこと

2026-10-21 にテーマ・CSS・TOP を決め、2026-11-16 に header と footer を決めた。

1. **テーマ**: `samplemain` を複製して別テーマにする。`samplemain` は今の状態のまま残し、比較・切り戻し用にする。
2. **header のメニュー**: ACF のメニュー(`common-menu-*`)は廃止する。今の WordPress メニュー `hdmenu`(英)・`hdmenu_ja`(日)の項目だけを、新 header の HTML に載せる。メニュー項目の追加・削除・並べ替えはしない。
   日英は `header.php` 1 枚。判定は `ICL_LANGUAGE_CODE`。`header-ja.php` は置かない。言語切替の URL は `icl_get_languages()`。
3. **footer**: `nig` の `_footer.php` をそのまま使う。メニューの組み替えや項目追加はしない。住所・サイトマップ・関連機関・SNS はテーマに直書きされている。ACF の `common-submenu-*` は入れない。
4. **CSS**: `nig` の CSS に差し替える。旧 `/nig/css/common.css`・`navigation.css`・`index.css` は読まない。
   DB の本文(投稿・固定ページ)で使われている旧クラスの指定だけを、本文エリアに限定して移植する。
5. **TOP**: `original-frontPage.php` だけを使う。`index-en.php` と `index-ja.php` は使わない。`highlights` と `interviews` は作らず、既存投稿への読み替えもせず、その枠は出さない。
   スライダー・お知らせ・研究者・Recommends の ACF は `acf/acf-new.json` から **TOP 関連グループだけ** 取り込む。

## 2 つのテーマの違い(調査)

| 項目 | samplemain(今・有効) | nig(新デザイン・未使用) |
|---|---|---|
| 多言語 | WPML。`header-ja.php` / `footer-ja.php`。`icl_get_languages` | Bogo 前提。`get_locale()`。1 つの header |
| グローバルナビ | メニュー `hdmenu`(136) / `hdmenu_ja`(165)。第1階層は About NIG / Research / PhD / RIC / Bioresources / Outreach / Career | 上段は直書きの対象者4リンク。ドロップダウンは ACF `common-menu-01`(PC) / `common-menu-02`(SP)。どちらも今回は使わない |
| CSS | WordPress 外 `/nig/css/*.css`。header が直接 link | テーマ内 `css/style.css`(約337KB、SCSS)。`_head_contents.php` で head にインライン出力。Meyer reset が全体に効く |
| JS / jQuery | 3.7.1（`nig/js/jquery-3.7.1.min.js`）+ `/nig/js/globalnavi.js` 等。英語 header は `wp_head()` を呼んでいない | 3.7.1 + `js/common.js`(header の DOM 前提) + Swiper。`wp_head()` / `wp_footer()` あり |
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

### 7. 対象者 4 リンクはデザインから削除済み
`ul.list` の4件（研究・共同研究を探したい `/researchers/`、大学院で学びたい `/students/`、遺伝研を知りたい `/public/`、産学連携を考えたい `/business/`）は、今の Figma には無い。PC の TOP ではレイヤー `targeting` が非表示。Header はロゴと、遺伝研について / 研究 / 大学院 / 共同研究・研究支援事業 / バイオリソース / 社会連携・産学連携 / 人材キャリア の7項目だけ。

仕様書の注記は「削除」。旧メニュー構造にする、とも書いてある。`nig` テーマの `_header.php` に残っている `ul.list` は古いので出さない。余白用に残さない。

メニュー項目の追加・削除はマスター権限がないと禁止。WordPress のメニューは触らず、今ある `hdmenu` / `hdmenu_ja` を表示するだけ。

実装するときは `.cursor/rules/header-menu.mdc` に従う。仕様書と Figma を見てから書き、書いたあとも Header と照合する。

### 8. TOP のデータ対応表

| 新 TOP の枠 | 新テーマが読むもの | 今あるデータ | 読み替え |
|---|---|---|---|
| メインスライダー | ACF `top_slider-01`(画像 ID) | CPT `top_slider` + ACF `top_slider_image` / `top_slider_link` | 当面は新 ACF に手入力。自動変換は後回し可 |
| Important | ACF `top_notice-*` | CPT `top_important` | 同上 |
| Our Research | CPT `highlights` + `highlights_cat` | 投稿タイプが無い | 枠を出さない。投稿タイプは作らない。投稿への読み替えもしない |
| Interviews | CPT `interviews` | 投稿タイプが無い | 枠を出さない。投稿タイプは作らない |
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
定義の多くは `common.css` だけにある。段階 4 で本文枠へ移す対象の中心。
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
- `highlights` / `interviews` の CPT を作る。既存投稿へ読み替えて枠を出すことも、今はしない
- 旧 CSS と新 CSS を同時に読む
- jQuery 1.8.2 と 3.7.1 を同時に読む
- パーマリンクや WPML の言語ディレクトリを変える
- 対象者 4 リンク(`ul.list` の `/researchers/` `/students/` `/public/` `/business/`)を出す
- WordPress のメニュー項目を追加・削除・並べ替えする（マスター権限がないと禁止）
- header/footer/CSS/TOP/下層を同じ commit で一度に替える

## 破綻しない進め方(この順だけ)

各段階は別 commit。終わりに英 TOP・日 TOP・下層固定ページ 1 つ・投稿 1 つ・カテゴリ 1 つを見る。前の段階に戻せること。

新 CSS と `common.js` は旧ヘッダーの DOM では動かない。下層を先に新 CSS へ載せると、ヘッダーを替えたときに枠を書き直す。だからヘッダーと CSS の切り替えは同じ段階にし、下層の本文枠はその次、TOP はヘッダーが固まってからにする。

### 段階 0: 済。jQuery 3.7.1
今のサイトの JS を 3.7.1 で動かした。`samplemain` に入っている。ここは戻さない。

### 段階 1: 複製して見た目を変えない
1. いまの `samplemain` を `nig-renewal` にコピーする。`style.css` に `Theme Name: nig-renewal` を書く。
2. `docker-compose.yml` にマウントを足してコンテナを再作成する。README も同じ commit で更新する。
3. ローカルだけ新しいテーマを有効にする。`samplemain` は比較用に残す。これ以降 `samplemain` は編集しない。
4. 外れたメニュー割り当て(`hdmenu` / `hdmenu_ja` / `sidemenu_*`)を付け直す。項目の中身は変えない。
5. 今と同じ見た目なら成功。

### 段階 2: 資産を置くが、まだ読まない
1. `nig` から `css/` `js/` `images/` `ico/` `webfonts/` を複製テーマへコピーする。
2. `functions.php` は段階 1 のものを残す。enqueue 用のファイルは足すが、この段階では旧 CSS のままにする。
3. サイトの見た目は変わらない。

### 段階 3: ヘッダーとフッター、同時に CSS と JS を切り替え
旧 CSS・旧 JS を残したまま新ヘッダーだけ出さない。同じ段階で両方入れ替える。
1. 移植の前に仕様書と Figma の Header を見る。
2. `_header.php` を `header.php` に載せる。対象者 4 リンクは出さない。`common-menu-*` は使わない。`hdmenu` / `hdmenu_ja` の既存項目だけ。項目の `<img>` は外す。`href` の無い `<a>` は出さない。
3. 言語切替は `icl_get_languages()` の URL。`/en/` は足さない。`html lang` は `ICL_LANGUAGE_CODE`。英語幅の CSS は `body.en-US` をやめる。
4. `_footer.php` を `footer.php` に載せる。項目は足さない。`common-submenu-*` は読まない。
5. `header-ja.php` と `footer-ja.php` は置かない。`wp_head()` と `wp_footer()` は日英とも入れる。`insert_custom_tmpl()` は呼ばない。
6. 旧 `/nig/css` と旧 `/nig/js`（`globalnavi.js` など）をやめる。新 `style.css`、jQuery 3.7.1、`modaal`、`common.js` を読む。検索の送り先は `/googlesearch_ja` のまま。
7. 確認は Figma の Header と余白。下層の本文が崩れて見えるのは次の段階で直す。ページが 200 で、文字が HTML にあることまで見る。

### 段階 4: 下層の本文を読めるようにする
1. ホーム以外の本文を `#global_contents` で包む。中の `#leftSlot` `#rightSlot` `.box` は残す。`#leftSlot` は旧どおり非表示のまま。
2. 新しい CSS に無い本文用だけを `#global_contents` の中へ置く。旧 `common.css` は戻さない。対象は `.listlink_box` `.icon_text` パン屑 `.bgColor*` `.pb20` `.bold` `.italic` `.image_right` `.caption_text` `.qaQ` `.qaA` `.coursesTalbe` と、カテゴリの `.yearSelectWrap`。
3. `nig/js/yearSelect.js` はカテゴリだけで読む。年リストの CSS が先頭以外を隠すので、開閉にはこのファイルが要る。中身は `.on('click')` と `slideToggle()` だけで、jQuery 3.7.1 のまま動く。`globalnavi.js` や `default.js` は戻さない。
4. ヘッダーの中身は触らない。サイドバーの復活や本文の作り込みはしない。

### 段階 5: TOP
1. `original-frontPage.php` を複製テーマに置いた。4 リンクとキャッチコピーは出さない。`highlights` と `interviews` は出さない。News は通常の `post`。`'lang'` は使わない。
2. 英日の固定ページ（en=51100, ja=51101）を WPML で対にし、`show_on_front=page`。作り直しは `scripts/setup-front-page.php`。
3. TOP 用 ACF は `acf/top.json`。中身は本番 `https://www.nig.ac.jp/` と `https://www.nig.ac.jp/en/` の表示から入れた。画像はローカルのメディア。リンク先は本番の URL。入れ直しは `scripts/import-top-acf.php`。
4. お知らせは Opportunities（`top_notice-*`、緑、旗）と Important（`top_important-*`、朱色、感嘆符）の2枠。項目は同じ。スライダーは横840pxのカードで、下のドットで送る。Research と Interview はまだ出さない。SP ヘッダーの対象者4リンクは出さない。

### 段階 6
メニューの追加・削除と対象者4ページはまだやらない。
下層は、新しい見出し帯とパンくずを先に載せる。本文の HTML は書き直さず、見出し・本文・表・リストの見た目だけ CSS で新しい部品へ寄せる。隠している左メニューはまだ出さない。

## 複製テーマの名前

仮: `nig-renewal`。フォルダも Theme Name もこれにする。`nig` は参照用のまま残す。

## 2026-11-17 確定（実装で外さない）

このチャットで決めたこと。実装時はここを上から確認する。

### 作業場所
- 見るのはローカル `http://localhost:3080/nig/` だけ。検証サーバーの Basic 認証は開かない。
- 中身は `nig-new-codiadev8-site-nig-20261020-172431-xx8bxnc530pb.wpress`。
- 手元に無い uploads は本番 `https://www.nig.ac.jp` へ転送しない。無いパスは 404。`uploads/.htaccess` は消さない・書き換えない。

### 正本
- 見た目・余白・JS・動きは `nig/wp-content/themes/nig`。CSS も JS も Figma から作り直さない。
- その HTML には古い部分がある。ヘッダーと TOP を書く前に、仕様書と Figma（PC `15568-3408`、SP `6009-1464`）を見る。ルールは `.cursor/rules/header-menu.mdc`。
- `nig` テーマは有効にしない。`samplemain` は直接編集しない。複製テーマ `nig-renewal` に載せる。載せる前に `docker-compose.yml` へマウントを足す。

### ヘッダー・フッター・言語
- `header.php` と `footer.php` の 1 枚で日英を出す。判定は `ICL_LANGUAGE_CODE`（`en` / `ja`）。WPML 文字列翻訳はファイルの切り分けには使わない。
- `header-ja.php` と `footer-ja.php` は置かない。`get_header('ja')` はファイルが無いと `header.php` に落ちる。
- Bogo の経路（日本語が `/`、英語が `/en/`）は使わない。切替先は `icl_get_languages()` の URL。ディレクトリは英語が `/nig/`、日本語が `/nig/ja/` のまま。
- `wp_head()` と `wp_footer()` は日英とも入れる。
- メニューは `hdmenu` と `hdmenu_ja` を表示するだけ。追加・削除・並べ替えはしない。マスター権限がないと禁止。
- ACF の `common-menu-*` と `common-submenu-*` は使わない。
- 対象者 4 リンク（`/researchers/` `/students/` `/public/` `/business/`）は出さない。`_header.php` の `ul.list` と、`original-frontPage.php` の `#top_link-01` の両方。余白用に残さない。Figma の `targeting` は非表示。仕様書の注記は「削除」。
- フッターは `_footer.php` の直書き（住所、サイトマップ、関連機関、SNS）のまま。項目は足さない。

### TOP
- 正本は `original-frontPage.php`。`get_header()` と `get_footer()` は引数なし。ファイル名は変えない。
- `index-en.php` と `index-ja.php` は使わない。表示には、英日の固定フロントページを WPML で対にし、`show_on_front` を `page` にする。この 2 ページはメニュー項目ではない。
- `highlights` と `interviews` は無い。投稿タイプは作らない。今の投稿への読み替えもしない。Our Research と Interviews の枠は出さない。
- News は通常の `post`。Bogo の `'lang'` と `nig/inc` のセミナー関数は使わない。
- スライダー、重要なお知らせ、研究者、Recommends は ACF。投稿タイプではない。入れるのは TOP 用グループだけ。

### 仕様と functions.php
- お知らせ、投稿タイプ、ショートコード、パーマリンクは今の環境のまま。`samplemain/functions.php` を残す。
- 今ある投稿タイプは `top_important` / `top_slider` / `top_pickup`。ショートコードは `[get_posts]` `[get_posts_en]` `[onphp]`。
- `nig/functions.php` は読み込まない。中身は `inc/*.php` の一括読み込みで、投稿タイプ・リライト・Bogo・検索改変・管理画面の URL 改変が入っている。
- 新デザインに必要なものだけ別ファイルで足す。jQuery 3.7.1、`common.js`、`modaal`、Swiper、TOP だけの `home.js`、`style.css`、スライダー用の `add_image_size`。
- いま動いている旧 JS は jQuery 3.7.1 で動くよう直した。`globalnavi.js` の `.live()`、`default.js` と `index.js` の `$(window).load()`、bxSlider の `.load()`。読み込みは `nig/js/jquery-3.7.1.min.js`。1.8.2 は使わない。
- `inc/custom.php`、`inc/permalink.php`、`inc/shortcode.php`、`inc/search.php`、`inc/admin.php` は足さない。

## 実装前に残っている懸念

決めたことの外で、無視すると崩れるもの。

1. **新 CSS は下層を巻き込む。** Meyer reset は `body` や `a` 全体に効く。ヘッダーと CSS は同じ段階で切り替え、本文枠は直後の段階で足す。本文枠を飛ばしたまま下層の見た目を作り込まない。
2. **`nig` の URL はこの環境ではそのまま動かない。** 言語切替の `/en/`、検索 `action="/"`、SCSS の画像パス `/wp/wp-content/themes/nig/images/`。`/nig/` を付けて、言語 URL は WPML のものを使う。
3. **`common.js` は条件が欠けると全体が止まる。** `href` の無い `<a>`、`modaal` 未読込。メニュー項目のタイトルに入っている `<img>` は外す。開閉の並びは `button` → `a` → `div.wrapper`。
4. **英語用の幅指定が `body.en-US` 前提。** これは Bogo のクラスで、WPML では付かない。`html[lang=en]` か、こちらで付けるクラスに変える。
5. **英語ページには今 `wp_head()` が無い。** 共通 header で入れると、英語側に WPML とプラグインの CSS が初めて出る。出ることを確認する。
6. **旧 `top_slider` は新スライダーに自動では入らない。** 新テンプレートは ACF `top_slider-01` を読む。変換するか、管理画面で入れ直すかは未決。空ならスライダーは出ない。
7. **キャッチコピー。** 仕様書の注記は「キャッチコピーは削除」。`original-frontPage.php` は `tm_title` を出している。実装時に Figma と照合し、仕様にあれば出さない。
8. **セミナーは未決。** 今の英語 TOP は `www.nig.ac.jp` の外部 JS。新 TOP へ載せるかは決めていない。`nig_get_seminars_category_id` は `inc/custom.php` にあり、ファイルごとコピーしない。
9. **検索の送り先は未決。** 今は Google カスタム検索。新 header の `?s=` に変えると結果ページが変わる。決まるまでは `/googlesearch_ja` を維持する。
10. **Font Awesome Pro を公開リポジトリに置いてよいか未決。** テーマに含まれている。
11. **複製するとメニュー割り当てが空になる。** `hdmenu` と `hdmenu_ja` を付け直す。項目の中身は変えない。

## 未決(実装前に決めること)

- セミナーを新 TOP に載せるか(今は外部 JS。カテゴリ `seminar` は英語側に無い)
- 旧 `top_slider` を新 ACF へ変換するか、管理画面で入れ直すか
- Font Awesome Pro を公開リポジトリに置いてよいか
- 検索を Google カスタム検索のままにするか、WordPress 検索に変えるか
