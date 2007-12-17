<?php /*

**************************************************************************

Plugin Name:  Global Plugin Update Notice
Plugin URI:   http://www.viper007bond.com/wordpress-plugins/global-plugin-update-notice/
Version:      1.0.1
Description:  When a new version of an activated plugin is available for download from WordPress.org, you will recieve a notice on all admin pages. No more having to check the plugins page!
Author:       Viper007Bond
Author URI:   http://www.viper007bond.com/

**************************************************************************/

class GlobalPluginUpdateNotice {

	// Plugin initialization
	function GlobalPluginUpdateNotice() {
		if ( !is_admin() || !current_user_can('activate_plugins') ) return;

		add_action( 'admin_head', array(&$this, 'CheckPlugins') ); // admin_head runs at just the right time (init is too early)
		add_action( 'admin_notices', array(&$this, 'MaybeDisplayUpdateMessage'), 5 );
	}


	// Make wp_update_plugins() run on all admin pages
	function CheckPlugins() {
		// Make sure this version of WordPress is new enough to work with this plugin.
		if ( !function_exists('wp_update_plugins') ) {
			add_action( 'admin_notices', array(&$this, 'WordPressTooOld') );
			return;
		}

		// Call the update checker
		wp_update_plugins();
	}


	// Displays a message that this version of WordPress is too old
	function WordPressTooOld() {
		echo "	<div class='updated'><p>Your version of WordPress is too old for <strong>Global Plugin Update Notice</strong> to work. <a href='http://wordpress.org/download/'>Please update!</a></p></div>\n";
	}


	// Displays a message that plugin updates are available if they are
	function MaybeDisplayUpdateMessage() {
		$current = get_option( 'update_plugins' );

		if ( empty( $current->response ) ) return; // No plugin updates available

		// Since the message can get spammy, only display activated plugins
		$active_plugins = get_option('active_plugins');
		if ( empty($active_plugins) || !is_array($active_plugins) ) return;

		$plugins = get_plugins();

		$updatelist = array();

		foreach ( $current->response as $plugin_file => $update_data ) {
			// Make sure the plugin data is known and that it's activated
			if ( empty( $plugins[$plugin_file] ) || !in_array( $plugin_file, $active_plugins ) ) continue;

			// Make syre there is something to display
			if ( empty($plugins[$plugin_file]['Name']) ) $plugins[$plugin_file]['Name'] = $plugin_file;

			$updatelist[] = array(
				'name' => $plugins[$plugin_file]['Name'],
				'url' => $update_data->url,
				'version' => $update_data->new_version,
				'file' => $plugin_file,
			);
		}

		if ( empty($updatelist) ) return; // There are plugins that need updating, but none of them are activated


		echo '	<div class="plugin-update">';

		if ( 1 == count($updatelist) ) {
			printf( __('There is a new version of %s available. <a href="%s">Download version %s here</a>.'), $updatelist[0]['name'], $updatelist[0]['url'], $updatelist[0]['version'] );
		} else {
			foreach ( $updatelist as $item ) {
				$temp[] = '<a href="' . $item['url'] . '">' . $item['name'] . ' ' . $item['version'] . '</a>';
			}
			printf( 'There are new versions available of the following plugins: %s', implode( ', ', $temp ) );
		}

		echo "</div>\n";
	}
}

// Start this plugin once all other files and plugins are fully loaded
add_action( 'plugins_loaded', create_function( '', 'global $GlobalPluginUpdateNotice; $GlobalPluginUpdateNotice = new GlobalPluginUpdateNotice();' ) );

?>