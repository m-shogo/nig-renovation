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
    <p class="category <?php echo $taxonomy; ?>">
      <?php foreach ($category as $cat) {
        $parent = $cat->category_parent;
        if ($parent) {
          $parent_term = get_term($parent);
          if (!is_wp_error($parent_term)) {
            $parent_slug = $parent_term->slug;
            $parent_slug = ' _' . $parent_slug;
          }
        }
        echo '<span class="label ' . esc_html($cat->slug) . '' . ($parent ? $parent_slug : '') . '">' . esc_html($cat->$name) . '</span>';
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
    <p class="category <?php echo $taxonomy; ?>">
      <?php foreach ($terms as $term) {
        $parent = $term->parent;
        if ($parent) {
          $parent_term = get_term($parent);
          if (!is_wp_error($parent_term)) {
            $parent_slug = $parent_term->slug;
            $parent_slug = ' _' . $parent_slug;
          }
        }
        echo '<span class="label ' . esc_html($term->slug) . '' . ($parent ? $parent_slug : '') . '">' . esc_html($term->name) . '</span>';
      } ?></p>
  <?php endif; //$terms 
  ?>
<?php endif; ?>