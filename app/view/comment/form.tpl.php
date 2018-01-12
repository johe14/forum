<div class='comment-form'>
    <form method=post>
        <input type=hidden name="redirect" value="<?=$pagekey == 'comment' ? $this->url->create('comment') : $this->url->create('')?>">
        <input type=hidden name="pagekey" value="<?=$pagekey?>">
        <fieldset>
        <legend>Leave a comment here &#160;</legend>
        <p><label>Comment:<br/><textarea name='content' ><?=strip_tags($content)?></textarea></label></p>
        <p><label>Name:*<br/><input type='text' name='name' required value='<?=$name?>' /></label></p>
        <p><label>Homepage:<br/><input type='text' name='web' value='<?=$web?>' /></label></p>
        <p><label>Email:*<br/><input type='text' name='mail' required value='<?=$mail?>' /></label></p>
        <p><small>* required field</small></p>
        <p class=buttons>
            <input type='submit' name='doCreate' value='Comment' onClick="this.form.action = '<?=$this->url->create('comment/add')?>'"/>
            <input type='reset' value='Reset'/>
            
        </p>
        <output><?=$output?></output>
        </fieldset>
    </form>
</div>
