<div class='comment-form'>
    <form method=post>
        <input type=hidden name="redirect" value="<?=$pagekey == 'comment' ? $this->url->create('comment') : $this->url->create('')?>">
        <input type=hidden name="pagekey" value="<?=$pagekey?>">
        <fieldset>
        <legend>Update your comment &#160;</legend>
        <p><label>Comment:<br/><textarea name='content' ><?=strip_tags($comment['content'])?></textarea></label></p>
        <p><label>Name:*<br/><input type='text' name='name' value='<?=$comment['name']?>' /></label></p>
        <p><label>Homepage:<br/><input type='text' name='web' value='<?=$comment['web']?>' /></label></p>
        <p><label>Email:*<br/><input type='text' name='mail' value='<?=$comment['mail']?>' /></label></p>
        <p><small>* required field</small></p>
        <p class=buttons>
            <input type='submit' name='doEdit' value='Save updates' onClick="this.form.action = '<?=$this->url->create('comment/edit/' . $id . '/' . $pagekey)?>'"/>
            <input type='reset' value='Reset'/>
            <input type='submit' name='doRemoveOne' value='Remove comment' onClick="this.form.action = '<?=$this->url->create('comment/remove-one/' . $id . '/' . $pagekey)?>'"/>
        
            <input type='submit' name='doRemoveAll' value='Remove all on page' onClick="this.form.action = '<?=$this->url->create('comment/remove-all/' . $pagekey)?>'"/>
        </p>
        
        </fieldset>
    </form>
</div>