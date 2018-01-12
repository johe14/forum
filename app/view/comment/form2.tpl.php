<article class="form">
<h2><?=$title?></h2>

<?=$content?>
<p><small>* required information</small></p>
<?php if (isset($links)) : ?>
<ul>
<?php foreach ($links as $link) : ?>
<li><a href="<?=$link['href']?>"><?=$link['text']?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</article>
