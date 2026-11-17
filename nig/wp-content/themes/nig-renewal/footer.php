<?php
$lang = nig_renewal_current_lang();
$logo = $lang === 'ja' ? 'logo.svg' : 'logo-en.svg';
?>
</div><!-- /global_wrapper -->
<footer id="global_footer" class="global_footer">
  <div class="gf_information">
    <div class="global_inner">
      <div class="infoWrap">
        <p class="gf_logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/images/common/' . $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></a></p>
        <address>
          <p class="gf_address">
            <?php if ( $lang === 'en' ) : ?>
              Inter-University Research Institute Corporation<br>
              Research Organization of Information and Systems<br>
              National Institute of Genetics<br><br>
              1111 Yata, Mishima, Shizuoka 411-8540, Japan
            <?php else : ?>
              大学共同利用機関法人 情報・システム研究機構<br>
              国立遺伝学研究所<br><br>
              〒411-8540　静岡県三島市谷田1111
            <?php endif; ?>
          </p>
        </address>
        <div class="submenu">
          <ul>
            <li><a href="<?php echo esc_url( home_url( '/sitemap/' ) ); ?>"><span><?php echo $lang === 'en' ? 'Sitemap' : 'サイトマップ'; ?></span></a></li>
            <li><a href="<?php echo esc_url( home_url( '/sitepolicy/' ) ); ?>"><span><?php echo $lang === 'en' ? 'Sitepolicy' : 'サイトポリシー'; ?></span></a></li>
            <li><a href="<?php echo esc_url( home_url( '/public-disclosures/' ) ); ?>"><span><?php echo $lang === 'en' ? 'Public Disclosures' : '情報公開'; ?></span></a></li>
            <?php if ( $lang === 'ja' ) : ?>
            <li><a href="<?php echo esc_url( home_url( '/public-disclosures/chotatsu/' ) ); ?>"><span>調達情報</span></a></li>
            <?php endif; ?>
          </ul>
        </div>
        <div class="sns">
          <ul>
            <li class="_twitter"><a href="https://x.com/NIG_idenken" target="_blank" rel="noopener noreferrer"><?php get_template_part( 'ico/ico', 'twitter' ); ?></a></li>
            <li class="_youtube"><a href="https://www.youtube.com/user/NIGchannel" target="_blank" rel="noopener noreferrer"><?php get_template_part( 'ico/ico', 'youtube' ); ?></a></li>
            <li class="_facebook"><a href="<?php echo $lang === 'en' ? 'https://www.facebook.com/people/National-Institute-of-Genetics-Official/100064809672618' : 'https://www.facebook.com/people/%E5%9B%BD%E7%AB%8B%E9%81%BA%E4%BC%9D%E5%AD%A6%E7%A0%94%E7%A9%B6%E6%89%80/100057459939425/'; ?>" target="_blank" rel="noopener noreferrer"><?php get_template_part( 'ico/ico', 'facebook' ); ?></a></li>
          </ul>
        </div>
      </div>
      <div class="menuWrap">
        <div class="cardmenu">
          <ul>
            <li><a href="<?php echo $lang === 'en' ? 'https://www.rois.ac.jp/en/index.html' : 'https://www.rois.ac.jp/index.html'; ?>" target="_blank" rel="noopener noreferrer"><span><?php echo $lang === 'en' ? 'Research Organization of Information and Systems' : '情報・システム研究機構'; ?></span></a></li>
            <li><a href="<?php echo $lang === 'en' ? 'https://www.nipr.ac.jp/english/' : 'https://www.nipr.ac.jp'; ?>" target="_blank" rel="noopener noreferrer"><span><?php echo $lang === 'en' ? 'National Institute of Polar Research' : '国立極地研究所'; ?></span></a></li>
            <li><a href="<?php echo $lang === 'en' ? 'https://www.nii.ac.jp/en/' : 'https://www.nii.ac.jp'; ?>" target="_blank" rel="noopener noreferrer"><span><?php echo $lang === 'en' ? 'National Institute of Informatics' : '国立情報学研究所'; ?></span></a></li>
            <li><a href="<?php echo $lang === 'en' ? 'https://www.ism.ac.jp/index_e.html' : 'https://www.ism.ac.jp/index.html'; ?>" target="_blank" rel="noopener noreferrer"><span><?php echo $lang === 'en' ? 'Research Organaization of Information and Systems, The Institute of Statistical Mathematics' : '統計数理研究所'; ?></span></a></li>
            <li><a href="<?php echo $lang === 'en' ? 'https://fs.rois.ac.jp/en/' : 'https://fs.rois.ac.jp'; ?>" target="_blank" rel="noopener noreferrer"><span><?php echo $lang === 'en' ? 'Co-creation Center for Future Science Systems' : '未来科学システム共創施設'; ?></span></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <p class="gf_copyright"><span>Copyright © National Institute of Genetics</span></p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
