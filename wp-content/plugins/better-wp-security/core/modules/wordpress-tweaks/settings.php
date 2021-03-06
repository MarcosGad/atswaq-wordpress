<?php

final class ITSEC_Wordpress_Tweaks_Settings extends ITSEC_Settings {
	public function get_id() {
		return 'wordpress-tweaks';
	}

	public function get_defaults() {
		return array(
			'wlwmanifest_header'          => false,
			'edituri_header'              => false,
			'comment_spam'                => false,
			'file_editor'                 => true,
			'disable_xmlrpc'              => 0,
			'allow_xmlrpc_multiauth'      => false,
			'rest_api'                    => 'default-access',
			'login_errors'                => false,
			'force_unique_nicename'       => false,
			'disable_unused_author_pages' => false,
			'block_tabnapping'            => false,
			'valid_user_login_type'       => 'both',
			'patch_thumb_file_traversal'  => true,
		);
	}
}

ITSEC_Modules::register_settings( new ITSEC_WordPress_Tweaks_Settings() );
