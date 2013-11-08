<h2 class="no-top-margin">Scroll Triggered Box Options</h2>

<h3 class="stb-title">Display Options</h3>
<table class="form-table">
	<?php 
	$key = 0;
	foreach($opts['rules'] as $rule) { ?>
		<tr valign="top" class="stb-rule-row">
			<th><label>Show this box if</label></th>
			<td class="stb-sm">
				<select class="widefat stb-rule-condition" name="stb[rules][<?php echo $key; ?>][condition]">
					<optgroup label="Basic">
						<option value="is_post_type" <?php selected($rule['condition'], 'is_post_type'); ?>>Post Type is</option>
						<option value="is_page" <?php selected($rule['condition'], 'is_page'); ?>>Page is</option>
						<option value="is_single" <?php selected($rule['condition'], 'is_single'); ?>>Post is</option>
					</optgroup>
					<optgroup label="Advanced">
						<option value="manual" <?php selected($rule['condition'], 'manual'); ?>>Manual conditonal tag</option>
					</optgroup>
				</select>
			</td>
			<td>
				<input class="stb-rule-value widefat" name="stb[rules][<?php echo $key; ?>][value]" type="text" value="<?php echo esc_attr($rule['value']); ?>" placeholder="Leave empty for any or enter (comma-separated) names or ID's" />
			</td>
			<td class="stb-xsm" width="1"><span class="stb-close stb-remove-rule">×</span></td>
		</tr>
	<?php $key++;
	} ?>
	<tr>
		<th></th>
		<td colspan="4"><button type="button" class="button stb-add-rule">Add rule</button></td>
	</tr>
</table>
<table class="form-table">
	<tr valign="top">
		<th><label for="stb_position">Box Position</label></th>
		<td>
			<select id="stb_position" name="stb[css][position]" class="widefat">
				<option value="top-left" <?php selected($opts['css']['position'], 'top-left'); ?>>Top Left</option>
				<option value="top-right" <?php selected($opts['css']['position'], 'top-right'); ?>>Top Right</option>
				<option value="bottom-left" <?php selected($opts['css']['position'], 'bottom-left'); ?>>Bottom Left</option>
				<option value="bottom-left" <?php selected($opts['css']['position'], 'bottom-right'); ?>>Bottom Right</option>
			</select>
		</td>
		<td></td>
	</tr>
	<?php /*<tr valign="top">
		<th><label for="stb_trigger">Trigger Point</label></th>
		<td class="stb-sm">
			<select id="stb_trigger" name="stb[trigger]" class="widefat">
				<option value="percentage" <?php selected($opts['trigger'], 'percentage'); ?>>Percentage (%) of page height</option>
				<option value="element" <?php selected($opts['trigger'], 'element'); ?>>Element #ID</option>
			</select>
		</td>
		<td>
			<input type="number" class="stb-trigger-percentage" name="stb[trigger_percentage]" min="0" max="100" value="<?php echo esc_attr($opts['trigger_percentage']); ?>" />
			<input type="text" class="stb-trigger-element widefat" name="stb[trigger_element]" value="<?php echo esc_attr($opts['trigger_element']); ?>" placeholder="Example: #comments" style="display: none;" />
		</td>
	</tr> */ ?>
	<tr valign="top">
		<th><label for="stb_cookie">Cookie expiration days</label></th>
		<td>
			<input type="number" id="stb_cookie" name="stb[cookie]" min="0" step="1" value="<?php echo esc_attr($opts['cookie']); ?>" />
		</td>
		<td><small class="help">After closing the box, how many days should it stay hidden?</small></td>
	</tr>
</table>

<h3 class="stb-title">Appearance</h3>
<table class="form-table">
	<tr valign="top">
		<th>Background color</th><td><input id="stb-background-color" name="stb[css][background_color]" type="text" class="stb-color-field" value="<?php echo esc_attr($opts['css']['background_color']); ?>" /></td>
		<th>Text color</th><td><input id="stb-color" name="stb[css][color]" type="text" class="stb-color-field" value="<?php echo esc_attr($opts['css']['color']); ?>" /></td>
		<th>Box width</th><td><input id="stb-width" name="stb[css][width]" id="stb-box-width" type="number" min="100" max="1600" value="<?php echo esc_attr($opts['css']['width']); ?>" /></td>
	</tr>
	<tr valign="top">
		<th>Border color</th><td><input name="stb[css][border_color]" id="stb-border-color" type="text" class="stb-color-field" value="<?php echo esc_attr($opts['css']['border_color']); ?>" /></td>
		<th>Border width</th><td><input name="stb[css][border_width]" id="stb-border-width" type="number" min="0" max="25" value="<?php echo esc_attr($opts['css']['border_width']); ?>" /></td>
		<th></th><td></td>
	</tr>
</table>

<?php wp_nonce_field( 'stb_options', 'stb_options_nonce' ); ?>