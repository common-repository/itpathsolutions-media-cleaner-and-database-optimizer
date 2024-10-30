<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.itpathsolutions.com
 * @since             1.0.0
 * @package           Aiowc
 *
 * @wordpress-plugin
 * Plugin Name:       Media Cleaner and Database Optimizer for WordPress
 * Plugin URI:        https://wordpress.org/plugins/itpathsolutions-media-cleaner-and-database-optimizer/
 * Description:       The most powerful tool for clearing unused media from your website and optimizing your database to boost site performance.
 * Version:           1.0.3
 * Author:            IT Path Solutions
 * Author URI:        https://www.itpathsolutions.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       aiowc
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AIOWC_VERSION', '1.0.3' );

define( 'AIOWC_PLUGIN_BASENAME', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-aiowc-activator.php
 */
function activate_aiowc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aiowc-activator.php';
	Aiowc_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-aiowc-deactivator.php
 */
function deactivate_aiowc() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aiowc-deactivator.php';
	Aiowc_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_aiowc' );
register_deactivation_hook( __FILE__, 'deactivate_aiowc' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-aiowc.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_aiowc() {

	$plugin = new Aiowc();
	$plugin->run();

}
run_aiowc();


// Register REST API route
add_action('rest_api_init', function() {
    register_rest_route('wp-site-health/v1', '/directory-sizes', array(
        'methods'  => 'GET',
        'callback' => 'get_directory_sizes',
        'permission_callback' => '__return_true', // Adjust permissions as needed
    ));
});

function get_directory_sizes() {
    try {
        // Define directory paths
        $wordpress_path = ABSPATH;
        $themes_path = WP_CONTENT_DIR . '/themes';
        $plugins_path = WP_CONTENT_DIR . '/plugins';
        $uploads_path = WP_CONTENT_DIR . '/uploads';
        // Calculate directory sizes
        $wordpress_size = get_directory_size($wordpress_path);
        $themes_size = get_directory_size($themes_path);
        $plugins_size = get_directory_size($plugins_path);
        $uploads_size = get_directory_size($uploads_path);

        // Get database size
        $database_size = get_database_size();

        // Calculate total size
        $total_size = $wordpress_size + $themes_size + $plugins_size + $uploads_size + $database_size;

        // Prepare response data
        $data = array(
            'wordpress_size' => array('size' => format_size($wordpress_size), 'raw' => $wordpress_size),
            'themes_size'    => array('size' => format_size($themes_size), 'raw' => $themes_size),
            'plugins_size'   => array('size' => format_size($plugins_size), 'raw' => $plugins_size),
            'uploads_size'   => array('size' => format_size($uploads_size), 'raw' => $uploads_size),
            'database_size'  => array('size' => format_size($database_size), 'raw' => $database_size),
            'total_size'     => array('size' => format_size($total_size), 'raw' => $total_size),
        );

        return new WP_REST_Response($data, 200);
    } catch (Exception $e) {
        // Return detailed error information
        return new WP_REST_Response(array(
            'code'    => 'not_available',
            'message' => 'Directory sizes could not be returned. ' . $e->getMessage(),
            'data'    => array('status' => 500),
        ), 500);
    }
}

// Function to calculate directory size (example implementation)
function get_directory_size($path) {
    $size = 0;

    try {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
    } catch (Exception $e) {
        // Handle exception if directory cannot be read
        error_log("Error calculating directory size for {$path}: " . $e->getMessage());
    }

    return $size;
}


// Function to get database size (example implementation)
function get_database_size() {
    global $wpdb;
    $results = $wpdb->get_results("SHOW TABLE STATUS", ARRAY_A);
    $size = 0;
    foreach ($results as $row) {
        $size += $row['Data_length'] + $row['Index_length'];
    }
    return $size;
}

// Function to format size (example implementation)
function format_size($size) {
    if ($size >= 1073741824) {
        $size = number_format($size / 1073741824, 2) . ' GB';
    } elseif ($size >= 1048576) {
        $size = number_format($size / 1048576, 2) . ' MB';
    } elseif ($size >= 1024) {
        $size = number_format($size / 1024, 2) . ' KB';
    } elseif ($size > 1) {
        $size = $size . ' bytes';
    } elseif ($size == 1) {
        $size = $size . ' byte';
    } else {
        $size = '0 bytes';
    }
    return $size;
}

