jQuery(document).ready(function($) {
    $('#add-credential-button').on('click', function() {
        var index = $('#credentials-wrapper div').length;
        var newField = '<div style="margin-left: 0.5rem; margin-bottom: 0.2rem;">' +
                       '<label>Usuario:</label><input type="text" name="product_credentials[' + index + '][user]">' +
                       '<label>Contraseña:</label><input type="text" name="product_credentials[' + index + '][password]">' +
                       '<label>Enlace:</label><input type="text" name="product_credentials[' + index + '][link]">' +
                       '<label>Nota:</label><input type="text" name="product_credentials[' + index + '][note]">' +
                       '</div>';
        $('#credentials-wrapper').append(newField);
    });
});
