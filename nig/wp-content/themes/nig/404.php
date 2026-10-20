<?php
$lang = explode('_', get_locale())[0];
get_header(); ?>
<main id="global_contents" class="global_contents" itemscope itemprop="mainContentOfPage">
  <section>
    <?php get_template_part('visual'); ?>
    <div class="content_inner">
      <div class="gc_main _oneColumn">
        <p><?php echo $lang === 'en'
          ? 'Thank you for visiting our website. We apologize, but the page you were trying to access has been deleted or the URL has changed. Please use the search box below to find the page you are looking for.'
          : 'いつも当サイトをご覧頂きありがとうございます。<br>申し訳ございませんが、あなたがアクセスしようとしたページは削除されたかURLが変更されています。<br>お手数をおかけしますが、下記の検索ボックスからもう一度目的のページをお探しください。'; ?></p>
        <div class="module_search-01">
          <form class="ms_form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <input class="ms_input" type="search" name="s" placeholder="<?php echo $lang === 'en' ? 'Search' : 'サイト内検索'; ?>">
            <button class="ms_button" type="submit"><span></span></button>
          </form>
        </div>
        <div class="block-editor_wrap">
          <div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
          <div class="wp-block-buttons is-content-justification-center is-layout-flex wp-block-buttons-is-layout-flex">
            <div class="wp-block-button"><a href="<?php echo esc_url(home_url('/')); ?>" class="wp-block-button__link has-text-align-left wp-element-button"><?php echo $lang === 'en' ? 'Back to TOP' : 'TOPへ戻る'; ?></a></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>