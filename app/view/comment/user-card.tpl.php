<div class='user-card'>
<h3>Welcome to the forum</h3>

<?php if(isset($_SESSION["user"])) : ?>

    User: <a title="Your page" href='<?=$this->url->create('users/id/')?>/<?=$_SESSION["user"]->id?>'><?=$_SESSION["user"]->acronym?></a>
    <br>
    <p class=""><a title="logout" href='<?=$this->url->create('users/logout')?>'> Log out <?=$_SESSION["user"]->acronym?> <i class="fa fa-sign-out" aria-hidden="true"></i> </a></p>

<?php else : ?>  
    
    <p class=""><a title="login" href='<?=$this->url->create('users/login')?>'> Log in <i class="fa fa-sign-in" aria-hidden="true"></i> </a></p>
    <p class=""><a title="sign up" href='<?=$this->url->create('users/add')?>'> Sign up <i class="fa fa-user-plus" aria-hidden="true"></i> </a></p>
    
<?php endif; ?>    
</div>
