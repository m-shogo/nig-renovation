<?php
$accordion_type = get_field('accordion_type');
?>
<?php if (have_rows('accordion_items')) : ?>
    <ul class="module_accordion-01 <?php echo $accordion_type === 'faq' ? '_qa' : ''; ?>">
        <?php while (have_rows('accordion_items')) : the_row(); ?>
            <?php
            $title = get_sub_field('accordion_title');
            $content = get_sub_field('accordion_content');
            ?>
            <li class="accordion">
                <div class="head">
                    <div class="title"><?php echo nl2br(esc_html($title)); ?></div>
                    <div class="button"><span></span></div>
                </div>
                <div class="body" style="display: none;">
                    <div class="text"><?php echo $content; ?></div>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>
<?php endif; ?>