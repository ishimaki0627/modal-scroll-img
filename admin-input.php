<style>
  [name="image_path"],[name="url"] {
    width: 30rem; /* 文字入力するところの幅はrem(1文字分)で指定したほうがいい */
  }

  [name="top"] {
    width: 5rem;
  }

  [name="cat_id[]"] {
    width: 10rem;
  }

  .wrap label {
    display: inline-block;
    width: 6rem;
  }

  select option:checked {
  background-color: #4090ed;
  color: white;
}

/*
.input-style {
  width: 450px;
}

select option:checked {
  background-color: #4090ed;
}

textarea {
  width: 500px;
}
*/
</style>

<div class="wrap">
    <h1>モーダル画像設定</h1>
    <form method="POST" action="">
      <p>
        <label>広告画像(フルパス) </label>
        <input type="text" name="image_path" class="input-style" value="<?= $modal_settings['image_path'] ?? '' // これがきちんと反映されているか確認するのに★が必要 ?>">
      </p>

      <p>
        <label>スクロールの距離 </label><input type="number" name="top" value="<?= $modal_settings['top'] ?? ''  ?>">px
      </p>

      <p>
        <label>カテゴリ選択(複数可) </label>
        <select name="cat_id[]" multiple>

          <?php //試しにだす
            $categories = get_categories();
            foreach ($categories as $category) {
              if (isset($modal_settings['cat_id']) && in_array($category->cat_ID, $modal_settings['cat_id'])) {
                $selected = 'selected';
              } else {
                $selected = '';
              }
              ?>
            <option value="<?= $category->cat_ID ?>" <?= $selected ?>>
              <?= $category->name ?>
            </option>

          <?php } ?>

        </select>
      </p>

      <p>
        <label>広告リンク先 </label><textarea name="url"><?= $modal_settings['url'] ?? '' // 文字が入っていれば[]の値、入っていなければカラ(?? '')をかえす ?></textarea>
      </p>


      <p><input type="submit" value="登録"></p>

    </form>
  </div>