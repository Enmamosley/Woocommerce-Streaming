<?php

// Encolar el script de administración para la gestión de credenciales
add_action('admin_enqueue_scripts', 'wc_enqueue_admin_scripts');
function wc_enqueue_admin_scripts($hook) {
    global $post;
    
    // Solo cargar el script en la página de edición de productos
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        if ('product' === $post->post_type) {
            wp_enqueue_script('wc-admin-credentials', plugin_dir_url(__FILE__) . '../assets/js/admin.js', array('jquery'), '1.0', true);
        }
    }
}


// Añadir el metabox de credenciales a la página de productos
add_action('add_meta_boxes', 'wc_add_credentials_metabox');
function wc_add_credentials_metabox() {
    add_meta_box(
        'wc_credentials_metabox', 
        'Credenciales del Producto', 
        'wc_credentials_metabox_callback', 
        'product', 
        'normal', 
        'high'
    );
}

function wc_credentials_metabox_callback($post) {
    $credentials = get_post_meta($post->ID, '_product_credentials', true);

    echo '<div id="credentials-wrapper">';
    if (!empty($credentials)) {
        foreach ($credentials as $index => $credential) {
            echo wc_generate_credential_fields($credential, $index);
        }
    }
    echo '</div>';
    echo '<button type="button" id="add-credential-button" style="margin-left: 0.5rem;">Añadir Credencial</button>';

    wp_nonce_field('wc_save_product_credentials', 'wc_product_credentials_nonce');
}

function wc_generate_credential_fields($credential = array(), $index = 0) {
    $user = $credential['user'] ?? '';
    $password = $credential['password'] ?? '';
    $link = $credential['link'] ?? '';
    $note = $credential['note'] ?? '';

    return "<div style='margin-left: 0.5rem; margin-bottom: 0.2rem;'>
        <label>Usuario:</label><input type='text' name='product_credentials[$index][user]' value='$user'>
        <label>Contraseña:</label><input type='text' name='product_credentials[$index][password]' value='$password'>
        <label>Enlace:</label><input type='text' name='product_credentials[$index][link]' value='$link'>
        <label>Nota:</label><input type='text' name='product_credentials[$index][note]' value='$note'>
    </div>";
}

// Guardar las credenciales al guardar el producto
add_action('save_post', 'wc_save_product_credentials');
function wc_save_product_credentials($post_id) {
    if (!isset($_POST['wc_product_credentials_nonce']) || !wp_verify_nonce($_POST['wc_product_credentials_nonce'], 'wc_save_product_credentials')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['product_credentials'])) {
        update_post_meta($post_id, '_product_credentials', $_POST['product_credentials']);
    } else {
        delete_post_meta($post_id, '_product_credentials');
    }
}
