<?php if (have_rows('inPageLink_items')) : ?>
    <ul class="module_inPageLink-01">
        <?php while (have_rows('inPageLink_items')) : the_row(); ?>
            <?php
            $title = get_sub_field('inPageLink_title');
            $id = get_sub_field('inPageLink_id');
            ?>
            <li class="inPageLink">
                <a href="#<?php echo esc_attr($id); ?>">
                    <div class="title"><?php echo nl2br(esc_html($title)); ?></div>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
<?php endif; ?>