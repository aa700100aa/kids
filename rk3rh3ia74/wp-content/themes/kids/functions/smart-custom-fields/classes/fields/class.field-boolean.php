<?php
/**
 * Smart_Custom_Fields_Field_Boolean
 * Version    : 1.1.1
 * Author     : Toro_Unit, inc2734
 * Created    : April 6, 2015
 * Modified   : July 28, 2016
 * License    : GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

class Theme_Smart_Custom_Fields_Field_Boolean extends Theme_Smart_Custom_Fields_Field_Base {

	/**
	 * Set the required items
	 *
	 * @return array
	 */
	protected function init() {
		add_filter( 'smart-cf-validate-get-value', array( $this, 'validate_get_value' ), 10, 2 );
		return array(
			'type'         => 'boolean',
			'display-name' => __( 'Boolean', 'smart-custom-fields' ),
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
			'default'     => 0,
			'instruction' => '',
			'notes'       => '',
			'true_label'  => __( 'Yes', 'smart-custom-fields' ),
			'false_label' => __( 'No', 'smart-custom-fields' ),
		);
	}

	/**
	 * Getting the field
	 *
	 * @param int $index
	 * @param int $value
	 * @return string html
	 */
	public function get_field( $index, $value ) {
		$name     = $this->get_field_name_in_editor( $index );
		$disabled = $this->get_disable_attribute( $index );

		$true = sprintf(
			'<label><input type="radio" name="%s" value="1" class="widefat" %s %s />%s ( true )</label>',
			esc_attr( $name ),
			checked( 1, $value, false ),
			disabled( true, $disabled, false ),
			$this->get( 'true_label' )
		);

		$false = sprintf(
			'<label><input type="radio" name="%s" value="0" class="widefat" %s %s />%s ( false )</label>',
			esc_attr( $name ),
			checked( 0, $value, false ),
			disabled( true, $disabled, false ),
			$this->get( 'false_label' )
		);

		return $true . $false;
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
				<fieldset>

					<label>
						<input type="radio"
							name="<?php echo esc_attr( $this->get_field_name_in_setting( $group_key, $field_key, 'default' ) ); ?>"
							value="1"
							<?php checked( 1, $this->get( 'default' ) ); ?>
						/>
						<span><?php echo esc_html( $this->get( 'true_label' ) ); ?> ( true )</span>
					</label>&nbsp;
					<label>
						<input type="radio"
							name="<?php echo esc_attr( $this->get_field_name_in_setting( $group_key, $field_key, 'default' ) ); ?>"
							value="0"
							<?php checked( 0, $this->get( 'default' ) ); ?>
						/>
						<span><?php echo esc_html( $this->get( 'false_label' ) ); ?> ( false )</span>
					</label>

				</fieldset>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'TRUE Label', 'smart-custom-fields' ); ?></th>
			<td>
				<input type="text"
					name="<?php echo esc_attr( $this->get_field_name_in_setting( $group_key, $field_key, 'true_label' ) ); ?>"
					class="widefat"
					value="<?php echo esc_attr( $this->get( 'true_label' ) ); ?>"
				/>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'FALSE Label', 'smart-custom-fields' ); ?></th>
			<td>
				<input type="text"
					name="<?php echo esc_attr( $this->get_field_name_in_setting( $group_key, $field_key, 'false_label' ) ); ?>"
					class="widefat"
					value="<?php echo esc_attr( $this->get( 'false_label' ) ); ?>"
				/>
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

	/**
	 * Validating when displaying meta data
	 *
	 * @param int|string $value
	 * @param string $field_type
	 * @return boolean
	 */
	public function validate_get_value( $value, $field_type ) {
		if ( $field_type === $this->get_attribute( 'type' ) ) {
			if ( is_array( $value ) ) {
				foreach ( $value as $key => $val ) {
					$value[$key] = !!$val;
				}
			} else {
				$value = !!$value;
			}
		}
		return $value;
	}
}
