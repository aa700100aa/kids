<footer class='footer'>
  <div class='footer-innerContainer'>
    <div class='footer-inner'>
      <div class='footer-contact'>
        <div class="footer-contact_logoWrap">
          <img class="footer-contact_icon" src="<?php echo output_file("/assets/images/kids/common/icon.svg"); ?>" alt="">
          <img class="footer-contact_title" src="<?php echo output_file("/assets/images/kids/common/title.svg"); ?>" alt="あらかわ保育園">
        </div>
        <address class='footer-contact_address'>〒000-0000 神奈川県XXX市XXX区XX-XX-XX</address>
      </div>
      <div class='footer-otherPages'>
        <ul class='footer-otherPages_inner mod-center'>
          <li class='footer-otherPages_item'>
            <a class="footer-otherPages_link" href="<?php echo home_url(); ?>" >TOP</a>
          </li>
          <?php if (is_front_page()) : ?>
            <li class='footer-otherPages_item'>
              <a class="footer-otherPages_link js-smoothScroll" href="#about" >当園について</a>
            </li>
          <?php else : ?>
            <li class='footer-otherPages_item'>
              <a class="footer-otherPages_link" href="<?php echo home_url(); ?>/#about" >当園について</a>
            </li>
          <?php endif; ?>
          <li class='footer-otherPages_item'>
            <a class="footer-otherPages_link" href="<?php echo home_url(); ?>/flow/" >一日の流れ</a>
          </li>
          <li class='footer-otherPages_item'>
            <a class="footer-otherPages_link" href="<?php echo home_url(); ?>/category/news/" >お知らせ</a>
          </li>
          <li class='footer-otherPages_item'>
            <a class="footer-otherPages_link" href="<?php echo home_url(); ?>/contact/" >お問い合わせ</a>
          </li>
        </ul>
      </div>
    </div>
    <div class='footer-notes'>
      <div class='footer-notes_inner'>
        <a class="footer-notes_link" href="#policy" >個人情報保護方針</a>
        <div class='footer-notes_separator'></div>
        <a class="footer-notes_link mod-last" href="#security" >情報セキュリティ方針</a>
      </div>
      <small class='footer-notes_copyRight'>Copyright © 2023 Arakawa Hoikuen All Right Reserved.</small>
    </div>
  </div>
</footer>