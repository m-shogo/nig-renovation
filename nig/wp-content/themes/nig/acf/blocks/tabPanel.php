<?php
$panel_title = get_field('panel_title') ?: '';
$panel_id = 'tab-' . uniqid(); // 万一JSでID未設定の場合の保険
?>

<div class="tab-panel" data-title="<?php echo esc_attr($panel_title); ?>" id="<?php echo esc_attr($panel_id); ?>">
    <InnerBlocks />
</div>