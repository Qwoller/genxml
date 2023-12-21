<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://meowone.ru
 * @since             1.0.0
 * @package           Mewoxml
 *
 * @wordpress-plugin
 * Plugin Name:       mewo-xml
 * Plugin URI:        https://meowone.ru
 * Description:       Генератор xml
 * Version:           1.0.0
 * Author:            Konstantin Chetin
 * Author URI:        https://meowone.ru
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mewoxml
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
define( 'MEWOXML_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mewoxml-activator.php
 */
function activate_mewoxml() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mewoxml-activator.php';
	Mewoxml_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mewoxml-deactivator.php
 */
function deactivate_mewoxml() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mewoxml-deactivator.php';
	Mewoxml_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mewoxml' );
register_deactivation_hook( __FILE__, 'deactivate_mewoxml' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mewoxml.php';

require plugin_dir_path( __FILE__ ) . 'includes/class-mewoxml-import.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mewoxml() {
	$plugin = new Mewoxml();
	$plugin->run();

}
add_action('init', 'run_mewoxml');


// Добавим крон-задачу при активации плагина
register_activation_hook( __FILE__, 'my_activation' );

// Удалим крон задачу при де-активации плагина
register_deactivation_hook( __FILE__, 'my_deactivation');

// php функция, которая будет выполнятся при наступлении крон-события
add_action( 'my_hourly_event', 'do_this_hourly' );

function my_activation() {

    // удалим на всякий случай все такие же задачи cron, чтобы
    // добавить новые с "чистого листа" это может понадобиться,
    // если до этого подключалась такая же задача неправильно (без проверки что она уже есть).
    wp_clear_scheduled_hook( 'my_hourly_event' );

    // Проверим нет ли уже задачи с таким же хуком
    // этот пункт не нужен, потому что мы выше удалил все задачи...
    // if( ! wp_next_scheduled( 'my_hourly_event' ) )

    // добавим новую cron задачу
    wp_schedule_event( time(), 'hourly', 'my_hourly_event');
}

function do_this_hourly() {
    $plugin = new Mewoxml();
    $plugin->do_this_hourly();
}

function my_deactivation() {
    wp_clear_scheduled_hook( 'my_hourly_event' );
}