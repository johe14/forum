<h1><?=$title?></h1>

<?php if(isset($_SESSION["user"]) AND $_SESSION["user"]->acronym == "admin") : ?>
    
        <h2>Select an action:</h2>
        <p><a href='<?=$this->url->create('setup')?>'>Setup or reset the user database</a></p>
        <p><a href='<?=$this->url->create('users/add')?>'>Add a user</a></p>
        <p><a href='<?=$this->url->create('users/list')?>'>List all users </a>&nbsp;(view details, update, delete)</p>
        <p><a href='<?=$this->url->create('users/wastebasket')?>'>Permanently delete or recover a user</a>&nbsp;(wastebasket)</p>
        <p><a href='<?=$this->url->create('users/active')?>'>List active users </a>&nbsp;(not deleted)</p>
        <p><a href='<?=$this->url->create('users/inactive')?>'>Inactive users </a>&nbsp;(not deleted)</p>
    
    <?php else : ?>  

        <p><i>You must be an administrator to see this view.</i></p>

    <?php endif; ?>
    

