<h1><?=$title?></h1>

<?php if(isset($_SESSION["user"]) AND $_SESSION["user"]->acronym == "admin") : ?>

    <p>Click on the username to see a detailed view of the user.</p>

    <table class='users'>
    <tr>
        <th>ID</th><th>Username</th><th>Name</th><th>Email</th><th>Update</th><th>Soft delete/recover</th><th>Active</th>
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
        <td><a href='<?= $this->url->create("users/update/{$user->id}") ?>'>Update</a></td>
    
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