<?php get_header(); ?>
<main class="flow">
  <?php
    $args = ['ttl' => '一日の流れ', 'img' => 'kids/flow/icon.png', 'background' => 'kids/flow/kv.jpg'];
    get_template_part('templates/parts/mainTtl', null, $args);
  ?>
  <p class="flow-lead">当園の一日の主な流れをご紹介。<br>ミルク・離乳食・排泄・睡眠など<br>0歳児から一人一人のリズムに合わせて保育を行います。</p>
  <section class="flow-time">
    <?php
      $args = ['img' => 'kids/flow/seven.png','alt' => '7時から9時までのスケジュール'];
      get_template_part('templates/parts/flowTime', null, $args);
    ?>
    <dl class="flow-time_list">
      <div class="flow-time_listInner mod-am">
        <dt class="flow-time_listTime">7:00</dt>
        <dd class="flow-time_listItem">
          <dl>
            <dt class="flow-time_listItemTitle">登園</dt>
            <dd class="flow-time_listItemText">7:00〜9:00の間に、<br class="util-pc">園児それぞれのペースで登園します。</dd>
          </dl>
        </dd>
      </div>
      <div class="flow-time_listInner mod-am">
        <dt class="flow-time_listTime">8:00</dt>
        <dd class="flow-time_listItem">
          <dl>
            <dt class="flow-time_listItemTitle">自由遊び</dt>
            <dd class="flow-time_listItemText">登園後は部屋の中やテラスで自由に遊びます。</dd>
          </dl>
        </dd>
      </div>
    </dl>
    <figure class="flow-time_imageWrap">
      <img class="flow-time_image mod-right js-inView" src="<?php echo output_file("/assets/images/kids/flow/photo_01.jpg"); ?>" alt="">
    </figure>
  </section>
  <section class="flow-time">
    <?php
      $args = ['img' => 'kids/flow/nine.png','alt' => '9時から11時までのスケジュール'];
      get_template_part('templates/parts/flowTime', null, $args);
    ?>
    <dl class="flow-time_list">
      <div class="flow-time_listInner mod-am">
        <dt class="flow-time_listTime">9:00</dt>
        <dd class="flow-time_listItem">
          <dl>
            <dt class="flow-time_listItemTitle">おやつ</dt>
            <dd class="flow-time_listItemText">栄養価の高いおやつを提供しています。</dd>
          </dl>
        </dd>
      </div>
      <div class="flow-time_listInner">
        <dt class="flow-time_listTime">10:30</dt>
        <dd class="flow-time_listItem">
          <dl>
            <dt class="flow-time_listItemTitle">課業・リズム運動</dt>
            <dd class="flow-time_listItemText">お絵かきや工作や公園での運動時間です。</dd>
          </dl>
        </dd>
      </div>
    </dl>
    <figure class="flow-time_imageWrap">
      <img class="flow-time_image mod-left js-inView" src="<?php echo output_file("/assets/images/kids/flow/photo_02.jpg"); ?>" alt="">
    </figure>
  </section>
  <section class="flow-time">
    <?php
      $args = ['img' => 'kids/flow/eleven.png','alt' => '11時から14時までのスケジュール'];
      get_template_part('templates/parts/flowTime', null, $args);
    ?>
    <dl class="flow-time_list">
      <div class="flow-time_listInner mod-am">
        <dt class="flow-time_listTime">11:00</dt>
        <dd class="flow-time_listItem">
          <dl>
            <dt class="flow-time_listItemTitle">食事</dt>
            <dd class="flow-time_listItemText">お腹が空いた子からお昼ご飯。</dd>
          </dl>
        </dd>
      </div>
      <div class="flow-time_listInner">
        <dt class="flow-time_listTime">12:00</dt>
        <dd class="flow-time_listItem">
          <dl>
            <dt class="flow-time_listItemTitle">お昼寝</dt>
            <dd class="flow-time_listItemText">食べ終わって眠くなったらお昼寝です。</dd>
          </dl>
        </dd>
      </div>
    </dl>
    <figure class="flow-time_imageWrap">
      <img class="flow-time_image mod-right js-inView" src="<?php echo output_file("/assets/images/kids/flow/photo_03.jpg"); ?>" alt="">
    </figure>
  </section>
  <section class="flow-time">
    <?php
      $args = ['img' => 'kids/flow/fourteen.png','alt' => '14時から延長保育終了時刻までのスケジュール'];
      get_template_part('templates/parts/flowTime', null, $args);
    ?>
    <dl class="flow-time_list">
      <div class="flow-time_listInner mod-am">
        <dt class="flow-time_listTime">14:00</dt>
        <dd class="flow-time_listItem">
          <dl>
            <dt class="flow-time_listItemTitle">起きた順におやつ・自由遊び</dt>
            <dd class="flow-time_listItemText">目が覚めた子からおやつを食べます。<br>その後はそれぞれ自由遊びになります。</dd>
          </dl>
        </dd>
      </div>
      <div class="flow-time_listInner">
        <dt class="flow-time_listTime">18:00</dt>
        <dd class="flow-time_listItem">
          <dl>
            <dt class="flow-time_listItemTitle">随時降園・延長保育開始</dt>
            <dd class="flow-time_listItemText">延長保育がない子は18:00までに降園します。</dd>
          </dl>
        </dd>
      </div>
      <div class="flow-time_listInner">
        <dt class="flow-time_listTime">20:00</dt>
        <dd class="flow-time_listItem">
          <dl>
            <dt class="flow-time_listItemTitle">延長保育終了</dt>
            <dd class="flow-time_listItemText">延長保育も終了し、みんなお家へ帰ります。</dd>
          </dl>
        </dd>
      </div>
    </dl>
    <figure class="flow-time_imageWrap">
      <img class="flow-time_image mod-left js-inView" src="<?php echo output_file("/assets/images/kids/flow/photo_04.jpg"); ?>" alt="">
    </figure>
  </section>
</main>
<?php get_footer(); ?>