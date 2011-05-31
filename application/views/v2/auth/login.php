<?=form_open("auth/login", $attributes)?><div><?
        ?><div class="float_container"><?
                ?><div class="top_bottom"><?=lang("login_username")?></div><?
                ?><div class="top_bottom"><?=
                        form_input($username)
                ?></div><?
        ?></div><?
        ?><div class="float_container"><?
                ?><div class="top_bottom"><?=lang("login_password")?></div><?
                ?><div class="top_bottom"><?=
                        form_input($password)
                ?></div><?
        ?></div><?
        ?><div class="float_container lower"><?=
            form_input($login_submit)
        ?></div><?
        ?></div><?php echo form_close();
?><div class="clear"></div><?=
lang("site.or")?> <?=anchor("auth/create_user",lang("login_create"))?></a> <?=lang("site.why")?>