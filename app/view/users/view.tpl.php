<h1><?=$title?></h1>

<?php
if ($user->active == null) {
  $active = "No";
  }
  else {
    $active ="Yes";
  }
?>

<img style="height:100px;width:100px;margin-right:7px;" src="http://www.gravatar.com/avatar/<?=md5(strtolower(trim($user->email)))?>?d=retro">
<br><br>

<table class='users'>
<tr>
    <td width="20%"><strong>ID: </strong></td>
    <td><?=$user->id?></td>
</tr>

<tr>
    <td><strong>Username: </strong></td>
    <td><?=$user->acronym?></td>
</tr>

<tr>
    <td><strong>Name: </strong></td>
    <td><?=$user->name?></td>
</tr>

<tr>
    <td><strong>Email: </strong></td>
    <td><?=$user->email?></td>
</tr>

<tr>
    <td><strong>Created: </strong></td>
    <td><?=$user->created?></td>
</tr>

<tr>
    <td><strong>Updated: </strong></td>
    <td><?=$user->updated?></td>
</tr>

<tr>
    <td><strong>Soft-deleted:</strong></td>
    <td><?=$user->deleted?></td>
</tr>

<tr>
    <td><strong>Active: </strong></td>
    <td><?=$active?></td>
</tr>

</table>

<?php if(isset($_SESSION["user"])) : ?>
    <?php if($_SESSION["user"]->acronym == $user->acronym OR $_SESSION["user"]->acronym == "admin") : ?>
        <br>
        <p><a href='<?= $this->url->create("users/update/{$user->id}") ?>'>Update user</a></p>
        <p><a href='<?= $this->url->create("users/SoftDelete/{$user->id}") ?>'>Delete user</a></p>
    <?php endif; ?>
    
    <?php if($_SESSION["user"]->acronym == $user->acronym AND $_SESSION["user"]->acronym == "admin") : ?>
        <p><a href='<?= $this->url->create("users/list") ?>'>List all users</a></p>
    <?php endif; ?> 
    
<?php endif; ?>           

<br>

<h2>Questions asked</h2>
<?php if (!empty($questions)) : ?>
    
    <?php foreach ($questions as $question) : ?>
        <h3><?=$question->question?></h3>
        <?=substr($this->textFilter->doFilter($question->content, 'markdown'), 0, 200)?>
        <a title="View question" href='<?= $this->url->create("comment/id/{$question->id}")?>'> View question </a></p>
    <?php endforeach; ?>
    
<?php else: echo "<p><i>No questions posted.</i></p>" ?>
        
<?php endif; ?>     

<h2>Answers</h2>
<?php if (!empty($answers)) : ?>
    
    <?php foreach ($answers as $answer) : ?>
        <?=substr($this->textFilter->doFilter($answer->content, 'markdown'), 0, 200)?>
        <a title="View related question" href='<?= $this->url->create("comment/id/{$answer->questionID}")?>'> View related question </a></p>
        <?php endforeach; ?>
    
<?php else: echo "<p><i>No answers posted</i></p>" ?>
        
<?php endif; ?>  
