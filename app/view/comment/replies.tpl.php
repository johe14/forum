<?php if (!empty($replies)) : ?>


<?php $divider = '<span class="comment-time">&#160;&#160;&#8226;&#160;&#160;</span>' ?>

<?php foreach ($replies as $reply) : ?>

    <?php if (empty($reply->deleted)) : ?>
    
        <div class="replies">

        <?=substr($this->textFilter->doFilter($reply->content, 'markdown'), 0, 200)?> 

        <a class="comment" href='
        <?=$this->url->create('users/acronym/')?>/<?=$reply->name?>' title="View user profile"><?=$reply->name?></a>
                    
        <?=$divider ?>
                    
        <span class="comment-time">
        Posted: 
        <?=date('Y-m-d H:i:s', strtotime($reply->created))?>
        </span>        

        <?php if(isset($_SESSION["user"])) : ?>
        <?php if($_SESSION["user"]->acronym == $reply->name OR $_SESSION["user"]->acronym == "admin") : ?>
            <?=$divider ?>
            <a class="comment" href="<?=$this->url->create('comment/deleteReply/' . $reply->id)?> "> Delete</a> 
        <?php endif; ?>
        <?php endif; ?>
        </div> 
        
   <?php else : ?>
   
        <div class="replies">
        <span class="comment-time">
        <?=substr($this->textFilter->doFilter($reply->content, 'markdown'), 0, 200)?>
        </span>
        
        <a class="comment" href='
        <?=$this->url->create('users/acronym/')?>/<?=$reply->deletedBy?>' title="View user profile"><?=$reply->deletedBy?></a>
                
        <span class="comment-time">
        deleted this at:  
        </span>
                
        <span class="comment-time">
        <?=date('Y-m-d H:i:s', strtotime($reply->deleted))?>
        </span>
        </div>
        
   <?php endif; ?> 
       
    <br>
<?php endforeach; ?>

<?php endif; ?> 