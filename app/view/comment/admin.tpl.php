<h1>Reset the database</h1>

<?php if(isset($_SESSION["user"]) AND $_SESSION["user"]->acronym == "admin") : ?>
    <p>It is recommended to reset all tables at the same time.</p>
    <p><a href='<?=$this->url->create('reset')?>'>Reset questions.</a></p>
    <p><a href='<?=$this->url->create('reset-answers')?>'>Reset answers.</a></p>
    <p><a href='<?=$this->url->create('reset-replies')?>'>Reset replies on questions and answers.</a></p>
    <br>
    <p><i><?=$content?></i></p>
<?php else : ?>
    <p><i>You must login as administrator to reset the forum database.</i></p>
<?php endif; ?>
