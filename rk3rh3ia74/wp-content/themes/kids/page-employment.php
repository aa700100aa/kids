<?php get_header(); ?>

<main>
  <div class='employment-outer'>
    <div class='employment-kv'>
      <div class='employment-kv_inner js-inView'>
        <div class='employment-kv_textArea'>
          <h1 class='employment-kv_textArea_ttl'>採用情報</h1>
          <p class='employment-kv_textArea_subTtl'>縦でもなく横でもない円の会社<br>GFDで一緒に働きませんか。</p>
        </div>
        <figure class="employment-kv_img">
          <img src="<?php echo output_file("/assets/images/employment/kv.svg"); ?>" alt="">
        </figure>
      </div>
    </div>
    <section class='employment-lead'>
      <h2 class='employment-lead_ttl js-inView'>仲間とともに、笑顔ではたらく</h2>
      <p class='employment-lead_paragraph js-inView'>GFDが大事にしていることは、「誰と」仕事をするのかということ。<br class='util-pc'>仕事の状況によってはとても忙しい辛い仕事があるかもしれません。<br class='util-pc'>場合によっては意にそぐわない、仕事内容の時もあるかもしれません。<br class='util-pc'>しかしそのような時に、「誰と」その仕事を共にしているのかが、とても重要になると考えています。<br class='util-pc'>また、自らを成長させる機会、挑戦できる場所を見つけ、<br class='util-pc'>自分らしい「働き方」、家族との時間を大切にすることができる<br class='util-pc'>環境づくりに取り組んでいます。</p>
      <div class='employment-lead_site js-inView'>
        <h3 class='employment-lead_site_ttl'>GFDをより知りたい方はこちら<span class='employment-lead_arrow'></span></h3>
        <a class="employment-lead_site_link" href="https://www.instagram.com/gfd_pr/" target='_blank'><span class='employment-lead_site_img'><img src="<?php echo output_file("/assets/images/employment/instagram.png"); ?>" alt=""></span><span class='employment-lead_site_text'>GFD公式インスタグラム<br>@gfd_pr</span></a>
      </div>
    </section>
    <section class='employment-recruit'>
      <div class='employment-recruit_board'>
        <div class='employment-recruit_board_inner'>
          <h2 class='employment-recruit_ttl'><span class='employment-recruit_ttl_en'>RECRUIT</span><span class='employment-recruit_ttl_jp'>募集要項</span></h2>
          <?php
            $args = array(
              'post_type' => 'employment-info', //カスタム投稿タイプ名
              'orderby'=> 'menu_order'
            );
            $my_query = new WP_Query( $args );
          if($my_query->have_posts()): ?>
          <ul class="employment-recruit_list">
          <?php
            while ( $my_query->have_posts() ) : $my_query->the_post();
            $thisID = get_the_ID();
            $thisField = get_field('employment-group', $thisID);
          ?>
            <li class="employment-recruit_item js-employmentRecruitItem">
              <h3 class='employment-recruit_item_name js-employmentRecruitItemName'>
                <button class='employment-recruit_item_button'><span class='employment-recruit_item_check'></span><span class='employment-recruit_item_button_text'><?php the_title(); ?></span><span class='employment-recruit_item_plus'></span></button>
              </h3>
              <div class='employment-recruit_accordion_outerWrap'>
                <div class='employment-recruit_accordion_outer '>
                  <dl class="employment-recruit_accordion js-employmentRecruitAccordion">
                    <?php if($thisField['employment-group-jobDescription']) :?>
                      <div class='employment-recruit_accordion_inner'>
                        <dt class='employment-recruit_accordion_term'>業務内容</dt>
                        <dd class='employment-recruit_accordion_desc'><?php echo nl2br($thisField['employment-group-jobDescription']) ?></dd>
                      </div>
                    <?php endif; ?>
                    <?php if($thisField['employment-group-skills']) :?>
                    <div class='employment-recruit_accordion_inner'>
                      <dt class='employment-recruit_accordion_term'>求めるスキル</dt>
                      <dd class='employment-recruit_accordion_desc'><?php echo nl2br($thisField['employment-group-skills']) ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($thisField['employment-group-workPlace']) :?>
                    <div class='employment-recruit_accordion_inner'>
                      <dt class='employment-recruit_accordion_term'>勤務地</dt>
                      <dd class='employment-recruit_accordion_desc'><?php echo nl2br($thisField['employment-group-workPlace']) ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($thisField['employment-group-salary']) :?>
                    <div class='employment-recruit_accordion_inner'>
                      <dt class='employment-recruit_accordion_term'>給与</dt>
                      <dd class='employment-recruit_accordion_desc'><?php echo nl2br($thisField['employment-group-salary']) ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($thisField['employment-group-workingHours']) :?>
                    <div class='employment-recruit_accordion_inner'>
                      <dt class='employment-recruit_accordion_term'>勤務時間</dt>
                      <dd class='employment-recruit_accordion_desc'><?php echo nl2br($thisField['employment-group-workingHours']) ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($thisField['employment-group-holiday']) :?>
                    <div class='employment-recruit_accordion_inner'>
                      <dt class='employment-recruit_accordion_term'>休日</dt>
                      <dd class='employment-recruit_accordion_desc'><?php echo nl2br($thisField['employment-group-holiday']) ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($thisField['employment-group-benefits']) :?>
                    <div class='employment-recruit_accordion_inner'>
                      <dt class='employment-recruit_accordion_term'>待遇・<br class='util-pc'>福利厚生</dt>
                      <dd class='employment-recruit_accordion_desc'><?php echo nl2br($thisField['employment-group-benefits']) ?></dd>
                    </div>
                    <?php endif; ?>
                    <?php if($thisField['employment-group-free']) :
                    $freeGroup = $thisField['employment-group-free'];
                      if($freeGroup):
                        foreach($freeGroup as $group): 
                      ?>
                      <div class='employment-recruit_accordion_inner'>
                        <dt class='employment-recruit_accordion_term'><?php echo nl2br($group['employment-group-free-term']) ?></dt>
                        <dd class='employment-recruit_accordion_desc'><?php echo nl2br($group['employment-group-free-desc']) ?></dd>
                      </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    <?php endif; ?>
                </dl>
                <div class='employment-entry js-employment-entry'>
                  <div class='employment-entry_inner'>
                    <a class="employment-entry_link" 
                      <?php if($thisField['employment-group-entryUrl']) :?>
                        href="<?php echo $thisField['employment-group-entryUrl'] ?>" target="_blank"
                      <?php else : ?>
                        href="<?php echo home_url(); ?>/contact/?employment=1&jobname=<?php echo urlencode(get_the_title()); ?>"
                      <?php endif; ?>
                    >
                      <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 250 89" class='employment-entry_svg'>
                        <defs>
                          <clipPath class='employment-entry_clipPath'>
                            <path style="fill:none" d="M0 0h250v89H0z"/>
                          </clipPath>
                        </defs>
                        <g style="clip-path:url(.employment-entry_clipPath);fill:none">
                          <path class='employment-entry_path mod-border' d="M235.569 83.02c-.477 2.708-3.118 4.924-5.868 4.924H6.24c-2.75 0-4.609-2.216-4.132-4.924L15.671 6.092c.477-2.708 3.118-4.924 5.868-4.924H245c2.75 0 4.609 2.216 4.132 4.924L235.569 83.02Z"/>
                          <path class='employment-entry_path mod-box' d="M235.569 83.02c-.477 2.708-3.118 4.924-5.868 4.924H6.24c-2.75 0-4.609-2.216-4.132-4.924L15.671 6.092c.477-2.708 3.118-4.924 5.868-4.924H245c2.75 0 4.609 2.216 4.132 4.924L235.569 83.02Z"/>
                          <path class='employment-entry_path mod-text' d="m214.66 44.556-8.794 7.523v5.569l15.303-13.092-15.303-13.091v5.568l8.794 7.523zM45.53 28.395h21.654l-1.031 5.892H51.176l-1.375 7.856h13.11l-1.031 5.794H48.77l-1.424 7.955H63.01l-1.08 5.893H39.637l5.893-33.39ZM80.54 45.826c-1.473-1.768-2.946-4.518-3.094-4.763 0 .098-.196 3.781-.638 6.088l-2.603 14.633H67.92l5.941-33.685h.638l13.602 16.302c1.424 1.719 2.946 4.518 3.094 4.812 0-.098.147-3.584.59-6.138l2.602-14.682h6.285l-5.941 33.684h-.638L80.54 45.824ZM113.784 34.287h-9.379l1.031-5.892h25.436l-1.031 5.892h-9.28L115.7 61.785h-6.776l4.861-27.498ZM143.492 50.098h-5.058l-2.013 11.687h-6.728l5.843-33.39h11.146c6.285 0 11.245 3.437 11.294 10.164 0 5.401-3.29 9.133-7.954 10.705l5.695 12.521h-7.07l-5.156-11.687Zm1.522-5.598c3.83 0 6.089-2.111 6.089-5.647 0-2.995-2.161-4.616-5.156-4.616h-4.714L139.417 44.5h5.598ZM171.09 49.41l-7.611-21.016h7.218l3.094 8.937c.884 2.553 1.571 6.727 1.571 6.727s2.014-4.026 3.732-6.58l6.187-9.084h7.12l-14.584 20.967-2.16 12.423h-6.728l2.161-12.374Z"/>
                        </g>
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </li>
          <?php endwhile; ?>
          <?php wp_reset_postdata(); ?>
          <?php else : ?>
            <h3 class='employment-recruit_subTtl' id='js-employmentRecruitSubTtl'>現在は募集しておりません。</h3>
          <?php endif; ?>
        </div>
      </div>
    </section>
    <section class='employment-privacy'>
      <h2 class='employment-privacy_ttl'>採用希望者の個人情報のお取扱いについて</h2>
      <p class='employment-privacy_lead '>ご提供いただく採用希望者の個人情報のお取扱いについて、以下の通り明示いたします。</p>
      <dl class="employment-privacy_item">
        <dt class='employment-privacy_term'>個人情報の利用目的</dt>
        <dd class='employment-privacy_desc'>人材採用業務を適切に実施するため。</dd>
        <dt class='employment-privacy_term'>個人情報の第三者提供について</dt>
        <dd class='employment-privacy_desc'>本人の同意がある場合又は法令に基づく場合を除き、取得した個人情報を第三者に提供することはありません。</dd>
        <dt class='employment-privacy_term'>委託</dt>
        <dd class='employment-privacy_desc'>個人情報の取扱いを委託する予定はありません。</dd>
        <dt class='employment-privacy_term'>開示対象個人情報の開示等および問い合わせ窓口について</dt>
        <dd class='employment-privacy_desc'>ご本人からの求めにより、当社が保有する開示対象個人情報の利用目的の通知、開示、内容の訂正・追加または削除、利用の停止・消去および第三者への提供の停止（「開示等」といいます。）を受け付けております。 開示等を受け付ける窓口は、以下の「個人情報苦情及び相談窓口」をご覧下さい。</dd>
        <dt class='employment-privacy_term'>個人情報を入力するにあたっての注意事項</dt>
        <dd class='employment-privacy_desc'>個人情報のご提供は任意です。ただし、採用選考業務に必要な情報をご提供いただかない場合、同業務の実施に支障が生じる可能性があります。</dd>
        <dt class='employment-privacy_term'>本人が容易に認識できない方法による個人情報の取得</dt>
        <dd class='employment-privacy_desc'>クッキーやウェブビーコン等を用いるなどして、本人が容易に認識できない方法による個人情報の取得は行っておりません。</dd>
        <dt class='employment-privacy_term'>電子メールご利用の際のご注意</dt>
        <dd class='employment-privacy_desc'>電子メールは平文で送られますので、盗聴や改ざんが比較的容易とされています。安全性を高めつつ電子メールをお使いになりたいような場合には、メール本文を暗号化するなど、利用者ご自身で対策を施してください。</dd>
        <dt class='employment-privacy_term mod-underLine'>個人情報苦情及び相談窓口</dt>
        <dd class='employment-privacy_desc'>
          <address>株式会社GFD<br>〒221-0056 神奈川県横浜市神奈川区金港町5-14 クアドリフォリオ 3階<br><a class="employment-privacy_link" href="mailto:kanri@gf-design.jp" target='_blank' >kanri@gf-design.jp</a><br>個人情報保護管理者：取締役・管理本部長<br>TEL:045-440-0327</address>
          <small>（受付時間 10時～18時 土日祝日除く・営業のお電話はお断りいたします）</small>
        </dd>
      </dl>
      <?php get_template_part('templates/parts/privacyMark'); ?>
    </section>
  </div>
</main>

<?php get_footer();
