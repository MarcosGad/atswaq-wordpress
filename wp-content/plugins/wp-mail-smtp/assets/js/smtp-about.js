/* global WPMailSMTP, jQuery, wp_mail_smtp_about */

var WPMailSMTP = window.WPMailSMTP || {};
WPMailSMTP.Admin = WPMailSMTP.Admin || {};

/**
 * WP Mail SMTP Admin area About module.
 *
 * @since 1.5.0
 */
WPMailSMTP.Admin.About = WPMailSMTP.Admin.About || (function ( document, window, $ ) {

	'use strict';

	/**
	 * Private functions and properties.
	 *
	 * @since 1.5.0
	 *
	 * @type {Object}
	 */
	var __private = {};

	/**
	 * Public functions and properties.
	 *
	 * @since 1.5.0
	 *
	 * @type {Object}
	 */
	var app = {

		/**
		 * Start the engine. DOM is not ready yet, use only to init something.
		 *
		 * @since 1.5.0
		 */
		init: function () {

			// Do that when DOM is ready.
			$( document ).ready( app.ready );
		},

		/**
		 * DOM is fully loaded.
		 *
		 * @since 1.5.0
		 */
		ready: function () {

			app.pageHolder = $( '.wp-mail-smtp-page-about' );

			app.bindActions();

			$( '.wp-mail-smtp-page' ).trigger( 'WPMailSMTP.Admin.About.ready' );
		},

		/**
		 * Process all generic actions/events, mostly custom that were fired by our API.
		 *
		 * @since 1.5.0
		 */
		bindActions: function () {

			/*
			 * Make plugins description the same height.
			 */
			jQuery('.wp-mail-smtp-admin-about-plugins .plugin-item .details').matchHeight();

			/*
			 * Install/Active the plugins.
			 */
			$( document ).on( 'click', '.wp-mail-smtp-admin-about-plugins .plugin-item .action-button .button', function( e ) {
				e.preventDefault();

				var $btn = $( this );

				if ( $btn.hasClass( 'disabled' ) || $btn.hasClass( 'loading' ) ) {
					return false;
				}

				var $plugin = $btn.closest( '.plugin-item' ),
					plugin = $btn.attr( 'data-plugin' ),
					task,
					cssClass,
					statusText,
					buttonText,
					errorText,
					successText;

				$btn.prop( 'disabled', true ).addClass( 'loading' );
				$btn.text( wp_mail_smtp_about.plugin_processing );

				if ( $btn.hasClass( 'status-inactive' ) ) {
					// Activate.
					task       = 'about_plugin_activate';
					cssClass   = 'status-active button button-secondary disabled';
					statusText = wp_mail_smtp_about.plugin_active;
					buttonText = wp_mail_smtp_about.plugin_activated;
					errorText  = wp_mail_smtp_about.plugin_activate;

				} else if ( $btn.hasClass( 'status-download' ) ) {
					// Install & Activate.
					task       = 'about_plugin_install';
					cssClass   = 'status-active button disabled';
					statusText = wp_mail_smtp_about.plugin_active;
					buttonText = wp_mail_smtp_about.plugin_activated;
					errorText  = wp_mail_smtp_about.plugin_activate;

				} else {
					return;
				}

				// Setup ajax POST data.
				var data = {
					action: 'wp_mail_smtp_ajax',
					task: task,
					nonce : wp_mail_smtp_about.nonce,
					plugin: plugin
				};

				$.post( wp_mail_smtp_about.ajax_url, data, function( res ) {
					var is_install_successful;

					if ( res.success ) {
						is_install_successful = true;
						if ( 'about_plugin_install' === task ) {
							$btn.attr( 'data-plugin', res.data.basename );
							successText = res.data.msg;
							if ( ! res.data.is_activated ) {
								cssClass = 'button';
								statusText = wp_mail_smtp_about.plugin_inactive;
								buttonText = wp_mail_smtp_about.plugin_activate;
							}
						} else {
							successText = res.data;
						}
						$plugin.find( '.actions' ).append( '<div class="msg success">'+successText+'</div>' );
						$plugin.find( 'span.status-label' )
							  .removeClass( 'status-active status-inactive status-download' )
							  .addClass( cssClass )
							  .removeClass( 'button button-primary button-secondary disabled' )
							  .text( statusText );
						$btn
							.removeClass( 'status-active status-inactive status-download' )
							.removeClass( 'button button-primary button-secondary disabled' )
							.addClass( cssClass ).html( buttonText );
					} else {
						is_install_successful = false;

						if (
							res.hasOwnProperty( 'data' ) &&
							res.data.hasOwnProperty( 0 ) &&
							res.data[ 0 ].hasOwnProperty( 'code' ) &&
							res.data[ 0 ].code === 'download_failed'
						) {
							// Specific server-returned error.
							$plugin.find( '.actions' ).append( '<div class="msg error">' + wp_mail_smtp_about.plugin_install_error + '</div>' );
						}
						else {
							// Generic error.
							$plugin.find( '.actions' ).append( '<div class="msg error">' + res.data + '</div>' );
						}

						$btn.html( wp_mail_smtp_about.plugin_download_btn );
					}

					if ( is_install_successful ) {
						$btn.prop( 'disabled', false );
					}
					$btn.removeClass( 'loading' );

					// Automatically clear plugin messages after 3 seconds.
					setTimeout( function () {
						$( '.plugin-item .msg' ).remove();
					}, 3000 );

				}).fail( function( xhr ) {
					console.log( xhr.responseText );
				});
			});
		}
	};

	// Provide access to public functions/properties.
	return app;
})( document, window, jQuery );

// Initialize.
WPMailSMTP.Admin.About.init();
