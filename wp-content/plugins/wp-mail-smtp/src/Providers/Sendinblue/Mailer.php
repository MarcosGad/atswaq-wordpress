<?php

namespace WPMailSMTP\Providers\Sendinblue;

use WPMailSMTP\Debug;
use WPMailSMTP\Providers\MailerAbstract;
use WPMailSMTP\WP;

/**
 * Class Mailer.
 *
 * @since 1.6.0
 */
class Mailer extends MailerAbstract {

	/**
	 * Which response code from HTTP provider is considered to be successful?
	 *
	 * @since 1.6.0
	 *
	 * @var int
	 */
	protected $email_sent_code = 201;

	/**
	 * URL to make an API request to.
	 * Not actually used, because we use a lib to make requests.
	 *
	 * @since 1.6.0
	 *
	 * @var string
	 */
	protected $url = 'https://api.sendinblue.com/v3';

	/**
	 * The list of allowed attachment files extensions.
	 *
	 * @see   https://developers.sendinblue.com/reference#sendTransacEmail_attachment__title
	 *
	 * @since 1.6.0
	 *
	 * @var array
	 */
	// @formatter:off
	protected $allowed_attach_ext = array( 'xlsx', 'xls', 'ods', 'docx', 'docm', 'doc', 'csv', 'pdf', 'txt', 'gif', 'jpg', 'jpeg', 'png', 'tif', 'tiff', 'rtf', 'bmp', 'cgm', 'css', 'shtml', 'html', 'htm', 'zip', 'xml', 'ppt', 'pptx', 'tar', 'ez', 'ics', 'mobi', 'msg', 'pub', 'eps', 'odt', 'mp3', 'm4a', 'm4v', 'wma', 'ogg', 'flac', 'wav', 'aif', 'aifc', 'aiff', 'mp4', 'mov', 'avi', 'mkv', 'mpeg', 'mpg', 'wmv' );
	// @formatter:on

	/**
	 * Mailer constructor.
	 *
	 * @since 1.6.0
	 *
	 * @param \WPMailSMTP\MailCatcher $phpmailer
	 */
	public function __construct( $phpmailer ) {

		parent::__construct( $phpmailer );

		if ( ! $this->is_php_compatible() ) {
			return;
		}
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.6.0
	 */
	public function set_header( $name, $value ) {

		$name = sanitize_text_field( $name );

		$this->body['headers'][ $name ] = WP::sanitize_value( $value );
	}

	/**
	 * Set the From information for an email.
	 *
	 * @since 1.6.0
	 *
	 * @param string $email
	 * @param string $name
	 */
	public function set_from( $email, $name ) {

		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return;
		}

		$this->body['sender'] = array(
			'email' => $email,
			'name'  => ! empty( $name ) ? WP::sanitize_value( $name ) : '',
		);
	}

