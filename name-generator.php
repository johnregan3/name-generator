<?php

/**
 * Plugin Name: Name Generator
 * Plugin URI:  http://johnregan3.github.io/name-generator/
 * Description: Create fun random Name Generators for your WordPress website.
 * Author:      John Regan (johnregan3)
 * Author URI:  http://johnregan3.me
 * Version:     1.0
 * Text Domain: admin-search
 * License:     GPLv2+
 *
 * Copyright 2013  John Regan  (email : johnregan3@outlook.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @author John Regan
 * @version 1.0
 */


// Shortcode
include_once( plugin_dir_path(__FILE__) . 'includes/shortcode.php' );

// Table View
include_once( plugin_dir_path(__FILE__) . 'includes/admin/gen-table.php' );

// Process Add/Edit pages
include_once( plugin_dir_path(__FILE__) . 'includes/admin/gen-actions.php' );


class Name_Generator_Plugin {

	static function setup() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'scripts' ) );
		add_action( 'wp_ajax_generate_name', array( __CLASS__, 'generate_name' ) );
		add_action( 'wp_ajax_nopriv_generate_name', array( __CLASS__, 'generate_name' ) );
		add_action( 'admin_menu', array( __CLASS__, 'register_submenu_page' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	/**
	 * Cleanup on unistnall
	 */
	static function uninstall() {
		unregister_setting( 'ngjr3_settings_group', 'ngjr3_settings' );
		$posts = get_posts( array( 'post_type' => 'name_gen', 'posts_per_page' => -1 ) );
		foreach ( $posts as $post ) {
			wp_delete_post( $post->ID, true );
		}
	}

	/**
	 * Enqueue Front End Styles and Scripts
	 */
	static function scripts( ) {
		wp_register_script(
			'name-gen-script',
			plugins_url( 'includes/js/scripts.js', __FILE__),
			array( 'jquery' ),
			1.0,
			true
		);

		wp_localize_script(
			'name-gen-script',
			'gen_ajax',
			array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
		);

		wp_enqueue_script( 'name-gen-script' );
	}

	/**
	 * Set up Name Generator Page
	 */
	static function register_submenu_page() {
		add_submenu_page(
			'tools.php',
			__( 'Name Generators', 'ngjr3' ),
			__( 'Name Generators', 'ngjr3' ),
			'manage_options',
			basename( __FILE__ ),
			array( __CLASS__, 'admin_page' )
		);
	}

	/**
	 * Register settings
	 *
	 * Used for saving Default CSS rule.
	 */
	static function register_settings() {
		register_setting('ngjr3_settings_group', 'ngjr3_settings');

		add_settings_section(
			'ngjr3_settings_section',
			__( 'Name Generator Settings', 'ngjr3' ),
			'',
			__FILE__
		);

		add_settings_field(
			'ngjr3_btn_text',
			__( 'Button Text', 'ngjr3' ),
			array( __CLASS__, 'button_text_field' ),
			__FILE__,
			'ngjr3_settings_section'
		);

		add_settings_field(
			'ngjr3_css',
			__( 'Use Default Plugin CSS', 'ngjr3' ),
			array( __CLASS__, 'css_field' ),
			__FILE__,
			'ngjr3_settings_section'
		);
	}

	/**
	 * Render Button Text Input Field
	 */
	static function button_text_field() {
		$options = get_option( 'ngjr3_settings' );
		$button_text = isset( $options['button-text'] ) ? $options['button-text'] : 'Generate Name' ;
		echo '<input name="ngjr3_settings[button-text]" type="text" value="' . esc_attr( $button_text ) . '" />';
	}

	/**
	 * Render 'Use Default Plugin CSS' Input Field
	 */
	static function css_field() {
		$options = get_option( 'ngjr3_settings' );
		$css = isset( $options['css'] ) ? 1 : 0 ;
		echo '<input name="ngjr3_settings[css]" type="checkbox" value="1" ' . checked( 1, $css, false ) . '/>';
	}

	/**
	 * Render Name Generator Menu Page
	 *
	 * Detects which page (Edit/Add) is requested, then returns the view.
	 */
	static function admin_page(){

		if ( isset( $_GET['gen-action'] ) && $_GET['gen-action'] == 'edit_gen' ) {
			require_once plugin_dir_path(__FILE__) . 'includes/admin/edit-gen.php';
		} elseif ( isset( $_GET['gen-action'] ) && $_GET['gen-action'] == 'add_gen' ) {
			require_once plugin_dir_path(__FILE__) . 'includes/admin/add-gen.php';
		} else {
		?>

			<div class="wrap">
				<div class="icon32" id="ngjr3-icon">
					<br />
				</div>
				<h2><?php _e( 'Name Generators', 'ngjr3' ); ?><a href="<?php echo add_query_arg( array( 'gen-action' => 'add_gen' ) ); ?>" class="add-new-h2">Add New</a></h2>

				<?php if ( isset( $_GET['gen-message'] ) ) : ?>
					<?php if ( $_GET['gen-message'] == 'gen_updated' ) : ?>
						<div class="updated">
							<p><?php _e( 'Name Generator Updated', 'ngjr3' ); ?></p>
						</div>

					<?php elseif ( $_GET['gen-message'] == 'gen_added' ) : ?>
						<div class="updated">
							<p><?php _e( 'Name Generator Added', 'ngjr3' ); ?></p>
						</div>

					<?php elseif ( $_GET['gen-message'] == 'gen_update_failed' ) : ?>
						<div class="error">
							<p><?php _e( 'Name Generator Update Failed', 'ngjr3' ); ?></p>
						</div>

					<?php elseif ( $_GET['gen-message'] == 'gen_add_failed' ) : ?>
						<div class="error">
							<p><?php _e( 'Name Generator Add Failed', 'ngjr3' ); ?></p>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<?php
				$gen_table = new Name_Generator_Table();
				$gen_table->prepare_items();
				?>

				<form id="gen-filter" method="get" action="<?php echo admin_url( 'admin.php?page=name-generator.php' ); ?>">
					<input type="hidden" name="page" value="name-generator.php" />
					<?php $gen_table->display() ?>
				</form>

				<?php // Place Settings below Generators since the settings are rarely changed. ?>
				<form name="ngjr3-css-form" action="options.php" method="post" enctype="multipart/form-data">
					<?php settings_fields('ngjr3_settings_group'); ?>
					<?php do_settings_sections( __FILE__ ); ?>
					<?php submit_button( __( 'Update Settings', 'ngjr3' ), 'primary', 'submit', true ); ?>
				</form>

			</div>

			<?php
		}
	}

	/**
	 * Generate new Name and send back to Ajax.
	 */
	static function generate_name() {
		$gen_id   = isset( $_POST['gen_id'] ) ? $_POST['gen_id'] : '';
		$meta     = get_post_meta( $gen_id, 'fields', true );
		$fields   = $meta['fields'];
		$response = '';

		for ( $i = 1; $i <= 4; $i++ ) {
			$string = $fields['0' . $i]['array'];
			if ( ! empty( $string ) ) {
				$array = explode( ', ', $string );
				if ( is_array( $array ) ) {
					$rand = array_rand( $array, 1 );
					$response .= '<span class="name-gen-field-' . $i . '" >' . $array[$rand] . '</span> ';
				}
			}
		}
		wp_send_json( $response );
	}

}
add_action( 'plugins_loaded', array( 'Name_Generator_Plugin', 'setup' ) );
register_uninstall_hook( __FILE__, array( 'Name_Generator_Plugin' , 'uninstall' ) );
