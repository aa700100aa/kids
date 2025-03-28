<?php
/**
 * Smart_Custom_Fields_Field_Colorpicker
 * Version    : 1.2.0
 * Author     : inc2734
 * Created    : October 21, 2014
 * Modified   : June 4, 2016
 * License    : GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class Theme_Smart_Custom_Fields_Field_Colorpicker extends Theme_Smart_Custom_Fields_Field_Base {

	/**
	 * Set the required items
	 *
	 * @return array
	 */
	protected function init() {
		add_action(
			Theme_SCF_Config::PREFIX . 'before-editor-enqueue-scripts',
			array( $this, 'editor_enqueue_scripts' )
		);
		add_action(
			Theme_SCF_Config::PREFIX . 'before-settings-enqueue-scripts',
			array( $this, 'settings_enqueue_scripts' )
		);
		return array(
			'type'         => 'colorpicker',
			'display-name' => __( 'Color picker', 'smart-custom-fields' ),
			'optgroup'     => 'other-fields',
		);
	}

	/**
	 * Set the non required items
	 *
	 * @return array
	 */
	protected function options() {
		return array(
			'default'     => '',
			'instruction' => '',
			'notes'       => '',
		);
	}

	/**
	 * Loading resources for editor
	 */
	public function editor_enqueue_scripts() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script(
			Theme_SCF_Config::PREFIX . 'editor-colorpicker',
			get_stylesheet_directory_uri(). '/functions/' . Theme_SCF_Config::NAME . '/js/editor-colorpicker.js',
			array( 'jquery', 'wp-color-picker' ),
			false,
			true
		);
	}

	/**
	 * Loading resources for editor for custom field settings page
	 */
	public function settings_enqueue_scripts() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script(
			Theme_SCF_Config::PREFIX . 'settings-colorpicker',
			get_stylesheet_directory_uri(). '/functions/' . Theme_SCF_Config::NAME . '/js/settings-colorpicker.js',
			array( 'jquery', 'wp-color-picker' ),
			false,
			true
		);
	}

	/**
	 * Getting the field
	 *
	 * @param int $index
	 * @param string $value
	 * @return string html
	 */
	public function get_field( $index, $value ) {
		$name     = $this->get_field_name_in_editor( $index );
		$disabled = $this->get_disable_attribute( $index );
		return sprintf(
			'<input type="text" name="%s" value="%s" class="%s" %s />',
			esc_attr( $name ),
			esc_attr( $value ),
			esc_attr( Theme_SCF_Config::PREFIX . 'colorpicker' ),
			disabled( true, $disabled, false )
		);
	}

	/**
	 * Displaying the option fields in custom field settings page
	 *
	 * @param int $group_key
	 * @param int $field_key
	 */
	public function display_field_options( $group_key, $field_key ) {
		?>
		<tr>
			<th><?php esc_html_e( 'Default', 'smart-custom-fields' ); ?></th>
			<td>
				<input type="text"
					name="<?php echo esc_attr( $this->get_field_name_in_setting( $group_key, $field_key, 'default' ) ); ?>"
					class="widefat default-option"
					value="<?php echo esc_attr( $this->get( 'default' ) ); ?>" />
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Instruction', 'smart-custom-fields' ); ?></th>
			<td>
				<textarea name="<?php echo esc_attr( $this->get_field_name_in_setting( $group_key, $field_key, 'instruction' ) ); ?>"
					class="widefat" rows="5"><?php echo esc_attr( $this->get( 'instruction' ) ); ?></textarea>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Notes', 'smart-custom-fields' ); ?></th>
			<td>
				<input type="text"
					name="<?php echo esc_attr( $this->get_field_name_in_setting( $group_key, $field_key, 'notes' ) ); ?>"
					class="widefat"
					value="<?php echo esc_attr( $this->get( 'notes' ) ); ?>"
				/>
			</td>
		</tr>
		<?php
	}
}
