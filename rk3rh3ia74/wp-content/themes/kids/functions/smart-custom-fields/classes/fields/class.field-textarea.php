<?php
/**
 * Smart_Custom_Fields_Field_Textarea
 * Version    : 1.3.0
 * Author     : inc2734
 * Created    : October 7, 2014
 * Modified   : June 4, 2016
 * License    : GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class Theme_Smart_Custom_Fields_Field_Textarea extends Theme_Smart_Custom_Fields_Field_Base {

	/**
	 * Set the required items
	 *
	 * @return array
	 */
	protected function init() {
		return array(
			'type'         => 'textarea',
			'display-name' => __( 'Textarea', 'smart-custom-fields' ),
			'optgroup'     => 'basic-fields',
		);
	}

	/**
	 * Set the non required items
	 *
	 * @return array
	 */
	protected function options() {
		return array(
			'rows'        => 5,
			'default'     => '',
			'instruction' => '',
			'notes'       => '',
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
		$rows     = $this->get( 'rows' );
		return sprintf(
			'<textarea name="%s" rows="%d" class="widefat" %s>%s</textarea>',
			esc_attr( $name ),
			esc_attr( $rows ),
			disabled( true, $disabled, false ),
			$value
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
			<th><?php esc_html_e( 'Rows', 'smart-custom-fields' ); ?></th>
			<td>
				<input type="number"
					name="<?php echo esc_attr( $this->get_field_name_in_setting( $group_key, $field_key, 'rows' ) ); ?>"
					min="3"
					value="<?php echo esc_attr( $this->get( 'rows' ) ); ?>"
				/>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Default', 'smart-custom-fields' ); ?></th>
			<td>
				<textarea
					name="<?php echo esc_attr( $this->get_field_name_in_setting( $group_key, $field_key, 'default' ) ); ?>"
					class="widefat"
					rows="5"><?php echo esc_textarea( "\n" . $this->get( 'default' ) ); ?></textarea>
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
