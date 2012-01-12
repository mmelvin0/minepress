<?php
$id = $this->get_field_id('title');
$name = $this->get_field_name('title');
?>
<p>
<label for="<?=$id?>"><?_e('Title:')?></label>
<input class="widefat" id="<?=$id?>" name="<?=$name?>" type="text" value="<?=esc_attr($title)?>">
</p>
