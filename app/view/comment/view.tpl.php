<h1><?=$comments->question?></h1>

<div class="article1">

<?php $divider = '<span class="comment-time">&#160;&#160;&#8226;&#160;&#160;</span>' ?>

<?=substr($this->textFilter->doFilter($comments->content, 'markdown'), 0, 200)?>

<?php if (!empty($comments->tags)) : ?>
    <?php $tag = explode(", ", $comments->tags);?>
    <?php foreach ($tag as $tagItem) : ?>    

    <a href='<?=$this->url->create('comment/findTagQuestion')?>/<?=$tagItem?>' class='tag'><?=$tagItem?></a>

    <?php endforeach; ?>
<?php endif; ?>     

<div>
    <img style="height:30px;width:30px;margin-right:7px;" alt="gravatar" src="http://www.gravatar.com/avatar/<?=md5(strtolower(trim($comments->email)))?>?d=retro">    

    <a class="comment" href='
    <?=$this->url->create('users/acronym/')?>/<?=$comments->name?>' title="View user profile"><?=$comments->name?></a>
    
    <?=$divider ?>
    
    <span class="comment-time">
    Posted: <?=date('Y-m-d H:i:s', strtotime($comments->created))?>
    </span>
        
    <?php if (!empty($comments->updated)) : ?>
        
        <?=$divider ?>
        <span class="comment-time">
        Updated:                                 
        <?=date('Y-m-d H:i:s', strtotime($comments->updated))?>
        </span>
        
    <?php endif; ?> 
    
    <?php if(isset($_SESSION["user"])) : ?> 
    
        <?=$divider ?>
        <a class="comment" href="<?=$this->url->create('comment/addReplyQuestion/' . $comments->id)?> "> Comment</a>
    
        <?php if($_SESSION["user"]->acronym == $comments->name OR $_SESSION["user"]->acronym == "admin") : ?>
            <?=$divider ?>
            <a class="comment" href="<?=$this->url->create('comment/update/' . $comments->id)?> "> Edit</a>
            <?=$divider ?>
            <a class="comment" href="<?=$this->url->create('comment/delete/' . $comments->id)?> "> Delete</a> 
        <?php endif; ?>
        
        <br><br>
        
<?php endif; ?>
    
</div>

</div>


