<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Content of the Edit Name Generator
 */

if ( ! isset( $_GET['gen_id'] ) || ! is_numeric( $_GET['gen_id'] ) )
	wp_die( __( 'Error.', 'ngjr3' ), __( 'Error', 'ngjr3' ) );


$gen_id = absint( $_GET['gen_id'] );
$gen    = gens_get_gen( $gen_id );
$meta   = get_post_meta( $gen_id, 'fields', true );
$fields = $meta['fields'];
?>

<div class="wrap">
	<div class="icon32" id="ngjr3-icon">
		<br />
	</div>
	<h2><?php _e( 'Edit Name Generator', 'ngjr3' ); ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo admin_url( 'tools.php?page=name-generator.php' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'ngjr3' ); ?></a></h2>
	<form id="ngjr3-edit-item" action="" method="post">
		<table class="form-table">
			<tbody>
				<tr class="form-field">
					<th scope="row" valign="top">
						<label for="name"><?php _e( 'Name', 'ngjr3' ); ?></label>
					</th>
					<td>
						<input name="name" id="name" type="text" value="<?php echo esc_attr( $gen->post_title ); ?>" style="width: 300px;"/>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
						<label><?php _e( 'List 1', 'ngjr3' ); ?></label>
					</th>
					<td>
						<textarea name="fields[01][array]" style="width: 300px;"><?php echo esc_html( $fields['01']['array'] ) ?></textarea>
						<br />
						<span class="description"><?php _e( 'Comma-separated list of items.', 'pijr3' ) ?></span>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
						<label><?php _e( 'List 2', 'ngjr3' ); ?></label>
					</th>
					<td>
						<textarea name="fields[02][array]" style="width: 300px;"><?php echo esc_html( $fields['02']['array'] ) ?></textarea>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
						<label><?php _e( 'List 3', 'ngjr3' ); ?></label>
					</th>
					<td>
						<textarea name="fields[03][array]" style="width: 300px;"><?php echo esc_html( $fields['03']['array'] ) ?></textarea>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
						<label><?php _e( 'List 4', 'ngjr3' ); ?></label>
					</th>
					<td>
						<textarea name="fields[04][array]" style="width: 300px;"><?php echo esc_html( $fields['04']['array'] ) ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="hidden" name="gen-action" value="edit_gen"/>
			<input type="hidden" name="gen_id" value="<?php echo $gen_id ?>"/>
			<input type="hidden" name="gen-redirect" value="<?php echo esc_url( admin_url( 'tools.php?page=name-generator.php' ) ); ?>"/>
			<input type="hidden" name="ngjr3-nonce" value="<?php echo wp_create_nonce( 'ngjr3_nonce' ); ?>"/>
			<input type="submit" value="<?php _e( 'Update Name Generator', 'ngjr3' ); ?>" class="button-primary"/>
		</p>
	</form>
</div>