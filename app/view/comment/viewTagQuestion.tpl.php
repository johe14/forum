<h1><?=$title?></h1>

<article>

<?php $divider = '<span class="comment-time">&#160;&#160;&#8226;&#160;&#160;</span>' ?>

<article class="article1">

<?php if (is_array($comments)) : ?>

    <?php foreach ($comments as $comment) : ?>
        <a class="question-header" title="View a question" href='<?=$this->url->create('comment/id/')?>/<?=$comment->id?>'><?=$comment->question?> </a>
        
        <p><?=$comment->content?></p>
        
        <?php if (!empty($comment->tags)) : ?>
            <?php $tag = explode(", ", $comment->tags);?>
            <?php foreach ($tag as $tagItem) : ?>    

                <a href='<?=$this->url->create('comment/findTagQuestion')?>/<?=$tagItem?>' class='tag'><?=$tagItem?></a>
        
            <?php endforeach; ?>
        <?php endif; ?>   
        
        
        <p>
        <img style="height:20px;width:20px;margin-right:7px;" src="http://www.gravatar.com/avatar/<?=md5(strtolower(trim($comment->email)))?>?d=retro">

        <a class="comment" href='
        <?=$this->url->create('users/acronym/')?>/<?=$comment->name?>' title="View user profile"><?=$comment->name?></a>
        
        <?=$divider ?>
    
        <span class="comment-time">
        Posted: <?=date('Y-m-d H:i:s', strtotime($comment->created))?>
        </span>
     
        <?php if (!empty($comment->updated)) : ?>
            
            <?=$divider ?>
            <span class="comment-time">
            Uppdated:
            <?=date('Y-m-d H:i:s', strtotime($comment->updated))?>
            </span>
            
        <?php endif; ?> 
        
        </p>
        
        <hr class="grey">

    <?php endforeach; ?>

<?php endif; ?>

</article>




<?php if(isset($byline)) : ?>
<footer class="byline">
<?=$byline?>
</footer>
<?php endif; ?>

</article>