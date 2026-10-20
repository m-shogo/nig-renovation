<?php

/**
 * フロントのスタイル・JS設定
 */
if (locate_template('inc/front.php') !== '') {
	require_once locate_template('inc/front.php');
}

/**
 * 管理画面の設定
 */
if (locate_template('inc/admin.php') !== '') {
	require_once locate_template('inc/admin.php');
}

/**
 * カスタム投稿タイプ・タクソノミーの設定
 */
if (locate_template('inc/custom.php') !== '') {
	require_once locate_template('inc/custom.php');
}

/**
 * パーマリンクの設定
 */
if (locate_template('inc/permalink.php') !== '') {
	require_once locate_template('inc/permalink.php');
}

/**
 * カスタムフィールドの設定
 */
if (locate_template('inc/field.php') !== '') {
	require_once locate_template('inc/field.php');
}

/**
 * ツールチップの共通関数
 */
if (locate_template('inc/tooltip.php') !== '') {
	require_once locate_template('inc/tooltip.php');
}

/**
 * 目次（TOC）の共通関数
 */
if (locate_template('inc/toc.php') !== '') {
	require_once locate_template('inc/toc.php');
}

/**
 * エディターの設定
 */
if (locate_template('inc/editor.php') !== '') {
	require_once locate_template('inc/editor.php');
}

/**
 * ページネーションの設定
 */
if (locate_template('inc/pagination.php') !== '') {
	require_once locate_template('inc/pagination.php');
}

/**
 * フォームの設定
 */
if (locate_template('inc/form.php') !== '') {
	require_once locate_template('inc/form.php');
}

/**
 * 検索の設定
 */
if (locate_template('inc/search.php') !== '') {
	require_once locate_template('inc/search.php');
}

/**
 * ショートコードの設定
 */
if (locate_template('inc/shortcode.php') !== '') {
	require_once locate_template('inc/shortcode.php');
}
