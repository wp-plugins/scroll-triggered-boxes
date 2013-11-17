<h2 class="no-top-margin">Scroll Triggered Box Options</h2>

<h3 class="stb-title">Display Options</h3>
<table class="form-table">
	<?php 
	$key = 0;
	foreach($opts['rules'] as $rule) { ?>
		<tr valign="top" class="stb-rule-row">
			<th><label>Show this box</label></th>
			<td class="stb-sm">
				<select class="widefat stb-rule-condition" name="stb[rules][<?php echo $key; ?>][condition]">
					<optgroup label="Basic">
						<option value="everywhere" <?php selected($rule['condition'], 'everywhere')?>>Everywhere</option>
						<option value="is_post_type" <?php selected($rule['condition'], 'is_post_type'); ?>>if Post Type is</option>
						<option value="is_page" <?php selected($rule['condition'], 'is_page'); ?>>if Page is</option>
						<option value="is_single" <?php selected($rule['condition'], 'is_single'); ?>>if Post is</option>
					</optgroup>
					<optgroup label="Advanced">
						<option value="manual" <?php selected($rule['condition'], 'manual'); ?>>Manual conditonal tag</option>
					</optgroup>
				</select>
			</td>
			<td>
				<input class="stb-rule-value widefat" name="stb[rules][<?php echo $key; ?>][value]" type="text" value="<?php echo esc_attr($rule['value']); ?>" placeholder="Leave empty for any or enter (comma-separated) names or ID's" <?php if($rule['condition'] == 'everywhere') { echo 'style="display: none;"'; } ?> />
			</td>
			<td class="stb-xsm" width="1"><span class="stb-close stb-remove-rule">×</span></td>
		</tr>
	<?php $key++;
	} ?>
	<tr>
		<th></th>
		<td colspan="3"><button type="button" class="button stb-add-rule">Add rule</button></td>
	</tr>
	<tr valign="top">
		<th><label for="stb_position">Box Position</label></th>
		<td>
			<select id="stb_position" name="stb[css][position]" class="widefat">
				<option value="top-left" <?php selected($opts['css']['position'], 'top-left'); ?>>Top Left</option>
				<option value="top-right" <?php selected($opts['css']['position'], 'top-right'); ?>>Top Right</option>
				<option value="bottom-left" <?php selected($opts['css']['position'], 'bottom-left'); ?>>Bottom Left</option>
				<option value="bottom-right" <?php selected($opts['css']['position'], 'bottom-right'); ?>>Bottom Right</option>
			</select>
		</td>
		<td colspan="2"></td>
	</tr>
	<tr valign="top">
		<th><label for="stb_trigger">Trigger Point</label></th>
		<td class="stb-sm">
			<select id="stb_trigger" name="stb[trigger]" class="widefat">
				<optgroup label="Basic">
					<option value="percentage" <?php selected($opts['trigger'], 'percentage'); ?>>% of page height</option>
				</optgroup>
				<optgroup label="Advanced">
					<option value="element" <?php selected($opts['trigger'], 'element'); ?>>Element Selector</option>
				</optgroup>
			</select>
		</td>
		<td>
			<input type="number" class="stb-trigger-percentage" name="stb[trigger_percentage]" min="0" max="100" value="<?php echo esc_attr($opts['trigger_percentage']); ?>" <?php if($opts['trigger'] != 'percentage') {  echo 'style="display: none;"'; } ?> />
			<input type="text" class="stb-trigger-element widefat" name="stb[trigger_element]" value="<?php echo esc_attr($opts['trigger_element']); ?>" placeholder="Example: #comments" <?php if($opts['trigger'] != 'element') { echo 'style="display: none;"'; } ?> />
		</td>
	</tr>
	<tr valign="top">
	<th><label>Animation</label></th>
		<td>
			<label><input type="radio" name="stb[animation]" value="fade" <?php checked($opts['animation'], 'fade'); ?> /> Fade In</label> &nbsp; 
			<label><input type="radio" name="stb[animation]" value="slide" <?php checked($opts['animation'], 'slide'); ?> /> Slide In</label>
		</td>
		<td  colspan="2"><small class="help">Which animation type should be used to show the box when triggered?</small></td>
	</tr>
	<tr valign="top">
		<th><label for="stb_cookie">Cookie expiration days</label></th>
		<td>
			<input type="number" id="stb_cookie" name="stb[cookie]" min="0" step="1" value="<?php echo esc_attr($opts['cookie']); ?>" />
		</td>
		<td  colspan="2"><small class="help">After closing the box, how many days should it stay hidden?</small></td>
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