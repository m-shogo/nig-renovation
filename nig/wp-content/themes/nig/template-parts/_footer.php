<?php
  $locale = get_locale();
  $lang = explode("_", $locale)[0];
  $front_page_id = get_option('page_on_front');
  $lang_path = '';
  $logo = 'logo.svg';
  if ($lang !== 'ja') {
    $lang_path = '/' . $lang;
    $logo = 'logo-'.$lang.'.svg';
  }
?>
<footer id="global_footer" class="global_footer" itemscope itemtype="https://schema.org/WPFooter">
  <div class="gf_information">
    <div class="global_inner">
      <div class="infoWrap">
        <p class="gf_logo"><a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/common/<?php echo $logo ?>" alt="<?php bloginfo('name'); ?>"></a></p>
        <address>
          <p class="gf_address">
            <?php if ($lang === 'en'): ?>
              Inter-University Research Institute Corporation<br>
              Research Organization of Information and Systems<br>
              National Institute of Genetics<br><br>
              1111 Yata, Mishima, Shizuoka 411-8540, Japan
            <?php else: ?>
              大学共同利用機関法人 情報・システム研究機構<br>
              国立遺伝学研究所<br><br>
              〒411-8540　静岡県三島市谷田1111
            <?php endif; ?>
          </p>
        </address>
        <div class="submenu">
          <ul>
            <li>
              <a href="<?php echo esc_url(home_url($lang_path . '/sitemap/')); ?>">
                <span><?php echo $lang === 'en' ? 'Sitemap' : 'サイトマップ'; ?></span>
              </a>
            </li>
            <li>
              <a href="<?php echo esc_url(home_url($lang_path . '/sitepolicy/')); ?>">
                <span><?php echo $lang === 'en' ? 'Sitepolicy' : 'サイトポリシー'; ?></span>
              </a>
            </li>
            <li>
              <a href="<?php echo esc_url(home_url($lang_path . '/public-disclosures/')); ?>">
                <span><?php echo $lang === 'en' ? 'Public Disclosures' : '情報公開'; ?></span>
              </a>
            </li>
            <?php if($lang === 'ja'): ?>
              <li>
                <a href="<?php echo esc_url(home_url($lang_path . '/public-disclosures/chotatsu/')); ?>">
                  <span>調達情報</span>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
        <div class="sns">
          <ul>
            <li class="_twitter"><a href="https://x.com/NIG_idenken" target="_blank"><?php get_template_part( 'ico/ico', 'twitter' ); ?></a></li>
            <li class="_youtube"><a href="https://www.youtube.com/user/NIGchannel" target="_blank"><?php get_template_part( 'ico/ico', 'youtube' ); ?></a></li>
            <li class="_facebook"><a href="https://www.facebook.com/people/%E5%9B%BD%E7%AB%8B%E9%81%BA%E4%BC%9D%E5%AD%A6%E7%A0%94%E7%A9%B6%E6%89%80/100057459939425/#" target="_blank"><?php get_template_part( 'ico/ico', 'facebook' ); ?></a></li>
          </ul>

        </div>
      </div>
      <div class="menuWrap">
        <div class="cardmenu">
          <ul>
            <li>
              <a href="<?php echo $lang === 'en' ? 'https://www.rois.ac.jp/en/index.html' : 'https://www.rois.ac.jp/index.html'; ?>" target="_blank">
                <span><?php echo $lang === 'en' ? 'Research Organization of Information and Systems' : '情報・システム研究機構'; ?></span>
              </a>
            </li>
            <li>
              <a href="<?php echo $lang === 'en' ? 'https://www.nipr.ac.jp/english/' : 'https://www.nipr.ac.jp'; ?>" target="_blank">
                <span><?php echo $lang === 'en' ? 'National Institute of Polar Research' : '国立極地研究所'; ?></span>
              </a>
            </li>
            <li>
              <a href="<?php echo $lang === 'en' ? 'https://www.nii.ac.jp/en/' : 'https://www.nii.ac.jp'; ?>" target="_blank">
                <span><?php echo $lang === 'en' ? 'National Institute of Informatics' : '国立情報学研究所'; ?></span>
              </a>
            </li>
            <li>
              <a href="<?php echo $lang === 'en' ? 'https://www.ism.ac.jp/index_e.html' : 'https://www.ism.ac.jp/index.html'; ?>" target="_blank">
                <span><?php echo $lang === 'en' ? 'Research Organaization of Information and Systems, The Institute of Statistical Mathematics' : '統計数理研究所'; ?></span>
              </a>
            </li>
            <li>
              <a href="<?php echo $lang === 'en' ? 'https://ds.rois.ac.jp/en/' : 'https://ds.rois.ac.jp/'; ?>" target="_blank">
                <span><?php echo $lang === 'en' ? 'Joint Support-Center for Data Science Research' : 'データサイエンス共同利用基盤施設'; ?></span>
              </a>
            </li>
          </ul>
        </div>
        <div class="menu">
          <?php if(have_rows('common-submenu-02' , $front_page_id)): //サブメニュー ?>
            <ul>
              <?php while(have_rows('common-submenu-02', $front_page_id)): the_row(); ?>
                <?php
                  $title = get_sub_field('title');
                  $url = get_sub_field('url');
                  $target = get_sub_field('target');
                ?>
                <li><a class="title" href="<?php echo $url; ?>" <?php if ($target): ?>target="_blank" <?php endif; ?>><i class="fa-regular fa-angle-right"></i><span><?php echo $title; ?></span></a></li>
              <?php endwhile; ?>
            </ul>
          <?php endif; //サブメニュー ?>
          <?php if(have_rows('common-submenu-03' , $front_page_id)): //サブメニュー ?>
            <ul>
              <?php while(have_rows('common-submenu-03', $front_page_id)): the_row(); ?>
                <?php
                  $title = get_sub_field('title');
                  $url = get_sub_field('url');
                  $target = get_sub_field('target');
                ?>
                <li><a class="title" href="<?php echo $url; ?>" <?php if ($target): ?>target="_blank" <?php endif; ?>><i class="fa-regular fa-angle-right"></i><span><?php echo $title; ?></span></a></li>
              <?php endwhile; ?>
            </ul>
          <?php endif; //サブメニュー ?>
        </div>
      </div>
    </div>
  </div>
  <!-- <p id="js_gf_pageTop" class="gf_pageTop"><a href="#"><span>ページトップへ戻る</span></a></p> -->
  <p class="gf_copyright"><span>Copyright © National Institute of Genetics</span></p>
</footer>