<?php

class ITSEC_Notification_Center_Logs {

	public function __construct() {
		add_filter( 'itsec_logs_prepare_notification_center_entry_for_list_display', array( $this, 'filter_entry_for_list_display' ), 10, 3 );
		add_filter( 'itsec_logs_prepare_notification_center_entry_for_details_display', array( $this, 'filter_entry_for_details_display' ), 10, 4 );
		add_filter( 'itsec_highlighted_log_notification-center-send-failed_notice_title', array( $this, 'filter_highlight_title' ), 10, 2 );
		add_filter( 'itsec_highlighted_log_notification-center-send-failed_notice_message', array( $this, 'filter_highlight_message' ), 10, 2 );
	}

	public function filter_entry_for_list_display( $entry, $code, $data ) {

		$entry['module_display'] = esc_html__( 'Notification Center', 'better-wp-security' );

		switch ( $code ) {
			case 'send':
				list ( $notification ) = $data;

				if ( $strings = ITSEC_Core::get_notification_center()->get_notification_strings( $notification ) ) {
					$notification = $strings['label'];
				}

				$entry['description'] = sprintf( esc_html__( 'Sending %s', 'better-wp-security' ), $notification );
				break;
			case 'send_failed':
				list ( $notification ) = $data;

				if ( $strings = ITSEC_Core::get_notification_center()->get_notification_strings( $notification ) ) {
					$notification = $strings['label'];
				}

				$entry['description'] = sprintf( esc_html__( 'Sending %s Failed', 'better-wp-security' ), $notification );
				break;
			case 'send_scheduled':
				$entry['description'] = esc_html__( 'Sending scheduled notifications', 'better-wp-security' );
				break;
		}

		return $entry;
	}

	public function filter_entry_for_details_display( $details, $entry, $code, $code_data ) {

		$details['module']['content'] = esc_html__( 'Notification Center', 'better-wp-security' );

		switch ( $code ) {
			case 'send':
				list ( $notification ) = $code_data;

				if ( $strings = ITSEC_Core::get_notification_center()->get_notification_strings( $notification ) ) {
					$notification = $strings['label'];
				}

				$details['description']['content'] = esc_html__( 'Sending Notification', 'better-wp-security' );
				$details['notification']           = array(
					'header'  => esc_html__( 'Notification', 'better-wp-security' ),
					'content' => $notification,
					'order'   => 21,
				);
				break;
			case 'send_failed':
				list ( $notification ) = $code_data;

				if ( $strings = ITSEC_Core::get_notification_center()->get_notification_strings( $notification ) ) {
					$notification = $strings['label'];
				}

				$details['description']['content'] = esc_html__( 'Sending Notification Failed', 'better-wp-security' );
				$details['notification']           = array(
					'header'  => esc_html__( 'Notification', 'better-wp-security' ),
					'content' => $notification,
					'order'   => 21,
				);
				$details['error_message']          = array(
					'header'  => esc_html__( 'Error', 'better-wp-security' ),
					'content' => wp_sprintf( '%l', ITSEC_Response::get_error_strings( $entry['data']['error'] ) ),
					'order'   => 22,
				);
				break;
			case 'send_scheduled':
				$details['description']['content'] = esc_html__( 'Sending Scheduled Notification', 'better-wp-security' );
				$details['notifications']          = array(
					'header'  => esc_html__( 'Notifications', 'better-wp-security' ),
					'content' => wp_sprintf( '%l', $entry['data']['notifications'] ),
					'order'   => 21,
				);
				break;
				break;
		}

		return $details;
	}

	public function filter_highlight_title( $title, $entry ) {
		if ( false === strpos( $entry['code'], '::' ) ) {
			$data = array();
		} else {
			list( , $data ) = explode( '::', $entry['code'], 2 );
			$data = explode( ',', $data );
		}

		if ( empty( $data ) ) {
			return esc_html__( 'Failed sending notification.', 'better-wp-security' );
		}

		list( $notification ) = $data;

		if ( $strings = ITSEC_Core::get_notification_center()->get_notification_strings( $notification ) ) {
			$notification = $strings['label'];
		}

		return sprintf( esc_html__( 'Failed sending %s notification.', 'better-wp-security' ), $notification );
	}

	public function filter_highlight_message( $message, $entry ) {
		return wp_sprintf( '%l', ITSEC_Response::get_error_strings( $entry['data']['error'] ) );
	}
}

new ITSEC_Notification_Center_Logs();
