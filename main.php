
<?php
/*
Plugin Name: modal scroll img
Description: スクロールで広告がモーダルで出る
*/

add_action('admin_menu', 'my_add_admin_menu');
/**
 * 「設定」にメニューを追加
 */
function my_add_admin_menu()
{
  // add_options_page( ←設定メニューの中に出る
  add_menu_page(
    'モーダル画像', // 設定画面のページタイトル
    'モーダル画像', // 管理画面メニューの表示名
    'manage_options',
    'my-original-menu',
    'my_original_menu_page'
  );
}

/**
 * メニューページの中身を作成
 */
function my_original_menu_page() {
  // こっちがアウトプットphp
  // ここで4つのpost値が来ているか判定
  if(
    !empty($_POST['image_path'])
    && !empty($_POST['top'])
    && !empty($_POST['cat_id'])
    && !empty($_POST['url'])
  ){

    //ちゃんとサニタイズ(入力データなどに含まれる不正なコードや危険な文字を検出し、無害化(消毒)する処理もする
    $post = [];
    foreach ($_POST as $key => $str) {
      if ($key != 'cat_id'){
        $crean_text = htmlspecialchars($str, ENT_QUOTES);
        $post[$key] = $crean_text; 
      } else {
        $post[$key] = $str;
      }
    }
    //改行があっても一行目だけにする
    $post['url'] = explode("\n", $post['url'])[0];

    // DBのwp_optionsにアップサート(アップデートまたはインサート)
    update_option('modal_scroll', $_POST);
    // 保存したらメッセージを出す
    echo '<div class="wrap" style="color:#4090ed;"> 保存しました </div>';
  }

  // いま更新した値を取り出す
  // SELECT * FROM wp_options WHERE ID = 'modal_scroll' をアンシリアライズして配列で取得
  $modal_settings = get_option('modal_scroll');
  //print_r($modal_settings); // ★ $modal_settingsの中身をみる(本番環境には不要) 
  // var_dump($modal_settings);


  ?>



  <!-- こっちがインプット -->
   <?php require_once 'admin-input.php'; // ← onceをつけると一回しか読まない ?>
  <!-- こっちがインプット -->
  
  <?php
}


// 対象カテゴリ
// 記事に挿入
add_filter(
  'the_content',
  function ($content) {
    // 管理画面で設定されているカテゴリの取得
    $modal_settings = get_option('modal_scroll');
    echo $modal_settings['url'];
    echo $modal_settings['image_path'];

    // 開いているページのカテゴリIDを取得
    $categories = get_the_category();
    foreach ($categories as $category) {
      $now_category = $category->term_id; //カテゴリIDを数字のIDに割り当てる
    }
      // 投稿以外のページにはカテゴリがないのでここで判定する
    if (isset($now_category, $modal_settings['cat_id']) && in_array($now_category, $modal_settings['cat_id'])) {
      // プラグインフォルダのURLを取得(cssはurl参照のため)
      $plugin_url = plugin_dir_url(__FILE__);
      $html = "<link rel='stylesheet' href='$plugin_url/style.css'>";

      // 別ファイルのhtmlを読み込む絶対パスが必要
      $html .= file_get_contents( //styleシートにくっつけるから .=を使う
        WP_PLUGIN_DIR . '/modal-scroll-img/modal.html'
      );
      // 画像のurlはフルパスで入れる必要がある
      $html = sprintf(
        $html,
        $modal_settings['url'],
        $modal_settings['image_path'],
        $modal_settings['top'],
      );
    } else {
      $html = '';
    }
    return $content . $html;  // ← これが記事本文
  }    // 対象カテゴリでなければ .$html をくっつけない
  ,
  10
); // ← これは10番目に実行される 数字が大きいほど強い




