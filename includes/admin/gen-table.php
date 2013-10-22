<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit();

/**
 * ngjr3 WP Actions Table
 */

// Load WP_List_Table if not loaded
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Name_Generator_Table extends WP_List_Table {

	/**
	 * Set up Table
	 */
	public function __construct() {

		parent::__construct( array(
			'singular'  => 'gen',
			'plural'    => 'gens',
			'ajax'      => false
		) );
	}


	/**
	 * Retrieve the table columns
	 *
	 * @return array $columns Array of all the list table columns
	 */
	public function get_columns() {
		$columns = array(
			'cb'             => '<input type="checkbox" />',
			'name'           => __( 'Name', 'ngjr3' ),
			'date_created'   => __( 'Date Created', 'ngjr3' ),
			'shortcode'      => __( 'Shortcode', 'ngjr3' ),

		);

		return $columns;
	}


	/**
	 * Retrieve the table's sortable columns
	 *
	 * @return array Array of all the sortable columns
	 */
	public function get_sortable_columns() {
		return array(
			'name'   => array( 'name', true ),
			'date_created'   => array( 'date', true ),
		);
	}


	/**
	 * This function renders most of the columns in the list table.
	 *
	 * @param array  $item Contains all the data of the reward
	 * @param string $column_name The name of the column
	 *
	 * @return string Column Name
	 */
	function column_default( $gen, $column_name ) {
		switch( $column_name ){
			default:
				return $gen[ $column_name ];
		}
	}


	/**
	 * Render the Name Column
	 *
	 * @param  array  $item  Contains all the data of the generator
	 * @return array  $item  Data shown in the Name column
	 */
	function column_name( $item ) {
		$row          = get_post( $item['ID'] );
		$base         = admin_url( 'admin.php?page=name_generator.php&gen_id=' . $item['ID'] );
		$row_actions  = array();
		$row_actions['edit']   = '<a href="' . add_query_arg( array( 'gen-action' => 'edit_gen', 'gen_id' => $row->ID ) ) . '">' . __( 'Edit', 'ngjr3' ) . '</a>';
		$row_actions['delete'] = '<a href="' . wp_nonce_url( add_query_arg( array( 'gen-action' => 'delete_action', 'gen_id' => $row->ID ) ), 'ngjr3_nonce' ) . '">' . __( 'Delete', 'ngjr3' ) . '</a>';
		return $item['name'] . $this->row_actions( $row_actions );
	}


	/**
	 * Render the checkbox column
	 *
	 * @param  array $item Contains all the data for the checkbox column
	 * @return string Displays a checkbox
	 */
	function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item['ID'] );
	}


	/**
	 * Retrieve the bulk actions
	 *
	 * @return array $actions Array of the bulk actions
	 */
	public function get_bulk_actions() {
		$actions = array(
			'delete' => __( 'Delete', 'ngjr3' )
		);

		return $actions;
	}


	/**
	 * Process the delete bulk action
	 *
	 * @return void
	 */
	public function process_bulk_action() {
		$ids = isset( $_GET['gen'] ) ? $_GET['gen'] : false;

		if ( ! is_array( $ids ) )
			$ids = array( $ids );

		foreach ( $ids as $id ) {
			if ( 'delete' === $this->current_action() ) {
				gens_remove_gen( $id );
			}
		}
	}


	/**
	 * Retrieve all the data
	 *
	 * @return array Array of all the data for the action 111s
	 */
	public function gens_table_data() {
		$gens_table_data = array();

		$orderby = isset( $_GET['orderby'] ) ? $_GET['orderby'] : 'ID';
		$order   = isset( $_GET['order'] )   ? $_GET['order']   : 'DESC';

		$args = array(
			'post_type'      => 'name_gen',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => $orderby,
			'order'          => $order,
			);
		$gens = get_posts( $args );
		if ( $gens ) {
			foreach ( $gens as $gen ) {
				$gens_table_data[] = array(
					'ID'           => $gen->ID,
					'name'         => get_the_title( $gen->ID ),
					'date_created' => get_the_time( 'd M Y H:i a', $gen->ID ),
					'shortcode'    => '[name_gen id=' . $gen->ID . ']'
				);
			}
		}

		return $gens_table_data;
	}


	/**
	 * Render Table
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->process_bulk_action();
		$data                  = $this->gens_table_data();
		$this->items           = $data;
	}

}
