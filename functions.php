<?php

/*-------------------------------------------*/
/*  カスタム投稿タイプ「イベント情報」を追加
/*-------------------------------------------*/
// add_action( 'init', 'add_post_type_event', 0 );
// function add_post_type_event() {
//     register_post_type( 'event', /* カスタム投稿タイプのスラッグ */
//         array(
//             'labels' => array(
//                 'name' => 'イベント情報',
//                 'singular_name' => 'イベント情報'
//             ),
//         'public' => true,
//         'menu_position' =>5,
//         'has_archive' => true,
//         'supports' => array('title','editor','excerpt','thumbnail','author')
//         )
//     );
// }

/*-------------------------------------------*/
/*  カスタム分類「イベント情報カテゴリー」を追加
/*-------------------------------------------*/
// add_action( 'init', 'add_custom_taxonomy_event', 0 );
// function add_custom_taxonomy_event() {
//     register_taxonomy(
//         'event-cat', /* カテゴリーの識別子 */
//         'event', /* 対象の投稿タイプ */
//         array(
//             'hierarchical' => true,
//             'update_count_callback' => '_update_post_term_count',
//             'label' => 'イベントカテゴリー',
//             'singular_label' => 'イベント情報カテゴリー',
//             'public' => true,
//             'show_ui' => true,
//         )
//     );
// }

/********* 備考1 **********
Lightningはカスタム投稿タイプを追加すると、
作成したカスタム投稿タイプのサイドバー用のウィジェットエリアが自動的に追加されます。
プラグイン VK All in One Expansion Unit のウィジェット機能が有効化してあると、
VK_カテゴリー/カスタム分類ウィジェット が使えるので、このウィジェットで、
今回作成した投稿タイプ用のカスタム分類を設定したり、
VK_アーカイブウィジェット で、今回作成したカスタム投稿タイプを指定する事もできます。

/********* 備考2 **********
カスタム投稿タイプのループ部分やサイドバーをカスタマイズしたい場合は、
下記の命名ルールでファイルを作成してアップしてください。
module_loop_★ポストタイプ名★.php
*/

/*-------------------------------------------*/
/*  フッターのウィジェットエリアの数を増やす
/*-------------------------------------------*/
// add_filter('lightning_footer_widget_area_count','lightning_footer_widget_area_count_custom');
// function lightning_footer_widget_area_count_custom($footer_widget_area_count){
//     $footer_widget_area_count = 4; // ← 1~4の半角数字で設定してください。
//     return $footer_widget_area_count;
// }

/*  -------------------------------------------
  ファイルが存在する場合のみCSSを適用する
  -------------------------------------------*/
function add_style_if_exists($name, $path) {
  if (file_exists($path)) {
    wp_enqueue_style($name, $path);
  }
}

/*  -------------------------------------------
  ファイルが存在する場合のみJavaScriptを適用する
  -------------------------------------------*/
function add_script_if_exists($name, $path) {
  if (file_exists($path)) {
    wp_enqueue_script($name, $path, ['jquery']);
  }
}

/*  -------------------------------------------
  固定ページ／投稿ページ CSS・JSのファイル読み込み用メソッド
  -------------------------------------------*/
add_action('wp_print_scripts', 'add_custom_scripts');
function add_custom_scripts() {
  // 管理ページは除外
  if (is_admin()) {
    return;
  }

  // 子テーマディレクトリ取得
  $child_theme_uri = get_stylesheet_directory_uri();
  // ロケール取得
  $locale = get_locale();

  // 全ページ共通
  add_style_if_exists('style_default', $child_theme_uri.'/css/style_default.js');
  add_script_if_exists('script_default', $child_theme_uri.'/js/script.js');

  // 英語ページ共通
  if ($locale == 'en_US') {
    add_style_if_exists('style_en', $child_theme_uri.'/css/en/style_en.css');
    add_script_if_exists('script_en', $child_theme_uri.'/js/en/script_en.js');
  }

  // 検索フォームCSS
  add_style_if_exists('searchform', $child_theme_uri.'/css/pluginPageCSS/searchform.css');
  // PolylangメニューカスタマイズCSS
  add_style_if_exists('polylang', $child_theme_uri.'/css/pluginPageCSS/polylang.css');

  if (is_search()) {
    // 検索結果ページ
    wp_enqueue_style('search', $child_theme_uri.'/css/pluginPageCSS/search.css');
  } else if (is_page()) {
    // 固定ページ
    $slug = get_post(get_the_ID())->post_name;
    $name = preg_replace('/_en$/', '', $slug);

    add_style_if_exists('style_fixed', $child_theme_uri.'/css/fixedPageCSS/style_fixed.css');
    add_script_if_exists('script_fixed', $child_theme_uri.'/js/fixedPageJS/script_fixed.js');

    if (is_front_page()) {
      // トップページ
      add_style_if_exists('home', $child_theme_uri.'/css/fixedPageCSS/home.css');
      add_script_if_exists('home', $child_theme_uri.'/js/fixedPageJS/home.js');
    } else {
      // その他固定ページ
      add_style_if_exists($name, $child_theme_uri.'/css/fixedPageCSS/'.$name.'.css');
      add_script_if_exists($name, $child_theme_uri.'/js/fixedPageJS/'.$name.'.js');
    }
    // 英語ページ
    if ($locale == 'en_US') {
      add_style_if_exists('style_fixed_en', $child_theme_uri.'/css/en/fixedPageCSS/style_fixed_en.css');
      add_style_if_exists($name.'_en', $child_theme_uri.'/css/en/fixedPageCSS/'.$name.'_en.css');
    }
  }  else if (is_single()) {
    // 個別投稿ページ
    add_style_if_exists('style_posted', $child_theme_uri.'/css/postedPageCSS/style_posted.css');
    add_script_if_exists('script_posted', $child_theme_uri.'/js/postedPageJS/script_posted.js');
    // 英語ページ
    if ($locale == 'en_US') {
      add_style_if_exists('style_posted_en', $child_theme_uri.'/css/en/postedPageCSS/style_posted_en.css');
      add_script_if_exists('script_posted_en', $child_theme_uri.'/js/en/postedPageJS/script_posted_en.js');
    }
  }
}

