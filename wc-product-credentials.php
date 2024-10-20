<?php
/**
 * Plugin Name: WooCommerce Product Credentials
 * Plugin URI: https://mosley.mx
 * Description: Asigna credenciales únicas a productos y distribúyelas tras la compra.
 * Version: 1.0
 * Author: Enmanoell Mosley
 * Author URI: https://mosley.mx
 * License: GPLv2 or later
 * Text Domain: wc-product-credentials
 */

// Evitar acceso directo
if (!defined('ABSPATH')) exit;

// Incluir archivos del plugin
include_once plugin_dir_path(__FILE__) . 'includes/admin.php';
include_once plugin_dir_path(__FILE__) . 'includes/frontend.php';

// Función para activar el plugin
function wc_product_credentials_activate() {
    // Código de activación si es necesario
}
register_activation_hook(__FILE__, 'wc_product_credentials_activate');
