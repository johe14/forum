<h1><?=$title?></h1>

<div class="article1">

<?php foreach ($tags as $tag) : ?>
    <?php if (!empty($tag)) : ?>
        <a href='<?=$this->url->create('comment/findTagQuestion/')?>/<?=$tag?>' class='tag'><?=$tag?></a>
    <?php endif; ?>     
        
<?php endforeach; ?>

</div>