<?php $this->form('hello/csrf', 'post',array('hello' => 'ciao'), 'nocsrf'); ?>
<input type='text' name='test' value='<?php echo "testing"?>' />
<input type='submit' />
<?php $this->endForm(); ?>