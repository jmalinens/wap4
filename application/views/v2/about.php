<style>.content_right a{display: inline}</style>
<div class="content_right" style="width:98%;">
<ul class="list">
<?php foreach ($content as $par_projektu):?>

<li><?=$par_projektu?></li>

<?php endforeach; ?>
</ul>
    <p><?=anchor("auth/create_user",lang("login_create"))?></a> <em><?=lang("site.why")?></em></p>
</div>