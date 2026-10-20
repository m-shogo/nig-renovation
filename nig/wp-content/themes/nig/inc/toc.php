<?php

if (!function_exists('nig_prepare_toc_content')) {
  /**
   * コンテンツ内のh2/h3から目次データを作成し、見出しIDを補完したHTMLを返す
   *
   * @param string $content_html
   * @return array{content:string,items:array<int,array{id:string,text:string,level:int}>}
   */
  function nig_prepare_toc_content($content_html)
  {
    $result = array(
      'content' => is_string($content_html) ? $content_html : '',
      'items' => array(),
    );

    if (
      !is_string($content_html) ||
      $content_html === '' ||
      !class_exists('DOMDocument') ||
      !class_exists('DOMXPath')
    ) {
      return $result;
    }

    $previous_internal_errors = libxml_use_internal_errors(true);

    $dom = new DOMDocument('1.0', 'UTF-8');
    $loaded = $dom->loadHTML('<?xml encoding="UTF-8">' . $content_html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    if ($loaded) {
      $xpath = new DOMXPath($dom);

      $used_ids = array();
      $id_nodes = $xpath->query('//*[@id]');
      foreach ($id_nodes as $id_node) {
        $node_id = trim($id_node->getAttribute('id'));
        if ($node_id !== '') {
          $used_ids[$node_id] = true;
        }
      }

      $headings = $xpath->query('//h2 | //h3');
      $fallback_index = 1;

      foreach ($headings as $heading) {
        $heading_text = trim(wp_strip_all_tags($heading->textContent));
        if ($heading_text === '') {
          continue;
        }

        $heading_id = trim($heading->getAttribute('id'));
        if ($heading_id === '') {
          $ascii_text = strtolower(remove_accents($heading_text));
          $ascii_text = preg_replace('/[^a-z0-9\s-]/', '', $ascii_text);
          $ascii_text = preg_replace('/[\s-]+/', '-', trim((string) $ascii_text));
          $base_id = 'section' . ($ascii_text !== '' ? '-' . $ascii_text : '-' . $fallback_index);

          $candidate_id = $base_id;
          $suffix = 2;
          while (isset($used_ids[$candidate_id])) {
            $candidate_id = $base_id . '-' . $suffix;
            $suffix++;
          }

          $heading_id = $candidate_id;
          $heading->setAttribute('id', $heading_id);
          $used_ids[$heading_id] = true;
        }

        $result['items'][] = array(
          'id' => $heading_id,
          'text' => $heading_text,
          'level' => strtolower($heading->tagName) === 'h3' ? 3 : 2,
        );
        $fallback_index++;
      }

      $content_with_toc_ids = $dom->saveHTML();
      $content_without_xml = preg_replace('/^<\?xml[^>]+>\s*/', '', $content_with_toc_ids);
      if (is_string($content_without_xml)) {
        $result['content'] = $content_without_xml;
      } else {
        $result['content'] = $content_with_toc_ids;
      }
    }

    libxml_clear_errors();
    libxml_use_internal_errors($previous_internal_errors);

    return $result;
  }
}

if (!function_exists('nig_render_article_toc')) {
  /**
   * 目次HTMLを描画
   *
   * @param array $toc_items
   * @param string $lang
   * @return void
   */
  function nig_render_article_toc($toc_items, $lang = 'ja')
  {
    if (empty($toc_items) || !is_array($toc_items)) {
      return;
    }

    $toc_nav_label = $lang === 'en' ? 'Table of contents' : '目次';
    $toc_title_label = 'INDEX';
    $toc_open_label = $lang === 'en' ? 'Open' : '開く';
    $toc_close_label = $lang === 'en' ? 'Close' : '閉じる';
    ?>
    <nav class="module_toc-01 js-article-toc" aria-label="<?php echo esc_attr($toc_nav_label); ?>">
      <div class="mt_header">
        <p class="mt_title"><?php echo esc_html($toc_title_label); ?></p>
      </div>
      <ol class="mt_list">
        <?php foreach ($toc_items as $toc_item) : ?>
          <?php
          $toc_id = isset($toc_item['id']) ? (string) $toc_item['id'] : '';
          $toc_text = isset($toc_item['text']) ? (string) $toc_item['text'] : '';
          $toc_level = isset($toc_item['level']) ? (int) $toc_item['level'] : 2;
          if ($toc_id === '' || $toc_text === '') {
            continue;
          }
          ?>
          <li class="mt_item<?php echo $toc_level === 3 ? ' _sub' : ''; ?>">
            <a href="#<?php echo esc_attr($toc_id); ?>">
              <span><?php echo esc_html($toc_text); ?></span>
            </a>
          </li>
        <?php endforeach; ?>
      </ol>
      <div class="mt_footer">
        <button
          type="button"
          class="mt_toggle js-article-toc-toggle"
          aria-expanded="true"
          data-open-label="<?php echo esc_attr($toc_open_label); ?>"
          data-close-label="<?php echo esc_attr($toc_close_label); ?>"
        ><?php echo esc_html($toc_close_label); ?></button>
      </div>
    </nav>
    <?php
  }
}
