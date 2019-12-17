<?php
/**
 * Trustpilot-reviews
 *
 *
 * @package   Trustpilot-reviews
 * @author    Trustpilot
 * @license   AFL-3.0
 * @link      https://trustpilot.com
 * @copyright 2018 Trustpilot
 *
 * @wordpress-plugin
 * Plugin Name:       Trustpilot-reviews
 * Description:       Trustpilot-reviews helps Woocommerce store owners generate a ton of reviews for their products.
 * Version:           2.5.788
 * Author:            Trustpilot
 * Author URI:        https://trustpilot.com
 * Text Domain:       Trustpilot-reviews
 * License:           AFL-3.0
 * License URI:       https://www.afl.org/licenses/afl-3.0.txt
 */

namespace Trustpilot\Review;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

include( plugin_dir_path( __FILE__ ) . './config.php');
include( plugin_dir_path( __FILE__ ) . './review/api/TrustpilotPluginStatus.php');
include( plugin_dir_path( __FILE__ ) . './review/api/TrustpilotHttpClient.php');
include( plugin_dir_path( __FILE__ ) . './review/util/TrustpilotLogger.php');
include( plugin_dir_path( __FILE__ ) . './helper.php');
include( plugin_dir_path( __FILE__ ) . './legacy.php');
define( 'TRUSTPILOT_REVIEWS_VERSION', TRUSTPILOT_PLUGIN_VERSION );

/**
 * Autoloader
 *
 * @param string $class The fully-qualified class name.
 */
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = __NAMESPACE__;

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/review/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Initialize Plugin
 */
function init() {
    $Review = Plugin::get_instance();
    $Review_admin = Admin::get_instance();
    $Review_orders = Orders::get_instance();
    $Review_pastorders = PastOrders::get_instance();
    $Review_trustbox = TrustBox::get_instance();
    $Review_updater = Updater::get_instance();
}
add_action( 'plugins_loaded', 'Trustpilot\\Review\\init' );

/**
 * Register activation and deactivation hooks
 */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( 'trustpilot\\review\\Plugin', 'settings_link'));
register_activation_hook( __FILE__, array( 'trustpilot\\review\\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'trustpilot\\review\\Plugin', 'deactivate' ) );
init();
