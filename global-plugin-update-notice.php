<?php /*

**************************************************************************

Plugin Name:  Global Plugin Update Notice
Plugin URI:   http://www.viper007bond.com/wordpress-plugins/global-plugin-update-notice/
Version:      1.1.1
Description:  When a new version of an activated plugin is available for download from WordPress.org, you will recieve a notice on all admin pages. No more having to check the plugins page!
Author:       Viper007Bond
Author URI:   http://www.viper007bond.com/

**************************************************************************/

class GlobalPluginUpdateNotice {

	// Plugin initialization
	function GlobalPluginUpdateNotice() {
		if ( !is_admin() || !current_user_can('activate_plugins') || 'update.php' == basename($_SERVER['REQUEST_URI']) ) return;

		add_action( 'admin_head', array(&$this, 'CheckPlugins') ); // admin_head runs at just the right time (init is too early)
		add_action( 'admin_notices', array(&$this, 'MaybeDisplayUpdateMessage'), 5 );
	}


	// Make wp_update_plugins() run on all admin pages
	function CheckPlugins() {
		// Make sure this version of WordPress is new enough to work with this plugin.
		if ( !file_exists( ABSPATH . 'wp-admin/update.php' ) ) {
			add_action( 'admin_notices', array(&$this, 'WordPressTooOld') );
			return;
		}

		// Call the update checker
		wp_update_plugins();
	}


	// Displays a message that this version of WordPress is too old
	function WordPressTooOld() {
		echo "	<div class='plugin-update'>Your version of WordPress is too old for this version of <strong>Global Plugin Update Notice</strong> to work. <a href='http://codex.wordpress.org/Upgrading_WordPress'>Please upgrade!</a></div>\n";
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

		$first = TRUE;

		foreach ( $current->response as $plugin_file => $update_data ) {
			// Make sure the plugin data is known and that it's activated
			if ( empty( $plugins[$plugin_file] ) || !in_array( $plugin_file, $active_plugins ) ) continue;

			// Make syre there is something to display
			if ( empty($plugins[$plugin_file]['Name']) ) $plugins[$plugin_file]['Name'] = $plugin_file;

			echo '	<div class="plugin-update"';
			if ( TRUE != $first ) echo ' style="border-top:none"';
			echo '>';

			if ( !current_user_can('edit_plugins') )
				printf( __('There is a new version of %1$s available. <a href="%2$s">Download version %3$s here</a>.'), $plugins[$plugin_file]['Name'], $update_data->url, $update_data->new_version);
			elseif ( empty($update_data->package) )
				printf( __('There is a new version of %1$s available. <a href="%2$s">Download version %3$s here</a> <em>automatic upgrade unavailable for this plugin</em>.'), $plugins[$plugin_file]['Name'], $update_data->url, $update_data->new_version);
			else
				printf( __('There is a new version of %1$s available. <a href="%2$s">Download version %3$s here</a> or <a href="%4$s">upgrade automatically</a>.'), $plugins[$plugin_file]['Name'], $update_data->url, $update_data->new_version, wp_nonce_url("update.php?action=upgrade-plugin&amp;plugin=$plugin_file", 'upgrade-plugin_' . $plugin_file) );

			echo "</div>\n";

			$first = FALSE;
		}
	}
}

// Start this plugin once all other files and plugins are fully loaded
add_action( 'plugins_loaded', create_function( '', 'global $GlobalPluginUpdateNotice; $GlobalPluginUpdateNotice = new GlobalPluginUpdateNotice();' ) );

?>