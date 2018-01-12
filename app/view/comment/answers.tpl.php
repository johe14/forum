<h2><?=$title?></h2>

<?php if (!empty($answers)) : ?>

    <?php $divider = '<span class="comment-time">&#160;&#160;&#8226;&#160;&#160;</span>' ?>

    <?php foreach ($answers as $answer) : ?>

        
        <?=substr($this->textFilter->doFilter($answer->content, 'markdown'), 0, 200)?>
        <img style="height:25px;width:25px;margin-right:7px;" alt="gravatar" src="http://www.gravatar.com/avatar/<?=md5(strtolower(trim($answer->email)))?>?d=retro">
    
        <a class="comment" href='
        <?=$this->url->create('users/acronym/')?>/<?=$answer->name?>' title="View user profile"><?=$answer->name?></a>
    
        <?=$divider ?>
    
        <span class="comment-time">
        Posted:
        <?=date('Y-m-d H:i:s', strtotime($answer->created))?>
        </span>
    
        <?php if (!empty($answer->updated)) : ?>
    
            <?=$divider ?>
            <span class="comment-time">
            Updated:
            <?=date('Y-m-d H:i:s', strtotime($answer->updated))?>
            </span>
        
        <?php endif; ?> 
        
        <?php if(isset($_SESSION["user"])) : ?> 
    
            <?=$divider ?>
            <a class="comment" href="<?=$this->url->create('comment/addReplyAnswer/' . $answer->id)?> "> Comment</a>        
        
        <?php endif; ?>    
    
        <br><br>

        <?php if (!empty($replies)) : ?>
        
        
        <?php foreach ($replies as $reply) : ?>
            <?php if ($reply->referenceID == $answer->id) : ?>    
                
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
                <span class="comment-time"><i>
                <?=substr($this->textFilter->doFilter($reply->content, 'markdown'), 0, 200)?>
                </i></span>
                                
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
        <?php endif; ?>  
        
    <?php endforeach; ?>
    <?php endif; ?>



<br><br>      


<?php endforeach; ?>

<?php else: echo "<p><i>No answers yet. </i></p>"; ?>
  
<br><br>  

<?php endif; ?> 