<h2><?=$title?></h2>

<div class="article1">

<?php foreach ($tags as $tagItem => $count) : ?> 

    <?php if (!empty($tagItem)) : ?>
        <?php $linkName = sprintf('%s (%u) ', $tagItem, $count)   ?>
        <a href='<?=$this->url->create('comment/findTagQuestion')?>/<?=$tagItem?>' class='tag'><?=$linkName?></a>
    <?php endif; ?>
    
<?php endforeach; ?> 

</div>