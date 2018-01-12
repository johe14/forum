<h1>All questions</h1>

<?php $divider = '<span class="comment-time">&#160;&#160;&#8226;&#160;&#160;</span>' ?>

<div class="article1">

    <?php if (is_array($comments)) : ?>

        <?php foreach ($comments as $id => $comment) : ?>
            <a class="question-header" title="View a question" href='<?=$this->url->create('comment/id/')?>/<?=$comment->id?>'><?=$comment->question?> </a>
        
            <?=substr($this->textFilter->doFilter($comment->content, 'markdown'), 0, 200)?>
        
            <?php if (!empty($comment->tags)) : ?>
                <?php $tag = explode(", ", $comment->tags);?>
                
                <?php foreach ($tag as $tagItem) : ?>    
                    <a href='<?=$this->url->create('comment/findTagQuestion')?>/<?=$tagItem?>' class='tag'><?=$tagItem?></a>
                <?php endforeach; ?>
                
            <?php endif; ?> 

            <br>
        
            <img style="height:30px;width:30px;margin-right:7px;" alt="gravatar" src="http://www.gravatar.com/avatar/<?=md5(strtolower(trim($comment->email)))?>?d=retro">

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
    
            <?php if(isset($_SESSION["user"])) : ?>
                <?php if($_SESSION["user"]->acronym == $comment->name OR $_SESSION["user"]->acronym == "admin") : ?>
                    <?=$divider ?>
                    <a class="comment" href="<?=$this->url->create('comment/update/' . $comment->id)?> "> Edit</a>
                    <?=$divider ?>
                    <a class="comment" href="<?=$this->url->create('comment/delete/' . $comment->id)?> "> Delete</a>    
                <?php endif; ?>
            <?php endif; ?>  

            <hr class="grey">

        <?php endforeach; ?>

    <?php endif; ?>

</div>