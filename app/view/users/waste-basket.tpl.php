<h1><?=$title?></h1>

<?php if(isset($_SESSION["user"]) AND $_SESSION["user"]->acronym == "admin") : ?>

    <p>List of users that are soft-deleted. Users can not be permanent deleted (function has been inactivated).  </p>

    <table class='users'>
    <tr>
        <th>ID</th><th>Username</th><th>Name</th><th>Email</th><th>Recover</th><th>Active</th>
    </tr>

    <?php foreach ($users as $user) : ?>

    <?php
    if ($user->active == null) {
        $active = "No";
        }
        else {
            $active ="Yes";
        }
    ?>

    <tr>

        <td><?= $user->id ?></td>    
        <td><a href='<?= $this->url->create("users/id/{$user->id}") ?>'><?= $user->acronym ?></a></td>
        <td><?= $user->name ?></td>
        <td><?= $user->email ?></td>
    
        <?php
        if ($user->deleted == null) {
            $deleteCheck = 'SoftDelete';
            $linkName = 'Delete'; 
        }
        else {
            $deleteCheck = 'undoSoftDelete';
            $linkName = 'Recover';
        }
        ?>
    
        <td><a href='<?= $this->url->create("users/{$deleteCheck}/{$user->id}") ?>'><?= $linkName ?></a></td>
        <td><?= $active?></td>

    </tr>

    <?php endforeach; ?>

    </table> 
    
<?php else : ?>  

    <p><i>You must be an administrator to see this view.</i></p>

<?php endif; ?>     