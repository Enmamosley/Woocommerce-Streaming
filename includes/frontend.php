<?php

// Asignar credenciales al cliente después de la compra
add_action('woocommerce_order_status_completed', 'wc_assign_credentials_to_customer', 10, 1);
function wc_assign_credentials_to_customer($order_id) {
    $order = wc_get_order($order_id);

// Verificar si ya se han asignado credenciales para este pedido
if (get_post_meta($order_id, '_credentials_assigned', true)) {
    return; // Si ya se asignaron, no hacer nada
}
    foreach ($order->get_items() as $item) {
        $product_id = $item->get_product_id();
        $credentials = get_post_meta($product_id, '_product_credentials', true);

        if (!empty($credentials)) {
            // Asignar el primer conjunto de credenciales no utilizadas
            $assigned_credentials = array_shift($credentials);
            update_post_meta($product_id, '_product_credentials', $credentials); // Guardar las credenciales restantes

            // Mostrar credenciales en la página de agradecimiento
            echo '<h2>Credenciales Asignadas:</h2>';
            echo '<p>Usuario: ' . esc_html($assigned_credentials['user']) . '</p>';
            echo '<p>Contraseña: ' . esc_html($assigned_credentials['password']) . '</p>';
            echo '<p>Enlace: ' . esc_html($assigned_credentials['link']) . '</p>';
            echo '<p>Nota: ' . esc_html($assigned_credentials['note']) . '</p>';

            // Enviar credenciales por correo
            $to = $order->get_billing_email();
            $subject = 'Tus credenciales para el producto ' . $item->get_name();
            $message = "Usuario: {$assigned_credentials['user']}\nContraseña: {$assigned_credentials['password']}\nEnlace: {$assigned_credentials['link']}\nNota: {$assigned_credentials['note']}";
            wp_mail($to, $subject, $message);

             // Añadir credenciales como nota al pedido
            $order->add_order_note('Credenciales Asignadas: 
    Usuario: ' . esc_html($assigned_credentials['user']) . '
    Contraseña: ' . esc_html($assigned_credentials['password']) . '
    Enlace: ' . esc_html($assigned_credentials['link']) . '
    Nota: ' . esc_html($assigned_credentials['note']),
    true // "true" para que sea visible al cliente
);

            
             update_post_meta($order_id, '_credentials_assigned', 'yes');
        }
    }
}
