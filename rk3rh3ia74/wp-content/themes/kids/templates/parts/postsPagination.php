<div class="part-paginationWrap">
  <?php
  $query = $args;

  $current_page = max(1, get_query_var('paged')); // 現在のページを取得
  $total_pages = ceil($query->found_posts / $posts_per_page); // 総ページ数を取得

  // $mid_sizeの条件分岐
  if ($current_page >= 1 && $current_page <= 3) {
    // 1~3ページまで
    $mid_size = 4 - ($current_page - 1);
    $first_page_hide = false;
  } elseif ($current_page >= $total_pages - 2 && $current_page <= $total_pages) {
    // 最後、最後の1つ前ページまで
    $mid_size = 4 - ($total_pages - $current_page);
    $first_page_hide = true;
  } else {
    // 上記以外
    $mid_size = 2;
    $first_page_hide = true;
  }

  $pagination_links = paginate_links(array(
    'current' => $current_page,
    'total'   => $total_pages, // 総ページ数
    'mid_size' => $mid_size, // 現在のページの両側の数字の数
    'prev_text' => 'prev', // 前へのテキストを変更
    'next_text' => 'next', // 次へのテキストを変更
    'type' => 'array', // ページネーションのリンクを配列として取得
  ));

  if ($pagination_links) {
    $check_array = $pagination_links;
    if (!strpos(array_shift($check_array), 'prev')) {
      $disabled_prev = '<span class="prev page-numbers disabled">prev</span>';
      array_unshift($pagination_links, $disabled_prev);
    }
    if (!strpos(end($check_array), 'next')) {
      $disabled_next = '<span class="next page-numbers disabled">next</span>';
      array_push($pagination_links, $disabled_next);
    }

    foreach ($pagination_links as $key => $link) {

      if (strpos($link, 'prev')) {
        echo $link;
        echo '<div class="page-numbersWrap">';
      } elseif (strpos($link, 'next')) {
        echo '</div>';
        echo $link;
      } else {
        if ($first_page_hide) {
          if ($current_page === 4 && $total_pages === 4 && $key === 1) {
            $link = '<span class="page-numbers dots">…</span>';
          } elseif ($current_page >= 5 && $total_pages != 6 && $key === 1 ) {
            $link = null;
          }
        }
        echo $link;
      }
    }
  }
  ?>
</div>