<style>.content_right a{display: inline}</style>
<div class="content_right" style="width:98%;">
    <a href="http://infowap.info/clicks/5">List of TOP mobile websites</a><br/>
<ul class="list">
<?php foreach ($content as $par_projektu):?>

<li><?=$par_projektu?></li>

<?php endforeach; ?>
</ul>
    <p><?=anchor("auth/create_user",lang("login_create"))?> <em><?=lang("site.why")?></em></p>
</div>