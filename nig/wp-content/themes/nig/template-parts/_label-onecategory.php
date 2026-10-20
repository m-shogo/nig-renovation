<?php
$taxonomy = $args['taxonomy'] ?? '';
if (get_post_type() === 'post'): //通常投稿 
?>
  <?php
  if ($taxonomy === '_cat' || empty($taxonomy)) {
    $category = get_the_category();
    $name = 'cat_name';
  } else {
    $category = get_the_tags();
    $name = 'name';
  }
  if ($category && !is_wp_error($category)): ?>
    <?php
    // 子カテゴリがある場合は子のみ、ない場合は親のみを表示
    $has_child = false;
    foreach ($category as $cat) {
      if (!empty($cat->category_parent)) {
        $has_child = true;
        break;
      }
    }
    $filtered_category = array_filter(
      $category,
      function ($cat) use ($has_child) {
        return $has_child ? !empty($cat->category_parent) : empty($cat->category_parent);
      }
    );
    ?>
    <p class="category <?php echo $taxonomy; ?>">
      <?php foreach ($filtered_category as $cat) {
        $parent = $cat->category_parent;
        $parent_slug = '';
        if ($parent) {
          $parent_term = get_term($parent);
          if (!is_wp_error($parent_term)) {
            $parent_slug = ' _' . $parent_term->slug;
          }
        }
        echo '<span class="label ' . esc_html($cat->slug) . $parent_slug . '">' . esc_html($cat->$name) . '</span>';
      } ?></p>
  <?php endif; //$category 
  ?>
<?php else: //その他カスタム投稿 
?>
  <?php
  if (empty($taxonomy)) {
    $taxonomy = '_cat';
  }
  $postType = esc_html(get_post_type_object(get_post_type())->name);
  $postType_cat = $postType . $taxonomy;
  $terms = get_the_terms($post->ID, $postType_cat);
  if ($terms && !is_wp_error($terms)): ?>
    <?php
    // 子カテゴリがある場合は子のみ、ない場合は親のみを表示
    $has_child = false;
    foreach ($terms as $term) {
      if (!empty($term->parent)) {
        $has_child = true;
        break;
      }
    }
    $filtered_terms = array_filter(
      $terms,
      function ($term) use ($has_child) {
        return $has_child ? !empty($term->parent) : empty($term->parent);
      }
    );
    ?>
    <p class="category <?php echo $taxonomy; ?>">
      <?php foreach ($filtered_terms as $term) {
        $parent = $term->parent;
        $parent_slug = '';
        if ($parent) {
          $parent_term = get_term($parent);
          if (!is_wp_error($parent_term)) {
            $parent_slug = ' _' . $parent_term->slug;
          }
        }
        echo '<span class="label ' . esc_html($term->slug) . $parent_slug . '">' . esc_html($term->name) . '</span>';
      } ?></p>
  <?php endif; //$terms ?>
<?php endif; ?>