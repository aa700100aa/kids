<?php
if(is_category()) {
  $current_category = $args;
  if ($current_category->parent == 0) {
    $category = $current_category;
  } else {
    $parent_category = get_term($current_category->parent, 'category');
    $category = $parent_category;
  }
}

if ($category) {
  $args = array(
    'child_of' => $category->term_id,
  );
  $child_categories = get_categories($args);
}
?>
<aside class="part-sideMenu">
  <ul class="part-sideMenuList">
    <?php if($current_category->parent == 0): ?>
    <li class="part-sideMenuItem">
      <span class="part-sideMenuItem_link add-selected">ALL</span>
    </li>
    <?php else: ?>
    <li class="part-sideMenuItem">
      <a href="<?php echo get_category_link($category->term_id); ?>" class="part-sideMenuItem_link">ALL</a>
    </li>
    <?php endif; ?>
    <?php 
    if($child_categories): 
    foreach ($child_categories as $child_category):
    if($current_category->term_id == $child_category->term_id):
    ?>
    <li class="part-sideMenuItem">
      <span class="part-sideMenuItem_link add-selected"><?php echo $child_category->name; ?></span>
    </li>
    <?php else: ?>
    <li class="part-sideMenuItem">
      <a href="<?php echo get_category_link($child_category->term_id) ?>" class="part-sideMenuItem_link"><?php echo $child_category->name; ?></a>
    </li>
    <?php endif; endforeach; endif; ?>
  </ul>
</aside>