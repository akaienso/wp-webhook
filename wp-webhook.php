<?php
/*
Plugin Name: WP-Webhook
Description: Create custom webhook endpoints and log incoming requests like webhook.site.
Version: 1.0.0-alpha.3
Author: Rob moore <io@rmoore.dev>
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WSNIFFER_PATH', plugin_dir_path( __FILE__ ) );
define( 'WSNIFFER_URL', plugin_dir_url( __FILE__ ) );

// Load components
require_once WSNIFFER_PATH . 'includes/class-endpoint-cpt.php';
require_once WSNIFFER_PATH . 'includes/class-request-logger.php';
require_once WSNIFFER_PATH . 'includes/class-api-handler.php';

// Init components
add_action( 'init', [ 'WS_Endpoint_CPT', 'register' ] );
add_action( 'rest_api_init', [ 'WS_API_Handler', 'register_routes' ] );
