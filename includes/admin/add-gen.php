<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit();

/**
 * Content of the Add Name Generator Page
 */

?>

<div class="wrap">
	<div class="icon32" id="ngjr3-icon">
		<br />
	</div>
	<h2><?php _e( 'Add New Name Generator', 'ngjr3' ); ?>&nbsp;&nbsp;&nbsp;<a href="<?php echo admin_url( 'tools.php?page=name-generator.php' ); ?>" class="button-secondary"><?php _e( 'Go Back', 'ngjr3' ); ?></a></h2>
	<form id="ngjr3-add-gen" action="" method="POST">
		<table class="form-table">
			<tbody>
				<tr class="form-field">
					<th scope="row" valign="top">
						<label for="name"><?php _e( 'Generator Title', 'ngjr3' ); ?></label>
					</th>
					<td>
						<input name="name" id="name" type="text" value="" placeholder="Generator Title" style="width: 300px;"/>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
						<label><?php _e( 'List 1', 'ngjr3' ); ?></label>
					</th>
					<td>
						<textarea name="fields[01][array]" style="width: 300px;" placeholder="item 1, item 2, item 3" ></textarea>
						<br />
						<span class="description"><?php _e( 'Comma-separated list of items.', 'pijr3' ) ?></span>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
						<label><?php _e( 'List 2', 'ngjr3' ); ?></label>
					</th>
					<td>
						<textarea name="fields[02][array]" style="width: 300px;"></textarea>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
						<label><?php _e( 'List 3', 'ngjr3' ); ?></label>
					</th>
					<td>
						<textarea name="fields[03][array]" style="width: 300px;"></textarea>
					</td>
				</tr>
				<tr>
					<th scope="row" valign="top">
						<label><?php _e( 'List 4', 'ngjr3' ); ?></label>
					</th>
					<td>
						<textarea name="fields[04][array]" style="width: 300px;"></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="hidden" name="gen-action" value="add_gen"/>
			<input type="hidden" name="gen-redirect" value="<?php echo esc_url( admin_url( 'tools.php?page=name-generator.php' ) ); ?>"/>
			<input type="hidden" name="ngjr3-nonce" value="<?php echo wp_create_nonce( 'ngjr3_nonce' ); ?>"/>
			<input type="submit" value="<?php _e( 'Add New Name Generator', 'ngjr3' ); ?>" class="button-primary"/>
		</p>
	</form>
</div>
