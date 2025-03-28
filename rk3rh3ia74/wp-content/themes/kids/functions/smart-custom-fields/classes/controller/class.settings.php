<?php
/**
 * Smart_Custom_Fields_Controller_Settings
 * Version    : 1.3.0
 * Author     : inc2734
 * Created    : September 23, 2014
 * Modified   : May 31, 2016
 * License    : GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class Theme_Smart_Custom_Fields_Controller_Settings {

	/**
	 * Selectbox choices of the field selection
	 * @var array
	 */
	private $optgroups = array();

	/**
	 * __construct
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		$this->optgroups = array(
			'basic-fields' => array(
				'label'   => esc_attr__( 'Basic fields', 'smart-custom-fields' ),
				'options' => array(),
			),
			'select-fields' => array(
				'label'   => esc_attr__( 'Select fields', 'smart-custom-fields' ),
				'options' => array(),
			),
			'content-fields' => array(
				'label'   => esc_attr__( 'Content fields', 'smart-custom-fields' ),
				'options' => array(),
			),
			'other-fields' => array(
				'label'   => esc_attr__( 'Other fields', 'smart-custom-fields' ),
				'options' => array(),
			),
		);
	}

	/**
	 * Loading resources
	 */
	public function admin_enqueue_scripts() {
		do_action( Theme_SCF_Config::PREFIX . 'before-settings-enqueue-scripts' );
		wp_enqueue_style(
			Theme_SCF_Config::PREFIX . 'settings',
			get_stylesheet_directory_uri(). '/functions/' . Theme_SCF_Config::NAME . '/css/settings.css'
		);
		wp_enqueue_script(
			Theme_SCF_Config::PREFIX . 'settings',
			get_stylesheet_directory_uri(). '/functions/' . Theme_SCF_Config::NAME . '/js/settings.js',
			array( 'jquery' ),
			null,
			true
		);
		wp_localize_script( Theme_SCF_Config::PREFIX . 'settings', 'smart_cf_settings', array(
			'duplicate_alert' => esc_html__( 'Same name exists!', 'smart-custom-fields' ),
		) );
		wp_enqueue_script( 'jquery-ui-sortable' );
		do_action( Theme_SCF_Config::PREFIX . 'after-settings-enqueue-scripts' );
	}

	/**
	 * Adding meta boxes
	 */
	public function add_meta_boxes() {
		add_meta_box(
			Theme_SCF_Config::PREFIX . 'meta-box',
			__( 'Custom Fields', 'smart-custom-fields' ),
			array( $this, 'display_meta_box' ),
			Theme_SCF_Config::NAME
		);
		add_meta_box(
			Theme_SCF_Config::PREFIX . 'meta-box-condition-post',
			__( 'Display conditions ( Post )', 'smart-custom-fields' ),
			array( $this, 'display_meta_box_condition_post' ),
			Theme_SCF_Config::NAME,
			'side'
		);
		add_meta_box(
			Theme_SCF_Config::PREFIX . 'meta-box-condition-profile',
			__( 'Display conditions ( Profile )', 'smart-custom-fields' ),
			array( $this, 'display_meta_box_condition_profile' ),
			Theme_SCF_Config::NAME,
			'side'
		);
		add_meta_box(
			Theme_SCF_Config::PREFIX . 'meta-box-condition-taxonomy',
			__( 'Display conditions ( Taxonomy )', 'smart-custom-fields' ),
			array( $this, 'display_meta_box_condition_taxonomy' ),
			Theme_SCF_Config::NAME,
			'side'
		);
		add_meta_box(
			Theme_SCF_Config::PREFIX . 'meta-box-condition-options-page',
			__( 'Display conditions ( Options page )', 'smart-custom-fields' ),
			array( $this, 'display_meta_box_condition_options_page' ),
			Theme_SCF_Config::NAME,
			'side'
		);
	}

	/**
	 * Displaying "hide" if $key isn't empty
	 *
	 * @param string $key
	 */
	private function add_hide_class( $key ) {
		if ( !$key ) {
			echo 'hide';
		}
	}

	/**
	 * Displaying custom fields
	 */
	public function display_meta_box() {
		$Setting = Theme_SCF::add_setting( get_the_ID(), get_the_title() );
		$Setting->add_group_unshift();
		$groups  = $Setting->get_groups();
		?>
		<div class="<?php echo esc_attr( Theme_SCF_Config::PREFIX . 'fields-wrapper' ); ?>">
			<div class="<?php echo esc_attr( Theme_SCF_Config::PREFIX . 'groups' ); ?>">
			<?php foreach ( $groups as $group_key => $Group ) : ?>
				<?php
				$fields = $Group->get_fields();
				array_unshift( $fields, Theme_SCF::get_form_field_instance( 'text' ) );
				?>
				<div class="<?php echo esc_attr( Theme_SCF_Config::PREFIX . 'group' ); ?> <?php $this->add_hide_class( $group_key ); ?>">
					<div class="btn-remove-group"><span class="dashicons dashicons-no-alt"></span></div>
					<?php $Group->display_options( $group_key ); ?>

					<div class="<?php echo esc_attr( Theme_SCF_Config::PREFIX . 'fields' ); ?>">
						<?php foreach ( $fields as $field_key => $Field ) : ?>
						<div class="<?php echo esc_attr( Theme_SCF_Config::PREFIX . 'field' ); ?> <?php $this->add_hide_class( $field_key ); ?>">
							<?php
							$field_name  = $Field->get( 'name' );
							$field_label = $Field->get( 'label' );
							if ( !$field_label ) {
								$field_label = $field_name;
								if ( !$field_label ) {
									$field_label = "&nbsp;";
								}
							}
							?>
							<div class="<?php echo esc_attr( Theme_SCF_Config::PREFIX . 'icon-handle' ); ?>"></div>
							<b class="btn-remove-field"><span class="dashicons dashicons-no-alt"></span></b>
							<div class="field-label">
								<?php echo esc_html( $field_label ); ?>
								<?php if ( $field_name ) : ?>
									<small>[ <?php echo esc_html( $field_name ); ?> ]</small>
								<?php endif; ?>
							</div>
							<table class="<?php $this->add_hide_class( !$Field->get( 'name' ) ); ?>">
								<tr>
									<th><?php esc_html_e( 'Type', 'smart-custom-fields' ); ?><span class="<?php echo esc_attr( Theme_SCF_Config::PREFIX . 'require' ); ?>">*</span></th>
									<td>
										<select
											name="<?php echo esc_attr( $Field->get_field_name_in_setting( $group_key, $field_key, 'type' ) ); ?>"
											class="<?php echo esc_attr( Theme_SCF_Config::PREFIX . 'field-select' ); ?>" />
											<?php
											foreach ( $this->optgroups as $optgroup_name => $optgroup_values ) {
												$optgroup_fields = array();
												$optgroup_values['options'] = apply_filters(
													Theme_SCF_Config::PREFIX . 'field-select-' . $optgroup_name,
													$optgroup_values['options']
												);
												foreach ( $optgroup_values['options'] as $option_key => $option ) {
													$optgroup_fields[] = sprintf(
														'<option value="%s" %s>%s</option>',
														esc_attr( $option_key ),
														selected( $Field->get_attribute( 'type' ), $option_key, false ),
														esc_html( $option )
													);
												}
												printf(
													'<optgroup label="%s">%s</optgroup>',
													$optgroup_values['label'],
													implode( '', $optgroup_fields )
												);
											}
											?>
										</select>
									</td>
								</tr>
								<?php $Field->display_options( $group_key, $field_key ); ?>
							</table>
						</div>
						<?php endforeach; ?>
					</div>
					<div class="button btn-add-field <?php $this->add_hide_class( $Group->is_repeatable() ); ?>"><?php esc_html_e( 'Add Sub field', 'smart-custom-fields' ); ?></div>
				</div>
			<?php endforeach; ?>
			</div>
			<div class="button btn-add-group"><?php esc_html_e( 'Add Field', 'smart-custom-fields' ); ?></div>
		</div>
		<?php wp_nonce_field( Theme_SCF_Config::NAME . '-settings', Theme_SCF_Config::PREFIX . 'settings-nonce' ) ?>
		<?php
	}

	/**
	 * Displaying the meta box to set the display conditions for post edit page
	 */
	public function display_meta_box_condition_post() {
		$post_types = get_post_types( array(
			'show_ui' => true,
		), 'objects' );
		unset( $post_types['attachment'] );
		unset( $post_types[Theme_SCF_Config::NAME] );

		$conditions = get_post_meta( get_the_ID(), Theme_SCF_Config::PREFIX . 'condition', true );
		$post_type_field = '';
		foreach ( $post_types as $post_type => $post_type_object ) {
			$current = ( is_array( $conditions ) && in_array( $post_type, $conditions ) ) ? $post_type : false;
			$post_type_field .= sprintf(
				'<label><input type="checkbox" name="%s" value="%s" %s /> %s</label>',
				esc_attr( Theme_SCF_Config::PREFIX . 'condition[]' ),
				esc_attr( $post_type ),
				checked( $current, $post_type, false ),
				esc_attr( $post_type_object->labels->singular_name )
			);
		}
		printf(
			'<p><b>%s</b>%s</p>',
			esc_html__( 'Post Types', 'smart-custom-fields' ),
			$post_type_field
		);

		$condition_post_ids = get_post_meta( get_the_ID(), Theme_SCF_Config::PREFIX . 'condition-post-ids', true );
		printf(
			'<p><b>%s</b><input type="text" name="%s" value="%s" class="widefat" /></p>',
			esc_html__( 'Post Ids ( Comma separated )', 'smart-custom-fields' ),
			esc_attr( Theme_SCF_Config::PREFIX . 'condition-post-ids' ),
			$condition_post_ids
		);
	}

	/**
	 *  Displaying the meta box to set the display conditions for profile edit page
	 */
	public function display_meta_box_condition_profile() {
		$roles = get_editable_roles();
		$conditions = get_post_meta( get_the_ID(), Theme_SCF_Config::PREFIX . 'roles', true );
		$profile_field = '';
		foreach ( $roles as $name => $role ) {
			$current = ( is_array( $conditions ) && in_array( $name, $conditions ) ) ? $name : false;
			$profile_field .= sprintf(
				'<label><input type="checkbox" name="%s" value="%s" %s /> %s</label>',
				esc_attr( Theme_SCF_Config::PREFIX . 'roles[]' ),
				esc_attr( $name ),
				checked( $current, $name, false ),
				translate_user_role( $role['name'] )
			);
		}
		printf(
			'<p><b>%s</b>%s</p>',
			esc_html__( 'Roles', 'smart-custom-fields' ),
			$profile_field
		);
	}

	/**
	 *  Displaying the meta box to set the display conditions for term edit page
	 */
	public function display_meta_box_condition_taxonomy() {
		$taxonomies = get_taxonomies( array(
			'show_ui' => true,
		), 'objects' );
		$conditions = get_post_meta( get_the_ID(), Theme_SCF_Config::PREFIX . 'taxonomies', true );
		$taxonomy_field = '';
		foreach ( $taxonomies as $name => $taxonomy ) {
			$current = ( is_array( $conditions ) && in_array( $name, $conditions ) ) ? $name : false;
			$taxonomy_field .= sprintf(
				'<label><input type="checkbox" name="%s" value="%s" %s /> %s</label>',
				esc_attr( Theme_SCF_Config::PREFIX . 'taxonomies[]' ),
				esc_attr( $name ),
				checked( $current, $name, false ),
				esc_html__( $taxonomy->label, 'smart-custom-fields' )
			);
		}
		printf(
			'<p><b>%s</b>%s</p>',
			esc_html__( 'Taxonomies', 'smart-custom-fields' ),
			$taxonomy_field
		);
	}

	/**
	 *  Displaying the meta box to set the display conditions for custom options page
	 */
	public function display_meta_box_condition_options_page() {
		$optinos_pages = Theme_SCF::get_options_pages();
		$conditions = get_post_meta( get_the_ID(), Theme_SCF_Config::PREFIX . 'options-pages', true );
		$options_page_field = '';
		foreach ( $optinos_pages as $name => $optinos_page ) {
			$current = ( is_array( $conditions ) && in_array( $name, $conditions ) ) ? $name : false;
			$options_page_field .= sprintf(
				'<label><input type="checkbox" name="%s" value="%s" %s /> %s</label>',
				esc_attr( Theme_SCF_Config::PREFIX . 'options-pages[]' ),
				esc_attr( $name ),
				checked( $current, $name, false ),
				esc_html( $optinos_page )
			);
		}
		printf(
			'<p><b>%s</b>%s</p>',
			esc_html__( 'Options pages', 'smart-custom-fields' ),
			$options_page_field
		);
	}

	/**
	 * Saving settings
	 *
	 * @param int $post_id
	 */
	public function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( !isset( $_POST[Theme_SCF_Config::NAME] ) ) {
			return;
		}
		check_admin_referer(
			Theme_SCF_Config::NAME . '-settings',
			Theme_SCF_Config::PREFIX . 'settings-nonce'
		);

		$data = array();
		foreach ( $_POST[Theme_SCF_Config::NAME] as $group_key => $group_value ) {
			// $group_key = 0 is hidden field so don't save
			if ( $group_key === 0 ) {
				continue;
			}
			if ( !empty( $group_value['fields'] ) && count( $group_value['fields'] ) > 1 ) {
				$fields = array();
				foreach ( $group_value['fields'] as $field_key => $field_value ) {
					// $field_key = 0 is hidden field so don't save
					if ( $field_key === 0 ) {
						continue;
					}
					if ( !empty( $field_value['name'] ) ) {
						$fields[] = $field_value;
					}
				}
				if ( !$fields ) {
					continue;
				}

				if ( !empty( $group_value['repeat'] ) && $group_value['repeat'] === 'true' ) {
					$group_value['repeat'] = true;
				} else {
					$group_value['repeat'] = false;
				}

				// If "repeat" isn't true, empty name
				// If "repeat" is true and name is empty, assign index
				if ( !( isset( $group_value['repeat'] ) && $group_value['repeat'] === true && !empty( $group_value['group-name'] ) ) ) {
					$group_value['group-name'] = $group_key;
				}

				$group_value['fields'] = $fields;
				$data[] = $group_value;
			}
		}
		update_post_meta( $post_id, Theme_SCF_Config::PREFIX . 'setting', $data );

		if ( !isset( $_POST[Theme_SCF_Config::PREFIX . 'condition'] ) ) {
			delete_post_meta( $post_id, Theme_SCF_Config::PREFIX . 'condition' );
		} else {
			update_post_meta( $post_id, Theme_SCF_Config::PREFIX . 'condition', $_POST[Theme_SCF_Config::PREFIX . 'condition'] );
		}

		if ( !isset( $_POST[Theme_SCF_Config::PREFIX . 'condition-post-ids'] ) ) {
			delete_post_meta( $post_id, Theme_SCF_Config::PREFIX . 'condition-post-ids' );
		} else {
			update_post_meta( $post_id, Theme_SCF_Config::PREFIX . 'condition-post-ids', $_POST[Theme_SCF_Config::PREFIX . 'condition-post-ids'] );
		}

		if ( !isset( $_POST[Theme_SCF_Config::PREFIX . 'roles'] ) ) {
			delete_post_meta( $post_id, Theme_SCF_Config::PREFIX . 'roles' );
		} else {
			update_post_meta( $post_id, Theme_SCF_Config::PREFIX . 'roles', $_POST[Theme_SCF_Config::PREFIX . 'roles'] );
		}

		if ( !isset( $_POST[Theme_SCF_Config::PREFIX . 'taxonomies'] ) ) {
			delete_post_meta( $post_id, Theme_SCF_Config::PREFIX . 'taxonomies' );
		} else {
			update_post_meta( $post_id, Theme_SCF_Config::PREFIX . 'taxonomies', $_POST[Theme_SCF_Config::PREFIX . 'taxonomies'] );
		}

		if ( !isset( $_POST[Theme_SCF_Config::PREFIX . 'options-pages'] ) ) {
			delete_post_meta( $post_id, Theme_SCF_Config::PREFIX . 'options-pages' );
		} else {
			update_post_meta( $post_id, Theme_SCF_Config::PREFIX . 'options-pages', $_POST[Theme_SCF_Config::PREFIX . 'options-pages'] );
		}
	}
}
