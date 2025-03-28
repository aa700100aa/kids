<?php
/**
 * Smart_Custom_Fields_Field_Related_Posts
 * Version    : 1.4.1
 * Author     : inc2734
 * Created    : October 7, 2014
 * Modified   : February 20, 2017
 * License    : GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
class Theme_Smart_Custom_Fields_Field_Related_Posts extends Theme_Smart_Custom_Fields_Field_Base {

	/**
	 * Set the required items
	 *
	 * @return array
	 */
	protected function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_ajax_smart-cf-relational-posts-search', array( $this, 'relational_posts_search' ) );
		add_filter( 'smart-cf-validate-get-value', array( $this, 'validate_get_value' ), 10, 2 );
		return array(
			'type'                => 'relation',
			'display-name'        => __( 'Related Posts', 'smart-custom-fields' ),
			'optgroup'            => 'other-fields',
			'allow-multiple-data' => true,
		);
	}

	/**
	 * Set the non required items
	 *
	 * @return array
	 */
	protected function options() {
		return array(
			'post-type'   => '',
			'limit'       => 0,
			'instruction' => '',
			'notes'       => '',
		);
	}

	/**
	 * Loading resources
	 *
	 * @param string $hook
	 */
	public function admin_enqueue_scripts( $hook ) {
		wp_enqueue_script(
			Theme_SCF_Config::PREFIX . 'editor-relation-common',
			get_stylesheet_directory_uri(). '/functions/' . Theme_SCF_Config::NAME . '/js/editor-relation-common.js',
			array( 'jquery' ),
			null,
			true
		);
		wp_enqueue_script(
			Theme_SCF_Config::PREFIX . 'editor-relation-post-types',
			get_stylesheet_directory_uri(). '/functions/' . Theme_SCF_Config::NAME . '/js/editor-relation-post-types.js',
			array( 'jquery' ),
			null,
			true
		);
		wp_localize_script( Theme_SCF_Config::PREFIX . 'editor-relation-post-types', 'smart_cf_relation_post_types', array(
			'endpoint' => admin_url( 'admin-ajax.php' ),
			'action'   => Theme_SCF_Config::PREFIX . 'relational-posts-search',
			'nonce'    => wp_create_nonce( Theme_SCF_Config::NAME . '-relation-post-types' )
		) );
	}

	/**
	 * Process that loading post when clicking post load button
	 */
	public function relational_posts_search() {
		check_ajax_referer( Theme_SCF_Config::NAME . '-relation-post-types', 'nonce' );
		$_posts = array();
		$args = array();
		if ( isset( $_POST['post_types'] ) ) {
			$post_type = explode( ',', $_POST['post_types'] );
			$args = array(
				'post_type' => $post_type,
				'order'     => 'ASC',
				'orderby'   => 'ID',
				'posts_per_page' => -1,
			);

			if ( isset( $_POST['click_count'] ) ) {
				$posts_per_page = get_option( 'posts_per_page' );
				$offset = $_POST['click_count'] * $posts_per_page;
				$args = array_merge(
					$args,
					array(
						'offset'         => $offset,
						'posts_per_page' => $posts_per_page,
					)
				);
			}

			if ( isset( $_POST['s'] ) ) {
				$args = array_merge(
					$args,
					array(
						's' => $_POST['s'],
					)
				);
			}
			$_posts = get_posts( $args );
		}
        if(is_array($_posts) && count($_posts)){
            foreach($_posts as $k => $_post){
                $_post_type_object          = get_post_type_object($_post->post_type);
                $_posts[$k]->post_type_name = $_post_type_object->labels->name;
            }
        }
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $_posts );
		die();
	}

	/**
	 * Getting the field
	 *
	 * @param int $index
	 * @param array $value
	 * @return string html
	 */
	public function get_field( $index, $value ) {
		$name      = $this->get_field_name_in_editor( $index );
		$disabled  = $this->get_disable_attribute( $index );
		$post_type = $this->get( 'post-type' );
		$limit     = $this->get( 'limit' );
		if ( ! $post_type ) {
			$post_type = array( 'post' );
		}
		if ( ! preg_match( '/^\d+$/', $limit ) ) {
			$limit = '';
		}
		$posts_per_page = get_option( 'posts_per_page' );

		// choicse
		$choices_posts = get_posts( array(
			'post_type'      => $post_type,
			'order'          => 'ASC',
			'orderby'        => 'ID',
			'posts_per_page' => $posts_per_page,
		) );
		$choices_li = array();
		foreach ( $choices_posts as $_post ) {
			$post_title = get_the_title( $_post->ID );
			if ( empty( $post_title ) ) {
				$post_title = '&nbsp;';
			}
			$choices_li[] = sprintf( '<li data-id="%d">%s</li>', $_post->ID, $post_title );
		}

		// selected
		$selected_posts = array();
		if ( !empty( $value ) && is_array( $value ) ) {
			foreach ( $value as $post_id ) {
				if ( get_post_status( $post_id ) !== 'publish' ) {
					continue;
				}
				$post_title = get_the_title( $post_id );
				if ( empty( $post_title ) ) {
					$post_title = '&nbsp;';
				}
				$selected_posts[$post_id] = $post_title;
			}
		}
		$selected_li = array();
		$hidden = array();
		foreach ( $selected_posts as $post_id => $post_title ) {
			$selected_li[] = sprintf(
				'<li data-id="%d"><span class="%s"></span>%s<span class="relation-remove">-</li></li>',
				$post_id,
				esc_attr( Theme_SCF_Config::PREFIX . 'icon-handle dashicons dashicons-menu' ),
				$post_title
			);
			$hidden[] = sprintf(
				'<input type="hidden" name="%s" value="%d" %s />',
				esc_attr( $name . '[]' ),
				$post_id,
				disabled( true, $disabled, false )
			);
		}

		$hide_class = '';
		if ( count( $choices_li ) < $posts_per_page ) {
			$hide_class = 'hide';
		}

		return sprintf(
			'<div class="%s" data-post-types="%s" data-limit="%d">
				<div class="%s">
					<input type="text" class="widefat search-input search-input-post-types" name="search-input" placeholder="%s" />
				</div>
				<div class="%s">
					<ul>%s</ul>
					<p class="load-relation-items load-relation-post-types %s">%s</p>
					<input type="hidden" name="%s" %s />
					%s
				</div>
			</div>
			<div class="%s"><ul>%s</ul></div>',
			Theme_SCF_Config::PREFIX . 'relation-left',
			implode( ',', $post_type ),
			$limit,
			Theme_SCF_Config::PREFIX . 'search',
			esc_attr__( 'Search...', 'smart-custom-fields' ),
			Theme_SCF_Config::PREFIX . 'relation-children-select',
			implode( '', $choices_li ),
			$hide_class,
			esc_html__( 'Load more', 'smart-custom-fields' ),
			esc_attr( $name ),
			disabled( true, $disabled, false ),
			implode( '', $hidden ),
			Theme_SCF_Config::PREFIX . 'relation-right',
			implode( '', $selected_li )
		);
	}
	public function get_option_field($name,$post_type,$limit,$value ) {
		//$name      = $this->get_field_name_in_editor( $index );
		$disabled  = false;
		//$post_type = $this->get( 'post-type' );
		//$limit     = $this->get( 'limit' );
		if ( ! $post_type ) {
			$post_type = array( 'post' );
		}
		if ( ! preg_match( '/^\d+$/', $limit ) ) {
			$limit = '';
		}
		$posts_per_page = get_option( 'posts_per_page' );

		// choicse
		$choices_posts = get_posts( array(
			'post_type'      => $post_type,
			'order'          => 'ASC',
			'orderby'        => 'ID',
			'posts_per_page' => $posts_per_page,
		) );
		$choices_li = array();
		foreach ( $choices_posts as $_post ) {
			$post_title = get_the_title( $_post->ID );
			if ( empty( $post_title ) ) {
				$post_title = '&nbsp;';
			}
            if(count($post_type) > 1){
                $_post_type_object = get_post_type_object($_post->post_type);
                $choices_li[] = sprintf( '<li data-id="%d">%s ( %s )</li>', $_post->ID, $post_title,$_post_type_object->labels->name );
            }else{
                $choices_li[] = sprintf( '<li data-id="%d">%s</li>', $_post->ID, $post_title );
            }
		}

		// selected
		$selected_posts = array();
		if ( !empty( $value ) && is_array( $value ) ) {
			foreach ( $value as $post_id ) {
				if ( get_post_status( $post_id ) !== 'publish' ) {
					continue;
				}
				$post_title = get_the_title( $post_id );
				if ( empty( $post_title ) ) {
					$post_title = '&nbsp;';
				}
				$selected_posts[$post_id] = $post_title;
			}
		}
		$selected_li = array();
		$hidden = array();
		foreach ( $selected_posts as $post_id => $post_title ) {
            if(count($post_type) > 1){
                $_post_type_object = get_post_type_object(get_post_type($post_id));
                $selected_li[] = sprintf(
                    '<li data-id="%d"><span class="%s"></span>%s ( %s )<span class="relation-remove">-</li></li>',
                    $post_id,
                    esc_attr( Theme_SCF_Config::PREFIX . 'icon-handle dashicons dashicons-menu' ),
                    $post_title,
                    $_post_type_object->labels->name
                );
            }else{
                $selected_li[] = sprintf(
                    '<li data-id="%d"><span class="%s"></span>%s<span class="relation-remove">-</li></li>',
                    $post_id,
                    esc_attr( Theme_SCF_Config::PREFIX . 'icon-handle dashicons dashicons-menu' ),
                    $post_title
                );
            }
			$hidden[] = sprintf(
				'<input type="hidden" name="%s" value="%d" %s />',
				esc_attr( $name . '[]' ),
				$post_id,
				disabled( true, $disabled, false )
			);
		}

		$hide_class = '';
		if ( count( $choices_li ) < $posts_per_page ) {
			$hide_class = 'hide';
		}

		return sprintf(
			'<div class="%s" data-post-types="%s" data-limit="%d">
				<div class="%s">
					<input type="text" class="widefat search-input search-input-post-types" name="search-input" placeholder="%s" />
				</div>
				<div class="%s">
					<ul>%s</ul>
					<p class="load-relation-items load-relation-post-types %s">%s</p>
					<input type="hidden" name="%s" %s />
					%s
				</div>
			</div>
			<div class="%s"><ul>%s</ul></div>',
			Theme_SCF_Config::PREFIX . 'relation-left',
			implode( ',', $post_type ),
			$limit,
			Theme_SCF_Config::PREFIX . 'search',
			esc_attr__( 'Search...', 'smart-custom-fields' ),
			Theme_SCF_Config::PREFIX . 'relation-children-select',
			implode( '', $choices_li ),
			$hide_class,
			esc_html__( 'Load more', 'smart-custom-fields' ),
			esc_attr( $name ),
			disabled( true, $disabled, false ),
			implode( '', $hidden ),
			Theme_SCF_Config::PREFIX . 'relation-right',
			implode( '', $selected_li )
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
			<th><?php esc_html_e( 'Post Types', 'smart-custom-fields' ); ?></th>
			<td>
				<?php
				$post_types = get_post_types( array(
					'show_ui' => true,
				), 'objects' );
				unset( $post_types['attachment'] );
				unset( $post_types[Theme_SCF_Config::NAME] );
				?>
				<?php foreach ( $post_types as $post_type => $post_type_object ) : ?>
				<?php
				$save_post_types = $this->get( 'post-type' );
				$checked = ( is_array( $save_post_types ) && in_array( $post_type, $save_post_types ) ) ? 'checked="checked"' : ''; ?>
				<input type="checkbox"
					name="<?php echo esc_attr( $this->get_field_name_in_setting( $group_key, $field_key, 'post-type' ) ); ?>[]"
					value="<?php echo esc_attr( $post_type ); ?>"
					 <?php echo $checked; ?> /><?php echo esc_html( $post_type_object->labels->singular_name ); ?>
				<?php endforeach; ?>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Selectable number', 'smart-custom-fields' ); ?></th>
			<td>
				<input type="number"
					name="<?php echo esc_attr( $this->get_field_name_in_setting( $group_key, $field_key, 'limit' ) ); ?>"
					value="<?php echo esc_attr( $this->get( 'limit' ) ); ?>" min="1" step="1" />
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
	 * @param array $value
	 * @param string $field_type
	 * @return array
	 */
	public function validate_get_value( $value, $field_type ) {
		if ( $field_type === $this->get_attribute( 'type' ) ) {
			$validated_value = array();
			foreach ( $value as $post_id ) {
				if ( get_post_status( $post_id ) !== 'publish' ) {
					continue;
				}
				$validated_value[] = $post_id;
			}
			$value = $validated_value;
		}
		return $value;
	}
}