	/**
	 * Set email recipients: to, cc, bcc.
	 *
	 * @since 1.6.0
	 *
	 * @param array $recipients
	 */
	public function set_recipients( $recipients ) {

		if ( empty( $recipients ) ) {
			return;
		}

		// Allow for now only these recipient types.
		$default = array( 'to', 'cc', 'bcc' );
		$data    = array();

		foreach ( $recipients as $type => $emails ) {

			if (
				! in_array( $type, $default, true ) ||
				empty( $emails ) ||
				! is_array( $emails )
			) {
				continue;
			}

			$data[ $type ] = array();

			// Iterate over all emails for each type.
			// There might be multiple cc/to/bcc emails.
			foreach ( $emails as $email ) {
				$holder = array();
				$addr   = isset( $email[0] ) ? $email[0] : false;
				$name   = isset( $email[1] ) ? $email[1] : false;

				if ( ! filter_var( $addr, FILTER_VALIDATE_EMAIL ) ) {
					continue;
				}

				$holder['email'] = $addr;
				if ( ! empty( $name ) ) {
					$holder['name'] = $name;
				}

				array_push( $data[ $type ], $holder );
			}
		}

		foreach ( $data as $type => $type_recipients ) {
			$this->body[ $type ] = $type_recipients;
		}
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.6.0
	 */
	public function set_subject( $subject ) {

		$this->body['subject'] = $subject;
	}

	/**
	 * Set email content.
	 *
	 * @since 1.6.0
	 *
	 * @param string|array $content
	 */
	public function set_content( $content ) {

		if ( empty( $content ) ) {
			return;
		}

		if ( is_array( $content ) ) {

			if ( ! empty( $content['text'] ) ) {
				$this->body['textContent'] = $content['text'];
			}

			if ( ! empty( $content['html'] ) ) {
				$this->body['htmlContent'] = $content['html'];
			}
		} else {
			if ( $this->phpmailer->ContentType === 'text/plain' ) {
				$this->body['textContent'] = $content;
			} else {
				$this->body['htmlContent'] = $content;
			}
		}
	}

	/**
	 * Doesn't support this.
	 *
	 * @since 1.6.0
	 *
	 * @param string $email
	 */
	public function set_return_path( $email ) {

	}

	/**
	 * Set the Reply To headers if not set already.
	 *
	 * @since 1.6.0
	 *
	 * @param array $emails
	 */
	public function set_reply_to( $emails ) {

		if ( empty( $emails ) ) {
			return;
		}

		$data = array();

		foreach ( $emails as $user ) {
			$holder = array();
			$addr   = isset( $user[0] ) ? $user[0] : false;
			$name   = isset( $user[1] ) ? $user[1] : false;

			if ( ! filter_var( $addr, FILTER_VALIDATE_EMAIL ) ) {
				continue;
			}

			$holder['email'] = $addr;
			if ( ! empty( $name ) ) {
				$holder['name'] = $name;
			}

			$data[] = $holder;
		}

		if ( ! empty( $data ) ) {
			$this->body['replyTo'] = $data[0];
		}
	}

	/**
	 * Set attachments for an email.
	 *
	 * @since 1.6.0
	 *
	 * @param array $attachments
	 */
	public function set_attachments( $attachments ) {

		if ( empty( $attachments ) ) {
			return;
		}

		foreach ( $attachments as $attachment ) {
			$file = false;

			/*
			 * We are not using WP_Filesystem API as we can't reliably work with it.
			 * It is not always available, same as credentials for FTP.
			 */
			try {
				if ( is_file( $attachment[0] ) && is_readable( $attachment[0] ) ) {
					$ext = pathinfo( $attachment[0], PATHINFO_EXTENSION );

					if ( in_array( $ext, $this->allowed_attach_ext, true ) ) {
						$file = file_get_contents( $attachment[0] ); // phpcs:ignore
					}
				}
			}
			catch ( \Exception $e ) {
				$file = false;
			}

			if ( $file === false ) {
				continue;
			}

			$this->body['attachment'][] = array(
				'name'    => $attachment[2],
				'content' => base64_encode( $file ),
			);
		}
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.6.0
	 *
	 * @return \SendinBlue\Client\Model\SendSmtpEmail
	 */
	public function get_body() {

		return new \SendinBlue\Client\Model\SendSmtpEmail( $this->body );
	}

	/**
	 * Use a library to send emails.
	 *
	 * @since 1.6.0
	 */
	public function send() {

		try {
			$api = new Api();

			$response = $api->get_smtp_client()->sendTransacEmail( $this->get_body() );

			$this->process_response( $response );
		}
		catch ( \SendinBlue\Client\ApiException $e ) {
			$error = json_decode( $e->getResponseBody() );
			if ( json_last_error() === JSON_ERROR_NONE ) {
				Debug::set(
					'Mailer: Sendinblue' . "\r\n" .
					'[' . sanitize_key( $error->code ) . ']: ' . esc_html( $error->message )
				);
			}
		}
		catch ( \Exception $e ) {
			Debug::set(
				'Mailer: Sendinblue' . "\r\n" .
				$e->getMessage()
			);

			return;
		}
	}

	/**
	 * Save response from the API to use it later.
	 * All the actually response processing is done in send() method,
	 * because SendinBlue throws exception if any error occurs.
	 *
	 * @since 1.6.0
	 *
	 * @param \SendinBlue\Client\Model\CreateSmtpEmail $response
	 */
	protected function process_response( $response ) {

		$this->response = $response;
	}

	/**
	 * Check whether the email was sent.
	 *
	 * @since 1.6.0
	 *
	 * @return bool
	 */
	public function is_email_sent() {

		$is_sent = false;

		if ( $this->response instanceof \SendinBlue\Client\Model\CreateSmtpEmail ) {
			$is_sent = $this->response->valid();
		}

		// Clear debug messages if email is successfully sent.
		if ( $is_sent ) {
			Debug::clear();
		}

		return $is_sent;
	}

	/**
	 * @inheritdoc
	 *
	 * @since 1.6.0
	 */
	public function get_debug_info() {

		$mailjet_text[] = '<strong>API Key:</strong> ' . ( $this->is_mailer_complete() ? 'Yes' : 'No' );

		return implode( '<br>', $mailjet_text );
	}

	/**
	 * @inheritdoc
	 *
	 * @since 1.6.0
	 */
	public function is_mailer_complete() {

		$options = $this->options->get_group( $this->mailer );

		// API key is the only required option.
		if ( ! empty( $options['api_key'] ) ) {
			return true;
		}

		return false;
	}
}
