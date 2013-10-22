<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Name Generator Shortcode
 *
 * @since 1.0
 */

function ngjr3_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array( 'id' => '' ), $atts ) );

	$options = get_option( 'ngjr3_settings' );
	$button_text = isset( $options['button-text'] ) ? $options['button-text'] : 'Generate Name' ;
	$css = isset( $options['css'] ) ? 1 : 0 ;
	if ( $css == 1 ) : ?>
		<style type="text/css">
			.name-gen-container { text-align: center;}
			.name-gen-result { font-size: 3em; margin-top: 30px; }
		</style>
	<?php endif; ?>
	<div id="name-gen" class="name-gen-container">
		<div id="name-gen-button-wrap">
			<input type="button" id="name-gen-button" value="<?php echo esc_attr( $button_text ) ?>" data-gen="<?php echo $id ?>" />
		</div>
		<div id="name-gen-result" class="name-gen-result" ></div>
	</div>
	<?php
}
add_shortcode('name_gen', 'ngjr3_shortcode' );
