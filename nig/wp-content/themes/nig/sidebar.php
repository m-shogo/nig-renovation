<nav class="local_navigation" id="local_navigation">
  <ul class="ln_links-01 module_menu-01">
    <?php if(have_rows('common-menu-01', 'option')): ?>
      <?php while(have_rows('common-menu-01', 'option')): the_row(); ?>
        <?php
        $title01 = get_sub_field('title-01');
        $url01 = get_sub_field('url-01');
        $target01 = get_sub_field('target-01');
        $select01 = get_sub_field('select-01');
        ?>
        <?php if( $select01 ): //3階層目あり ?>
          <li>
            <a class="lnl_title mm_title" href="<?php echo $url01; ?>" <?php if( $target01 ): ?>target="_blank"<?php endif; //外部リンク ?>><span><?php echo $title01; ?></span></a>
            <div class="lnl_wrapper mm_wrapper-01">
              <div class="lnl_inner mm_inner-01">
                <ul>
                  <?php if(have_rows('hierarchy-02', 'option')): //3階層目出力開始 ?>
                    <?php while(have_rows('hierarchy-02', 'option')): the_row(); ?>
                      <?php
                      $title02 = get_sub_field('title-02');
                      $url02 = get_sub_field('url-02');
                      $target02 = get_sub_field('target-02');
                      $select02 = get_sub_field('select-02');
                      ?>
                      <?php if( $select02 ): //4階層目あり ?>
                        <li>
                          <button class="lnl_button mm_button-01" type="button"><span>開閉</span></button>
                          <a class="lnl_title mm_title-01" href="<?php echo $url02; ?>" <?php if( $target02 ): ?>target="_blank"<?php endif; //外部リンク ?>><span><?php echo $title02; ?></span></a>
                          <div class="lnl_wrapper mm_wrapper-02">
                            <div class="lnl_inner mm_inner-02">
                              <ul>
                                <?php if(have_rows('hierarchy-03', 'option')): //4階層目出力開始 ?>
                                  <?php while(have_rows('hierarchy-03', 'option')): the_row(); ?>
                                    <?php
                                    $title03 = get_sub_field('title-03');
                                    $url03 = get_sub_field('url-03');
                                    $target03 = get_sub_field('target-03');
                                    ?>
                                    <li><a class="lnl_title mm_title-02" href="<?php echo $url03; ?>" <?php if( $target03 ): ?>target="_blank"<?php endif; //外部リンク ?>><span><?php echo $title03; ?></span></a></li>
                                  <?php endwhile; ?>
                                <?php endif; //4階層目出力終了 ?>
                              </ul>
                            </div>
                          </div>
                        </li>
                      <?php else: //4階層目なし ?>
                        <li><a class="lnl_title mm_title-01" href="<?php echo $url02; ?>" <?php if( $target02 ): ?>target="_blank"<?php endif; //外部リンク ?>><span><?php echo $title02; ?></span></a></li>
                      <?php endif; //4階層目分岐終了 ?>
                    <?php endwhile; ?>
                  <?php endif; //3階層目出力終了 ?>
                </ul>
              </div>
            </div>
          </li>
        <?php else: //3階層目なし ?>
        <?php endif; //3階層目分岐終了 ?>
      <?php endwhile; ?>
    <?php endif; //common-menu-01 ?>
  </ul>
</nav>