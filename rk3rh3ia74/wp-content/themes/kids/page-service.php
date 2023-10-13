<?php get_header(); ?>

<main>
  <div class='service-outer'>
    <div class='service-kv js-inView' id='js-serviceKv'>
      <div class='service-kv_inner'>
        <div class='service-kv_textArea'>
          <h1 class='service-kv_textArea_ttl'>事業紹介</h1>
          <p class='service-kv_textArea_subTtl'><img class='service-kv_textArea_subTtl_img' src="<?php echo output_file("/assets/images/service/kv/title.svg"); ?>" alt="de facto standard"><br><span class='service-kv_textArea_subTtl-small'>社会における事実上の標準となる<br>ITインフラ・サイバーセキュリティを</span></p>
        </div>
        <div class='service-kv_visual' id='js-serviceKvVisual'>
          <picture class='service-kv_visual_inner' id='js-serviceKvVisualInner'>
            <source media="(min-width: 768px)" srcset="<?php echo output_file("/assets/images/service/kv/pc/kv.svg"); ?>">
            <img src="<?php echo output_file("/assets/images/service/kv/kv.png"); ?>" alt="">
          </picture>
        </div>
      </div>
    </div>

    <section class='service-nav'>
      <h2 class='service-nav_ttl js-inView'>GFDが提供するサービス</h2>
      <p class='service-nav_ttl_paragraph js-inView'>私たちは、人と社会をつなぎ、安心・安全を創造し未来へのかけ橋となる、<br class='util-pc'>ITインフラ・サイバーセキュリティにフォーカスしたテクノロジーカンパニーです。<br>４つのサービス領域で、希望あふれる未来を創っていきます。</p>
      <nav class='service-localNav js-inView'>
        <ol class="service-localNav_list">
          <li class="service-localNav_item mod-security">
            <a class="service-localNav_link js-serviceLink" href="#security">
              <picture class='service-localNav_number'>
                <source media="(min-width: 768px)" srcset="<?php echo output_file("/assets/images/service/nav/pc/01.svg"); ?>">
                <img src="<?php echo output_file("/assets/images/service/nav/01.svg"); ?>" alt="SERVICE 01">
              </picture>
              <span class='service-localNav_visual util-pc'>
                <span class='service-localNav_visual_inner'>
                  <img class='service-localNav_visual_img' src="<?php echo output_file("/assets/images/service/nav/pc/security.png"); ?>" alt="">
                </span>
              </span>
              <span class='service-localNav_ttl'><img src="<?php echo output_file("/assets/images/service/nav/security.svg"); ?>" alt="Security"></span>
            </a>
          </li>
          <li class="service-localNav_item mod-resale">
            <a class="service-localNav_link js-serviceLink" href="#resale">
              <picture class='service-localNav_number'>
                <source media="(min-width: 768px)" srcset="<?php echo output_file("/assets/images/service/nav/pc/02.svg"); ?>">
                <img src="<?php echo output_file("/assets/images/service/nav/02.svg"); ?>" alt="SERVICE 02">
              </picture>
              <span class='service-localNav_visual util-pc'>
                <span class='service-localNav_visual_inner'>
                  <img class='service-localNav_visual_img' src="<?php echo output_file("/assets/images/service/nav/pc/resale.png"); ?>" alt="">
                </span>
              </span>
              <span class='service-localNav_ttl'><img src="<?php echo output_file("/assets/images/service/nav/resale.svg"); ?>" alt="Resale"></span>
            </a>
          </li>
          <li class="service-localNav_item mod-solution">
            <a class="service-localNav_link js-serviceLink" href="#solution">
              <picture class='service-localNav_number'>
                <source media="(min-width: 768px)" srcset="<?php echo output_file("/assets/images/service/nav/pc/03.svg"); ?>">
                <img src="<?php echo output_file("/assets/images/service/nav/03.svg"); ?>" alt="SERVICE 03">
              </picture>
              <span class='service-localNav_visual util-pc'>
                <span class='service-localNav_visual_inner'>
                  <img class='service-localNav_visual_img' src="<?php echo output_file("/assets/images/service/nav/pc/solution.png"); ?>" alt="">
                </span>
              </span>
              <span class='service-localNav_ttl'><img src="<?php echo output_file("/assets/images/service/nav/solution.svg"); ?>" alt="IT Solution"></span>
            </a>
          </li>
          <li class="service-localNav_item mod-education">
            <a class="service-localNav_link js-serviceLink" href="#education">
              <picture class='service-localNav_number'>
                <source media="(min-width: 768px)" srcset="<?php echo output_file("/assets/images/service/nav/pc/04.svg"); ?>">
                <img src="<?php echo output_file("/assets/images/service/nav/04.svg"); ?>" alt="SERVICE 04">
              </picture>
              <span class='service-localNav_visual util-pc'>
                <span class='service-localNav_visual_inner'>
                  <img class='service-localNav_visual_img'  src="<?php echo output_file("/assets/images/service/nav/pc/education.png"); ?>" alt="">
                </span>
              </span>
              <span class='service-localNav_ttl'><img src="<?php echo output_file("/assets/images/service/nav/education.svg"); ?>" alt="Education"></span>
            </a>
          </li>
        </ol>
      </nav>
    </section>
    <div class='service-detailOuter'>
      <section id='security' class='service-detail mod-security'>
        <div class='service-detail_inner'>
          <div class='service-detail_textArea'>
            <div class='service-detail_textArea_inner'>
              <h2 class='service-detail_textArea_inner_ttl'>
                <img class='service-detail_textArea_inner_ttl_img js-inView' src="<?php echo output_file("/assets/images/service/service/security/title.svg"); ?>" alt="SERVICE Security">
                <span class='service-detail_textArea_inner_ttl_sub js-inView'>最適ソリューションの提案から<br>CXまでをワンストップ支援</span>
              </h2>
              <p class='service-detail_textArea_inner_paragraph js-inView'>当社は世界中のITメーカーとのパートナー契約により、サーバー、ネットワーク、データベース、クラウドなどの幅広いサイバーセキュリティソリューションを提供しています。<br class='util-pc'>お客様のサイバーセキュリティリスクを分析した上で、ニーズに合ったプロダクトをワンステップで提供いたします。</p>
            </div>
            <div class="service-detail_textArea_img js-inView_illust mod-hidden">
              <div class="service-detail_textArea_layer1">
                <img class="service-detail_textArea_img_inline" src="<?php echo output_file("/assets/images/service/service/security/item1.svg"); ?>" alt="">
              </div>
            </div>
          </div>
          <div class='service-detail_works js-inView'>
            <div class='service-detail_works_deco'></div>
            <div class='service-detail_border'></div>
            <div class='service-detail_border'></div>
            <div class='service-detail_border'></div>
            <div class='service-detail_border'></div>
            <div class='service-detail_border'></div>
            <dl class="service-detail_works_inner">
              <div class='service-detail_works_item mod-consulting js-inView'>
                <dt class='service-detail_works_item_ttl'><img class='service-detail_works_item_ttl_img' src="<?php echo output_file("/assets/images/service/service/security/consulting.svg"); ?>" alt="Consulting"></dt>
                <dd>
                  <ul class='service-detail_works_item_list'>
                    <li class='service-detail_works_item_item'>アセスメントによる課題の抽出</li>
                    <li class='service-detail_works_item_item'>セキュリティ対投資効果 <br class='util-pc'>見込みの可視化</li>
                    <li class='service-detail_works_item_item'>ベンダーにとらわれない<br class='util-sp'>最適なソリューション提案</li>
                  </ul>
                </dd>
              </div>
              <div class='service-detail_works_item mod-resale js-inView'>
                <dt class='service-detail_works_item_ttl'><img class='service-detail_works_item_ttl_img' src="<?php echo output_file("/assets/images/service/service/security/resale.svg"); ?>" alt="Resale"></dt>
                <dd>
                  <ul class='service-detail_works_item_list'>
                    <li class='service-detail_works_item_item'>ネットワークインフラセキュリティ</li>
                    <li class='service-detail_works_item_item'>エンドポイント<br class='util-pc'>セキュリティ</li>
                    <li class='service-detail_works_item_item'>認証/管理 セキュリティ</li>
                  </ul>
                </dd>
              </div>
              <div class='service-detail_works_item mod-onboarding js-inView'>
                <dt class='service-detail_works_item_ttl'><img class='service-detail_works_item_ttl_img' src="<?php echo output_file("/assets/images/service/service/security/onboarding.svg"); ?>" alt="Onboarding"></dt>
                <dd>
                  <ul class='service-detail_works_item_list'>
                    <li class='service-detail_works_item_item'>システムのグランドデザイン(設計)</li>
                    <li class='service-detail_works_item_item'>プロジェクトマネジメント</li>
                    <li class='service-detail_works_item_item'>システム構築</li>
                    <li class='service-detail_works_item_item'>システム結合テスト</li>
                    <li class='service-detail_works_item_item'>最終運用試験</li>
                  </ul>
                </dd>
              </div>
              <div class='service-detail_works_item mod-adoption js-inView'>
                <dt class='service-detail_works_item_ttl'><img class='service-detail_works_item_ttl_img' src="<?php echo output_file("/assets/images/service/service/security/adoption.svg"); ?>" alt="Adoption"></dt>
                <dd>
                  <ul class='service-detail_works_item_list'>
                    <li class='service-detail_works_item_item'>メンテナンスサービス</li>
                    <li class='service-detail_works_item_item'>テクニカルサポート</li>
                    <li class='service-detail_works_item_item'>製品導入による業務プロセス変化に対するビジネスプロセスコンサル</li>
                  </ul>
                </dd>
              </div>
              <div class='service-detail_works_item mod-training js-inView'>
                <dt class='service-detail_works_item_ttl'><img class='service-detail_works_item_ttl_img' src="<?php echo output_file("/assets/images/service/service/security/training.svg"); ?>" alt="training"></dt>
                <dd>
                  <ul class='service-detail_works_item_list'>
                    <li class='service-detail_works_item_item'>メーカー認定<br class='util-pc'>トレーニング</li>
                    <li class='service-detail_works_item_item'>セキュリティ<br class='util-pc'>アウェアネストレーニング</li>
                    <li class='service-detail_works_item_item'>ITインフラ研修</li>
                  </ul>
                </dd>
              </div>
            </dl>
          </div>
          <div class='service-detail_statement js-inView'>
            <figure class="service-detail_statement_visual mod-changeImgWrap js-changeImgWrap">
              <img class='service-detail_statement_visual_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/security/cyber1.png"); ?>" alt="">
              <img class='service-detail_statement_visual_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/security/cyber2.png"); ?>" alt="">
            </figure>
            <div class='service-detail_statement_textArea'>
              <h3 class='service-detail_ttl'>各カテゴリーの幅広いセキュリティ<br class='util-sp'>プロダクトをリサーチし提供</h3>
              <p class='service-detail_paragraph'>当社では、認証技術・ネットワークサイバーセキュリティ・マルウェア対策・脆弱性対策など各カテゴリーの幅広いサイバーセキュリティプロダクトに対応します。<br class='util-pc'>特定のプロダクトのみを扱うのではなく、常に進化を続ける世界中のサイバーセキュリティ商材をリサーチし、社内の検証センターで自ら検証した上で、お客様へ提供することで、幅広いサイバーセキュリティプロダクトを提供します。</p>
            </div>
          </div>
        </div>
        <?php 
        if(get_field('service-handling',3460)[0]['service-handling-logo']):
        ?>
        <div class='service-logoArea mod-security js-inView'>
          <h4 class='service-logoArea_ttl'>取り扱いメーカー</h4>
          <div class='service-logoArea_accordion js-serviceLogoAreaAccordion <?php if(get_field('service-handling',3460)[9]['service-handling-logo'])echo 'mod-accordion'; ?>'>
            <ul class='service-logoArea_list js-serviceLogoAreaList'>
            <?php while(have_rows('service-handling',3460)): the_row();?>
            <?php if(get_sub_field('service-handling-url')): ?>
              <li class='service-logoArea_item'>
                <a class="service-logoArea_link" href="<?php the_sub_field('service-handling-url') ?>" target='_blank'>
                  <img class='service-logoArea_item_img' src="<?php the_sub_field('service-handling-logo') ?>" alt="<?php the_sub_field('service-handling-name') ?>">
                </a>
              </li>
            <?php else: ?>
              <li class='service-logoArea_item'><img class='service-logoArea_item_img' src="<?php the_sub_field('service-handling-logo') ?>" alt="<?php the_sub_field('service-handling-name') ?>"></li>
            <?php endif; ?>
            <?php endwhile; ?>
            </ul>
            <?php if(get_field('service-handling',3460)[9]['service-handling-logo']): ?>
            <button class='service-logoArea_accordionButton js-serviceLogoAreaAccordionButton'></button>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      </section>
      <section id='resale' class='service-detail mod-resale'>
      <div class='service-detail_inner'>
        <div class='service-detail_textArea'>
          <div class='service-detail_textArea_inner'>
            <h2 class='service-detail_textArea_inner_ttl js-inView'>
              <img class='service-detail_textArea_inner_ttl_img js-inView' src="<?php echo output_file("/assets/images/service/service/resale/title.svg"); ?>" alt="SERVICE Resale">
              <span class='service-detail_textArea_inner_ttl_sub js-inView'>お客様のニーズに合った<br>プロダクトを世界中から探します</span>
            </h2>
            <p class='service-detail_textArea_inner_paragraph js-inView'>当社では、現状あるメーカープロダクトから提案を行うだけではなく、常に進化を続ける世界中のサイバーセキュリティ商材の中からお客様のニーズに合ったプロダクトをご提案します。<br>また、販売して終わりではなく、オンボーディング・アダプション・トレーニングまで一貫したフォローをいたします。</p>
          </div>
          <div class="service-detail_textArea_img js-inView_illust">
            <div class="service-detail_textArea_layer2 js-stickOut mod-layer2">
              <img class="service-detail_textArea_img_inline mod-show" src="<?php echo output_file("/assets/images/service/service/resale/item1.svg"); ?>" alt="">
            </div>
            <div class="service-detail_textArea_layer1">
              <img class="service-detail_textArea_img_inline mod-show" src="<?php echo output_file("/assets/images/service/service/resale/item2.svg"); ?>" alt="">
            </div>
          </div>
        </div>
        <div class='service-detail_description js-inView'>
          <figure class="service-detail_description_visual mod-changeImgWrap js-changeImgWrap">
            <img class='mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/resale/cyber1.png"); ?>" alt="">
            <img class='mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/resale/cyber2.png"); ?>" alt="">
          </figure>
          <div class='service-detail_description_textArea'>
            <p class='service-detail_description_paragraph'>社内には技術研究・検証専門チーム「Unit0045」があり、常に最先端のサイバーセキュリティプロダクトの情報をキャッチアップすることができ、本当に信頼に足るプロダクト・サービスかどうかを自ら検証した上で、お客様へ提供します。<br>また「Unit0045」は、サイバーセキュリティにおける企業の駆け込み寺としての役割も担い、セキュリティ診断やインシデント対応などによって、企業の安心・安全を守ります。</p>
          </div>
        </div>
      </div>
      <?php 
      if(get_field('service-handling',3459)[0]['service-handling-logo']):
      ?>
        <div class='service-logoArea mod-resale js-inView'>
          <h4 class='service-logoArea_ttl'>取り扱いメーカー</h4>
          <div class='service-logoArea_accordion js-serviceLogoAreaAccordion <?php if(get_field('service-handling',3459)[9]['service-handling-logo'])echo 'mod-accordion'; ?>'>
            <ul class='service-logoArea_list js-serviceLogoAreaList'>
            <?php while(have_rows('service-handling',3459)): the_row(); ?>
            <?php if(get_sub_field('service-handling-url')): ?>
              <li class='service-logoArea_item'>
                <a class="service-logoArea_link" href="<?php the_sub_field('service-handling-url') ?>" target='_blank'>
                  <img class='service-logoArea_item_img' src="<?php the_sub_field('service-handling-logo') ?>" alt="<?php the_sub_field('service-handling-name') ?>">
                </a>
              </li>
              <?php else: ?>
              <li class='service-logoArea_item'><img class='service-logoArea_item_img' src="<?php the_sub_field('service-handling-logo') ?>" alt="<?php the_sub_field('service-handling-name') ?>"></li>
            <?php endif; ?>
            <?php endwhile; ?>
            </ul>
            <?php if(get_field('service-handling',3459)[9]['service-handling-logo']): ?>
            <button class='service-logoArea_accordionButton js-serviceLogoAreaAccordionButton'></button>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      </section>
      <section id='solution' class='service-detail mod-solution'>
      <div class='service-detail_inner'>
      <div class='service-detail_textArea'>
          <div class='service-detail_textArea_inner'>
            <h2 class='service-detail_textArea_inner_ttl'>
              <img class='service-detail_textArea_inner_ttl_img js-inView' src="<?php echo output_file("/assets/images/service/service/itsolution/title.svg"); ?>" alt="SERVICE IT Solution">
              <span class='service-detail_textArea_inner_ttl_sub js-inView'>最先端の<br class='util-sp'>ITインフラシステムを追求</span>
            </h2>
            <p class='service-detail_textArea_inner_paragraph js-inView'>「業務効率化、生産性向上、コスト削減」といったお客様が求めるご要望に対して、私たちは「製品力」「技術力」「トレーニング力」この３つの力で、 要件確認による問題点の可視化から、システムの実装(設計･構築･製品選定/導入)、安定運用に至るまで、ITインフラシステムのワンストップソリューションを提供します。</p>
          </div>
          <div class="service-detail_textArea_img js-inView_illust">
            <div class='service-detail_textArea_img_inner'>
              <div class="service-detail_textArea_layer2 js-stickOut mod-layer2">
                <img class="service-detail_textArea_img_inline mod-show" src="<?php echo output_file("/assets/images/service/service/itsolution/item1.svg"); ?>" alt="">
              </div>
              <div class="service-detail_textArea_layer1">
                <img class="service-detail_textArea_img_inline mod-show" src="<?php echo output_file("/assets/images/service/service/itsolution/item2.svg"); ?>" alt="">
              </div>
            </div>
          </div>
        </div>
        <ul class='service-detail_feature js-inView'>
          <li class='service-detail_feature_item js-serviceDetailFeatureItem js-inView mod-1'>
            <h3 class='service-detail_feature_ttl'>マルチベンダーに<br class='util-sp'>対応できる<span class='mod-strong'>製品力</span></h3>
            <div class='service-detail_feature_detail'>
              <figure class="service-detail_feature_detail_img js-changeImgWrap">
                <img class='service-detail_feature_detail_img_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/itsolution/product1.jpg"); ?>" alt="">
                <img class='service-detail_feature_detail_img_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/itsolution/product2.jpg"); ?>" alt="">
              </figure>
              <p class='service-detail_feature_detail_paragraph'>独自のコネクションによる幅広い製品の取扱いが可能なため、さまざまなお客様の要望に柔軟に対応させていただきます。実際の利用時の感覚や他製品と組合せた場合など具体的な導入相談が可能です。<br>また、現状扱っているメーカープロダクトから提案を行うだけではなく、常に進化を続ける世界中のITインフラプロダクトを検証し、お客様のニーズに合ったプロダクト・サービスを提案します。</p>
            </div>
          </li>
          <li class='service-detail_feature_item js-serviceDetailFeatureItem js-inView mod-2'>
            <h3 class='service-detail_feature_ttl'>最先端技術にも<br class='util-sp'>対応できる<span class='mod-strong'>技術力</span></h3>
            <div class='service-detail_feature_detail'>
              <figure class="service-detail_feature_detail_img js-changeImgWrap">
                <img class='service-detail_feature_detail_img_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/itsolution/skill1.jpg"); ?>" alt="">
                <img class='service-detail_feature_detail_img_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/itsolution/skill2.jpg"); ?>" alt="">
              </figure>
              <p class='service-detail_feature_detail_paragraph '><span class='mod-adjust'>各プロダクトに精通したエンジニアがそれぞれ在籍しており、プロジェクトに応じた横断的なアサインが可能です。また、プロジェクトをとりまとめるプロジェクトマネジャーやプロジェクトリーダクラスのエンジニアも多数在籍しております。</span><br>弊社にてお客様への提案活動から、設計構築、運用フェーズまでワンストップでの技術サービス提供いたします。</p>
            </div>
          </li>
          <li class='service-detail_feature_item js-serviceDetailFeatureItem js-inView mod-3'>
            <h3 class='service-detail_feature_ttl'>安定運用まで<br class='util-sp'>支援する<span class='mod-strong'>トレーニング力</span></h3>
            <div class='service-detail_feature_detail'>
              <figure class="service-detail_feature_detail_img js-changeImgWrap">
                <img class='service-detail_feature_detail_img_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/itsolution/training1.jpg"); ?>" alt="">
                <img class='service-detail_feature_detail_img_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/itsolution/training2.jpg"); ?>" alt="">
              </figure>
              <p class='service-detail_feature_detail_paragraph'>製品特有の設計や構築のナレッジは、マニュアル上だけではなく、実際の案件に携わることでしか得られない知識が多いと考えています。受託案件で得た要件定義から運用までのナレッジを、トレーニングサービスとしてご提供しています。案件に携わっているエンジニアが、トレーニング講師も兼任しているため、実際の案件で得た生きた知識をトレーニングにてご提供します。</p>
            </div>
          </li>
        </ul>
      </div>
      <?php 
      if(get_field('service-handling',3458)[0]['service-handling-logo']):
      ?>
        <div class='service-logoArea mod-solution js-inView'>
          <h4 class='service-logoArea_ttl'>取り扱い可能メーカー</h4>
          <div class='service-logoArea_accordion js-serviceLogoAreaAccordion <?php if(get_field('service-handling',3458)[9]['service-handling-logo'])echo 'mod-accordion'; ?>'>
            <ul class='service-logoArea_list js-serviceLogoAreaList'>
            <?php while(have_rows('service-handling',3458)): the_row(); ?>
            <?php if(get_sub_field('service-handling-url')): ?>
              <li class='service-logoArea_item'>
                <a class="service-logoArea_link" href="<?php the_sub_field('service-handling-url') ?>" target='_blank'>
                  <img class='service-logoArea_item_img' src="<?php the_sub_field('service-handling-logo') ?>" alt="<?php the_sub_field('service-handling-name') ?>">
                </a>
              </li>
            <?php else: ?>
              <li class='service-logoArea_item'><img class='service-logoArea_item_img' src="<?php the_sub_field('service-handling-logo') ?>" alt="<?php the_sub_field('service-handling-name') ?>"></li>
            <?php endif; ?>
            <?php endwhile; ?>
            </ul>
            <?php if(get_field('service-handling',3458)[9]['service-handling-logo']): ?>
            <button class='service-logoArea_accordionButton js-serviceLogoAreaAccordionButton'></button>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      </section>
      <section id='education' class='service-detail mod-education'>
        <div class='service-detail_inner'>
        <div class='service-detail_textArea'>
          <div class='service-detail_textArea_inner'>
            <h2 class='service-detail_textArea_inner_ttl'>
              <img class='service-detail_textArea_inner_ttl_img js-inView' src="<?php echo output_file("/assets/images/service/service/education/title.svg"); ?>" alt="SERVICE Education">
              <span class='service-detail_textArea_inner_ttl_sub js-inView'>リスキリングによる<br class='util-sp'>IT人材育成</span>
            </h2>
            <p class='service-detail_textArea_inner_paragraph js-inView'>技術革新やビジネスの変革による働き方の変化に対応するために、どの企業でもIT人材を育成することが求められていることから、IT企業としての実務経験値とお客様の要望を基に開発した実務に活きる教育サービスを提供いたします。</p>
          </div>
          <div class="service-detail_textArea_img js-inView_illust">
            <div class="service-detail_textArea_layer2 js-stickOut mod-layer2">
              <img class="service-detail_textArea_img_inline mod-show" src="<?php echo output_file("/assets/images/service/service/education/item1.svg"); ?>" alt="">
            </div>
            <div class="service-detail_textArea_layer1">
              <img class="service-detail_textArea_img_inline mod-show" src="<?php echo output_file("/assets/images/service/service/education/item2.svg"); ?>" alt="">
            </div>
          </div>
        </div>
        <ul class='service-detail_feature js-inView'>
          <li class='service-detail_feature_item js-serviceDetailFeatureItem js-inView mod-1'>
            <h3 class='service-detail_feature_ttl'><span class='mod-strong'>エンジニア<br class='util-sp'>育成</span></h3>
            <div class='service-detail_feature_detail mod-1row'>
              <figure class="service-detail_feature_detail_img js-changeImgWrap">
                <img class='service-detail_feature_detail_img_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/education/education1.jpg"); ?>" alt="">
                <img class='service-detail_feature_detail_img_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/education/education2.jpg"); ?>" alt="">
              </figure>
              <p class='service-detail_feature_detail_paragraph'>経験の浅いエンジニアが職場で活躍するために、ITインフラ業務システムを幅広く学ぶ研修を提供します。研修ではIT技術はもちろんのこと、エンジニアとして必要なドキュメント読解力や業務法を合わせて学習できます。講師は現役エンジニアやエンジニア経験者が担当するため、実際の業務さながらの経験が研修で体験できます。</p>
            </div>
          </li>
          <li class='service-detail_feature_item js-serviceDetailFeatureItem js-inView mod-2'>
            <h3 class='service-detail_feature_ttl'><span class='mod-strong'>サイバー<br class='util-sp'>セキュリティ<br>トレーニング</span></h3>
            <div class='service-detail_feature_detail'>
              <figure class="service-detail_feature_detail_img js-changeImgWrap">
                <img class='service-detail_feature_detail_img_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/education/security-training1.jpg"); ?>" alt="">
                <img class='service-detail_feature_detail_img_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/education/security-training2.jpg"); ?>" alt="">
              </figure>
              <p class='service-detail_feature_detail_paragraph'>企業や組織を狙ったサイバー攻撃が高度化・巧妙化している今日のサイバーセキュリティ対策のための人材育成トレーニングを提供します。また、セキュリティアウェアネスと呼ばれる組織のセキュリティが確保されるよう各個人が一定の習慣を理解して適切な行動をとることを目標とするトレーニングなども提供します。</p>
            </div>
          </li>
          <li class='service-detail_feature_item js-serviceDetailFeatureItem js-inView mod-3'>
            <h3 class='service-detail_feature_ttl'><span class='mod-strong'>プロダクト<br class='util-sp'>トレーニング</span></h3>
            <div class='service-detail_feature_detail mod-1row'>
              <figure class="service-detail_feature_detail_img js-changeImgWrap">
                <img class='service-detail_feature_detail_img_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/education/product-training1.jpg"); ?>" alt="">
                <img class='service-detail_feature_detail_img_img mod-changeImg js-img' src="<?php echo output_file("/assets/images/service/service/education/product-training2.jpg"); ?>" alt="">
              </figure>
              <p class='service-detail_feature_detail_paragraph'>製品特有の設計や構築のナレッジは、マニュアル上だけではなく、実際の案件に携わることでしか得られない知識が多いと考えています。受託案件で得た要件定義から運用までのナレッジを、トレーニングサービスとしてご提供しています。案件に携わっているエンジニアが、トレーニング講師も兼任しているため、実際の案件で得た生きた知識をトレーニングにてご提供します。</p>
            </div>
          </li>
        </ul>
        </div>
        <?php 
        if(get_field('service-handling',3456)[0]['service-handling-logo']):
        ?>
        <div class='service-logoArea mod-education js-inView'>
          <h4 class='service-logoArea_ttl'>取り扱いトレーニング</h4>
          <div class='service-logoArea_accordion js-serviceLogoAreaAccordion <?php if(get_field('service-handling',3456)[9]['service-handling-logo'])echo 'mod-accordion'; ?>'>
            <ul class='service-logoArea_list js-serviceLogoAreaList'>
            <?php while(have_rows('service-handling',3456)): the_row(); ?>
            <?php if(get_sub_field('service-handling-url')): ?>
              <li class='service-logoArea_item'>
                <a class="service-logoArea_link" href="<?php the_sub_field('service-handling-url') ?>" target='_blank'>
                  <img class='service-logoArea_item_img' src="<?php the_sub_field('service-handling-logo') ?>" alt="<?php the_sub_field('service-handling-name') ?>">
                </a>
              </li>
            <?php else: ?>
              <li class='service-logoArea_item'><img class='service-logoArea_item_img' src="<?php the_sub_field('service-handling-logo') ?>" alt="<?php the_sub_field('service-handling-name') ?>"></li>
            <?php endif; ?>
            <?php endwhile; ?>
            </ul>
            <?php if(get_field('service-handling',3456)[9]['service-handling-logo']): ?>
            <button class='service-logoArea_accordionButton js-serviceLogoAreaAccordionButton'></button>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      </section>
    </div>
  </div>
  <div class='service-bottom'></div>
</main>

<?php get_footer();
