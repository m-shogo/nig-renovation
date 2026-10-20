<?php global $post; ?>
<!DOCTYPE html>
<?php
  $locale = get_locale();
  $lang = explode("_", $locale)[0];
  ?>
<html lang="<?php echo $lang; ?>" dir="ltr" itemscope itemtype="https://schema.org/WebSite">

<head prefix="og: https://ogp.me/ns#">
  <meta charset="utf-8">
  <?php
  $headTag_after = get_field('headTag_after', 'option');
  if ($headTag_after): ?>
    <?php echo $headTag_after; ?>
  <?php endif; ?>
  <?php include get_stylesheet_directory() . '/template-parts/_head_contents.php'; ?>
  <?php wp_head(); ?>
  <?php
  $headTag_before = get_field('headTag_before', 'option');
  if ($headTag_before): ?>
    <?php echo $headTag_before; ?>
  <?php endif; ?>
</head>

<body itemscope itemtype="https://schema.org/WebPage"
  <?php echo body_class() ? body_class() : ''; ?>
  id="<?php echo isset($post) && $post ? esc_attr($post->post_name) : ''; ?>">
  <?php
  $bodyTag_after = get_field('bodyTag_after', 'option');
  if ($bodyTag_after): ?>
    <?php echo $bodyTag_after; ?>
  <?php endif; ?>
  <?php include get_stylesheet_directory() . '/template-parts/_header.php'; ?>
  <div class="global_wrapper">