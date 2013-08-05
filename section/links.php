<ul>
<?php foreach ($s['d'][1] as $link): ?>
<?php if (is_array($link)): ?>
<li><a href="<?= url($s['d'][0] . '/' . $link[0]) ?>"><?= $link[1] ?></a></li>
<?php else: ?>
<li><a href="<?= url($s['d'][0] . '/' . nicen($link)) ?>"><?= $link ?></a></li>
<?php endif ?>
<?php endforeach ?>
</ul>
