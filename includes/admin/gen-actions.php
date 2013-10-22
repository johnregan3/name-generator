<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Fetches all Generator Actions
 */
function gens_get_gen_actions() {
	if ( isset( $_GET['gen-action'] ) ) {
		do_action( 'gens_' . $_GET['gen-action'], $_GET );
		}
	}
add_action( 'init', 'gens_get_gen_actions' );


/**
 * Checks for POST/GET information
 */
function gen_process_actions() {
	if ( isset( $_POST['gen-action'] ) ) {
		do_action( 'gens_' . $_POST['gen-action'], $_POST );
	}

	if ( isset( $_GET['gen-action'] ) ) {
		do_action( 'gens_' . $_GET['gen-action'], $_GET );
	}
}
add_action( 'admin_init', 'gen_process_actions' );


/**
 * Fetches array of new Generator information, then sends it to be saved.
 *
 * @param  array  $data  Data of gen to be added
 */
function gens_add_gen( $data ) {
	if ( isset( $data['ngjr3-nonce'] ) && wp_verify_nonce( $data['ngjr3-nonce'], 'ngjr3_nonce' ) ) {

		// Setup the action code details
		$posted = array();

		foreach ( $data as $key => $value ) {
			if ( $key != 'ngjr3-nonce' && $key != 'gen-action' && $key != 'gen-redirect' ) {
				// Need to Sanitize
				$posted[$key] = $value;
			}
		}

		if ( gens_store_gen( $posted ) ) {
			wp_redirect( add_query_arg( 'gen-message', 'gen_added', $data['gen-redirect'] ) ); die();
		} else {
			wp_redirect( add_query_arg( 'gen-message', 'gen_add_failed', $data['gen-redirect'] ) ); die();
		}
	}
}
add_action( 'gens_add_gen', 'gens_add_gen' );


/**
 * Fetches array of new gen information, then saves it and redirects.
 *
 * @param  array  $details  Data of gen to be added
 * @param  int    $gen_id  gen for which to store the data.
 */
function gens_store_gen( $details, $gen_id = null ) {

	if ( gens_gen_exists( $gen_id ) && ! empty( $gen_id ) ) {
		// Update an existing gen
		wp_update_post( array(
			'ID'          => $gen_id,
			'post_title'  => $details['name'],
		) );

		update_post_meta( $gen_id, 'fields', $details );

		return true;

	} else {

		$gen_id = wp_insert_post( array(
			'post_type'   => 'name_gen',
			'post_title'  => isset( $details['name'] ) ? $details['name'] : '',
			'post_status' => 'publish',
		) );

		update_post_meta( $gen_id, 'fields', $details );

		return true;
	}
}


/**
 * Fetches array of new gen information, then sends it to be saved.
 *
 * @param  array  $data  Data of gen to be added
 */
function gens_edit_gen( $data ) {
	if ( isset( $data['ngjr3-nonce'] ) && wp_verify_nonce( $data['ngjr3-nonce'], 'ngjr3_nonce' ) ) {
		$gen = array();
		foreach ( $data as $key => $value ) {
			if ( $key != 'ngjr3-nonce' && $key != 'gen-action' && $key != 'gen_id' && $key != 'gen-redirect' ) {
					// Need to Sanitize
					$gen[ $key ] = $value;
			}
		}

		if ( gens_store_gen( $gen, $data['gen_id'] ) ) {
			wp_redirect( add_query_arg( 'gen-message', 'gen_updated', $data['gen-redirect'] ) ); die();
		} else {
			wp_redirect( add_query_arg( 'gen-message', 'gen_update_failed', $data['gen-redirect'] ) ); die();
		}
	}
}
add_action( 'gens_edit_gen', 'gens_edit_gen' );


/**
 * Checks to see if gen exists
 *
 * @param  int  $gen_id  gen for which to store the data.
 * @return bool
 */
function gens_gen_exists( $gen_id ) {
	if ( gens_get_gen( $gen_id ) )
		return true;

	return false;
}


/**
 * Checks to see if gen exists
 *
 * @param  int    $gen_id  gen for which to store the data.
 * @return object $gen     Post object for requested gen ID.
 */
function gens_get_gen( $gen_id ) {
	$gen = get_post( $gen_id );
	if ( isset( $gen->ID ) && ( get_post_type( $gen->ID ) != 'name_gen' ) )
		return false;

	return $gen;
}


/**
 * Listens for when a delete link is clicked and deletes the gen
 *
 * @param  array  $data
 */
function gens_delete_action( $data ) {
	if ( ! isset( $data['_wpnonce'] ) || ! wp_verify_nonce( $data['_wpnonce'], 'ngjr3_nonce' ) )
		wp_die( __( 'Failed nonce verification', 'ngjr3' ), __( 'Error', 'ngjr3' ) );

	$gen_id = $data['gen_id'];
	wp_delete_post( $gen_id, true );
}
add_action( 'gens_delete_action', 'gens_delete_action' );


/**
 * Deletes a gen
 *
 * @param int $gen_id gen ID
 */
function gens_remove_gen( $gen_id = 0 ) {
	wp_delete_post( $gen_id, true );
	delete_post_meta( $gen_id, 'fields' );
}
