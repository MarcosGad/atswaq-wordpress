<?php

/**
 * Mailchimp: Get Mailchimp Status.
 * API to determine if current site has linked Mailchimp account and mailing list selected.
 * This API is meant to be used in Jetpack and on WPCOM.
 *
 * @since 7.1
 */
class WPCOM_REST_API_V2_Endpoint_Mailchimp extends WP_REST_Controller {
	public function __construct() {
		$this->namespace                    = 'wpcom/v2';
		$this->rest_base                    = 'mailchimp';
		$this->wpcom_is_wpcom_only_endpoint = true;

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Called automatically on `rest_api_init()`.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			$this->rest_base,
			array(
				array(
					'methods'  => WP_REST_Server::READABLE,
					'callback' => array( $this, 'get_mailchimp_status' ),
				),
			)
		);
	}

	/**
	 * Check if MailChimp is set up properly.
	 *
	 * @return bool
	 */
	private function is_connected() {
		$option = get_option( 'jetpack_mailchimp' );
		if ( ! $option ) {
			return false;
		}
		$data = json_decode( $option, true );
		if ( ! $data ) {
			return false;
		}
		return isset( $data['follower_list_id'], $data['keyring_id'] );
	}

	/**
	 * Get the status of current blog's Mailchimp connection
	 *
	 * @return mixed
	 * code:string (connected|unconnected),
	 * connect_url:string
	 * site_id:int
	 */
	public function get_mailchimp_status() {
		$is_wpcom = ( defined( 'IS_WPCOM' ) && IS_WPCOM );
		$site_id  = $is_wpcom ? get_current_blog_id() : Jetpack_Options::get_option( 'id' );
		if ( ! $site_id ) {
			return new WP_Error(
				'unavailable_site_id',
				__( 'Sorry, something is wrong with your Jetpack connection.', 'jetpack' ),
				403
			);
		}
		$connect_url = sprintf( 'https://wordpress.com/marketing/connections/%s', rawurlencode( $site_id ) );
		return array(
			'code'        => $this->is_connected() ? 'connected' : 'not_connected',
			'connect_url' => $connect_url,
			'site_id'     => $site_id,
		);
	}
}

wpcom_rest_api_v2_load_plugin( 'WPCOM_REST_API_V2_Endpoint_Mailchimp' );
