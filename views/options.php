<?php if ( isset($_POST['rt_options'])) {
    check_admin_referer('roptions','rnonce');
    foreach (['op2-1','op2-2','op2-3','op2-4','op2-5','op3'] as $i) {
        if(isset($_POST['rt_options'][$i])) $opt[$i] = 1;
    }
    update_option('rt_options', $opt);
    ?><div class="updated fade"><p><strong><?php _e('Options saved.', 'real-time-title-checker'); ?></strong></p></div>
<?php }
?>
<div class="wrap">
<h2>Real-time TITLE Checker <?php _e('Settings', 'real-time-title-checker'); ?></h2>
    <form action="" method="post">
    	<?php
    	wp_nonce_field('roptions','rnonce');
	    $opt = get_option('rt_options');
    	?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Post Type', 'real-time-title-checker'); ?></th>
                <td>
                	<p><input type="checkbox" id="inputtext" value="publish" disabled='disabled' checked> <?php _e('publish', 'real-time-title-checker'); ?></p>
                	<p><label for="op2-1"><input id="op2-1" type="checkbox" name="rt_options[op2-1]" value="1" <?php if(isset($opt['op2-1'])){echo 'checked'; } ?>> <?php _e('pending', 'real-time-title-checker'); ?></label></p>
                	<p><label for="op2-2"><input id="op2-2" type="checkbox" name="rt_options[op2-2]" value="1" <?php if(isset($opt['op2-2'])){echo 'checked'; } ?>> <?php _e('draft', 'real-time-title-checker'); ?></label></p>
                	<p><label for="op2-3"><input id="op2-3" type="checkbox" name="rt_options[op2-3]" value="1" <?php if(isset($opt['op2-3'])){echo 'checked'; } ?>> <?php _e('private', 'real-time-title-checker'); ?></label></p>
                	<p><label for="op2-4"><input id="op2-4" type="checkbox" name="rt_options[op2-4]" value="1" <?php if(isset($opt['op2-4'])){echo 'checked'; } ?>> <?php _e('future', 'real-time-title-checker'); ?></label></p>
                	<p><label for="op2-5"><input id="op2-5" type="checkbox" name="rt_options[op2-5]" value="1" <?php if(isset($opt['op2-5'])){echo 'checked'; } ?>> <?php _e('trash', 'real-time-title-checker'); ?></label></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e('Separator', 'real-time-title-checker'); ?></th>
                <td>
                    <p><label for="op3"><input id="op3" type="checkbox" name="rt_options[op3]" value="1" <?php if(isset($opt['op3'])){echo 'checked'; } ?>> <?php _e('Including Full-width Space', 'real-time-title-checker'); ?></label></p>
                </td>
            </tr>
        </table>
        <p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e('Save', 'real-time-title-checker'); ?>" /></p>
    </form>
<!-- /.wrap --></div>