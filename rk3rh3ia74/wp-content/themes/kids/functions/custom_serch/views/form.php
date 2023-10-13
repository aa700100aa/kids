<?php ob_start(); ?>
<div class="custom_serch">
    <div class="row row01_01"><div class="colbox">
        <div class="col col01">
            <label data-before-text="年齢" data-after-text="歳"><span class="custom-select"><select name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs01]<?php else: ?>cs01<?php endif; ?>">
                <option value=""></option>
                <?php for($i=18;$i<=100;$i++): ?><option value="<?php echo $i; ?>" <?php $this->selected('cs01',$i); ?>><?php echo $i; ?></option><?php endfor; ?>
            </select></span></label>
        </div><?php if(!empty($this->CustomField['職業']) && is_array($this->CustomField['職業']['choices']) && count($this->CustomField['職業']['choices'])): ?><div class="col col02">
            <label data-before-text="職業"><span class="custom-select"><select name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs03]<?php else: ?>cs03<?php endif; ?>">
                <option value=""></option>
                <?php foreach($this->CustomField['職業']['choices'] as $choice): ?><option value="<?php echo esc_attr($choice); ?>" <?php $this->selected('cs03',$choice); ?>><?php echo $choice; ?></option><?php endforeach; ?>
            </select></span></label>
        </div><?php endif;if(!empty($this->CustomField['居住地']) && is_array($this->CustomField['居住地']['choices']) && count($this->CustomField['居住地']['choices'])): ?><div class="col col03">
            <label data-before-text="居住地"><span class="custom-select"><select name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs04]<?php else: ?>cs04<?php endif; ?>">
                <option value=""></option>
                <?php foreach($this->CustomField['居住地']['choices'] as $choice): ?><option value="<?php echo esc_attr($choice); ?>" <?php $this->selected('cs04',$choice); ?>><?php echo $choice; ?></option><?php endforeach; ?>
            </select></span></label>
        </div><?php endif; ?>
    </div></div>
    <div class="row row01_02">
        <div class="col col01 flexbox" data-before-text="借入金利"><div class="colbox">
            <label class="col col01">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs06]<?php else: ?>cs06<?php endif; ?>" value="-3" <?php $this->checked('cs06',-3); ?>><span class="label"><span class="red">最低</span>金利が３％以下</span>
            </label><?php if(is_admin()): ?><label class="col col01">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs06]<?php else: ?>cs06<?php endif; ?>" value="-4" <?php $this->checked('cs06',-4); ?>><span class="label"><span class="red">最低</span>金利が４％以下</span>
            </label><label class="col col01">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs06]<?php else: ?>cs06<?php endif; ?>" value="-5" <?php $this->checked('cs06',-5); ?>><span class="label"><span class="red">最低</span>金利が５％以下</span>
            </label><label class="col col01">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs06]<?php else: ?>cs06<?php endif; ?>" value="-6" <?php $this->checked('cs06',-6); ?>><span class="label"><span class="red">最低</span>金利が６％以下</span>
            </label><?php endif; ?><label class="col col01">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs06]<?php else: ?>cs06<?php endif; ?>" value="-7" <?php $this->checked('cs06',-7); ?>><span class="label"><span class="red">最低</span>金利が７％以下</span>
            </label><?php if(is_admin()): ?><label class="col col01">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs06]<?php else: ?>cs06<?php endif; ?>" value="8" <?php $this->checked('cs06',8); ?>><span class="label"><span class="red">最大</span>金利が８％以下</span>
            </label><label class="col col01">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs06]<?php else: ?>cs06<?php endif; ?>" value="9" <?php $this->checked('cs06',9); ?>><span class="label"><span class="red">最大</span>金利が９％以下</span>
            </label><?php endif; ?><label class="col col01">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs06]<?php else: ?>cs06<?php endif; ?>" value="10" <?php $this->checked('cs06',10); ?>><span class="label"><span class="red">最大</span>金利が10％以下</span>
            </label><label class="col col01">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs06]<?php else: ?>cs06<?php endif; ?>" value="13" <?php $this->checked('cs06',13); ?>><span class="label"><span class="red">最大</span>金利が13％以下</span>
            </label><?php if(is_admin()): ?><label class="col col01">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs06]<?php else: ?>cs06<?php endif; ?>" value="9999" <?php $this->checked('cs06',9999); ?>><span class="label">指定なし</span>
            </label><?php endif; ?>
        </div></div>
    </div>
    <div class="row row01_03">
        <div class="col col01 flexbox" data-before-text="希望借入額"><div class="colbox">
            <label class="col col01">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs08]<?php else: ?>cs08<?php endif; ?>" value="10" <?php $this->checked('cs08',10); ?>><span class="label">10万円以内</span>
            </label><label class="col col02">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs08]<?php else: ?>cs08<?php endif; ?>" value="30" <?php $this->checked('cs08',30); ?>><span class="label">30万円以内</span>
            </label><label class="col col03">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs08]<?php else: ?>cs08<?php endif; ?>" value="50" <?php $this->checked('cs08',50); ?>><span class="label">50万円以内</span>
            </label><label class="col col04">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs08]<?php else: ?>cs08<?php endif; ?>" value="100" <?php $this->checked('cs08',100); ?>><span class="label">100万円以内</span>
            </label><label class="col col05">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs08]<?php else: ?>cs08<?php endif; ?>" value="200" <?php $this->checked('cs08',200); ?>><span class="label">200万円以内</span>
            </label><label class="col col06">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs08]<?php else: ?>cs08<?php endif; ?>" value="300" <?php $this->checked('cs08',300); ?>><span class="label">300万円以内</span>
            </label><label class="col col07">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs08]<?php else: ?>cs08<?php endif; ?>" value="500" <?php $this->checked('cs08',500); ?>><span class="label">500万円以内</span>
            </label><label class="col col08">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs08]<?php else: ?>cs08<?php endif; ?>" value="1000" <?php $this->checked('cs08',1000); ?>><span class="label">1,000万円以内</span>
            </label><?php if(is_admin()): ?><label class="col col08">
                <input type="radio" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs08]<?php else: ?>cs08<?php endif; ?>" value="9999" <?php $this->checked('cs08',9999); ?>><span class="label">指定なし</span>
            </label><?php endif; ?>
        </div></div>
    </div>
    <div class="row row01_04"><div class="colbox">
        <div class="col col01">
            <input type="hidden" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs09]<?php else: ?>cs09<?php endif; ?>" value="0">
            <label data-before-text="即日審査可"><input type="checkbox" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs09]<?php else: ?>cs09<?php endif; ?>" value="1" <?php $this->checked('cs09',1); ?>><span class="ml00 label"></span></label>
        </div><div class="col col02">
            <input type="hidden" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs10]<?php else: ?>cs10<?php endif; ?>" value="0">
            <label data-before-text="即日借入可"><input type="checkbox" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs10]<?php else: ?>cs10<?php endif; ?>" value="1" <?php $this->checked('cs10',1); ?>><span class="ml00 label"></span></label>
        </div><div class="col col03">
            <input type="hidden" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs11]<?php else: ?>cs11<?php endif; ?>" value="0">
            <label data-before-text="振込融資可"><input type="checkbox" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs11]<?php else: ?>cs11<?php endif; ?>" value="1" <?php $this->checked('cs11',1); ?>><span class="ml00 label"></span></label>
        </div><div class="col col04">
            <input type="hidden" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs12]<?php else: ?>cs12<?php endif; ?>" value="0">
            <label data-before-text="おまとめ・借換えにも対応"><input type="checkbox" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs12]<?php else: ?>cs12<?php endif; ?>" value="1" <?php $this->checked('cs12',1); ?>><span class="ml00 label"></span></label>
        </div>
    </div></div>
    <div class="row row01_05">
        <div class="col col01">
            <input type="hidden" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs13]<?php else: ?>cs13<?php endif; ?>" value="0">
            <label data-before-text="ネットバンキング利用可"><input type="checkbox" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs13]<?php else: ?>cs13<?php endif; ?>" value="1" <?php $this->checked('cs13',1); ?>><span class="ml00 label"></span></label>
        </div>
    </div>
    <?php /*<?php $terms = get_terms('atm'); ?>
    <?php if(is_array($terms) && count($terms)): ?><div class="row row01_06"><div class="col col01 flexbox" data-before-text="提携ATM"><div class="colbox">
        <?php foreach($terms as $k => $term): ?><label class="col col<?php echo sprintf('%02d',$term->term_id); ?>"><input type="checkbox" name="提携ATM[]" value="<?php echo esc_attr($term->term_id); ?>"><span class="label"><?php echo $term->name; ?></span></label><?php endforeach; ?>
    </div></div></div><?php endif; ?> */ ?>
    <?php if(!empty($this->CustomField['提携ATM']) && is_array($this->CustomField['提携ATM']['choices']) && count($this->CustomField['提携ATM']['choices'])): ?><div class="row row01_06"><div class="col col01 flexbox" data-before-text="提携ATM"><div class="colbox">
        <input type="hidden" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs14]<?php else: ?>cs14<?php endif; ?>" value="">
        <?php foreach($this->CustomField['提携ATM']['choices'] as $k => $choice): ?><label class="col col<?php echo sprintf('%02d',$k); ?>"><input type="checkbox" name="<?php if(!empty($this->post_key)):echo $this->post_key; ?>[cs14]<?php else: ?>cs14<?php endif; ?>[]" value="<?php echo esc_attr($choice); ?>" <?php $this->checked('cs14',$choice); ?>><span class="label"><?php echo $choice; ?></span></label><?php endforeach; ?>
    </div></div></div><?php endif; ?>
    <?php if(!is_admin()): ?>
        <div class="row row01_07 submit-area ac mb30">
            <button type="submit"><span>カードローン検索</span></button>
        </div>
        <input type="hidden" name="custom_serch" value="">
    <?php endif; ?>
</div>
<?php
$this->form_buffer = ob_get_contents();
ob_end_clean();