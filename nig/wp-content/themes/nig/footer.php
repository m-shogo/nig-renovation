    </div><!-- /global_wrapper -->
    <?php include get_stylesheet_directory() . '/template-parts/_footer.php'; ?>
    <?php wp_footer(); ?>
    <?php
    $bodyTag_before = get_field('bodyTag_before', 'option');
    if ($bodyTag_before): ?>
        <?php echo $bodyTag_before; ?>
    <?php endif; ?>
    </body>
</html>