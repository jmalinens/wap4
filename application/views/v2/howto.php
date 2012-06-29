<ul class="features howto">
<?php foreach ($content as $pamaaciiba):?>

    <li><p><?=$pamaaciiba?></p></li>

<?php endforeach; ?>
<li class="no_bullet"><strong><?=anchor("howto/codecs", lang("howto.codecs"))?></strong></li>
<li class="no_bullet"><strong><?=anchor("howto/formats", lang("howto.formats"))?></strong></li>
</ul>