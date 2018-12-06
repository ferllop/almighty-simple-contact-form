<?php 
/*
Plugin Name: Almighty Simple Contact Form
*/
function custom_phpmailer_init($PHPMailer)
{
	$PHPMailer->IsSMTP();
	$PHPMailer->SMTPAuth = true;
	$PHPMailer->SMTPSecure = 'ssl';
	$PHPMailer->Host = 'smtp.gmail.com';
	$PHPMailer->Port = 465;
	$PHPMailer->Username = 'xxxxxxx@gmail.com'; // The sender service username
	$PHPMailer->Password = 'xxxyyy123'; // The sender service password
}
add_action('phpmailer_init', 'custom_phpmailer_init');

function ascf_send_email($name, $email, $subject, $message){
    
    $name = sanitize_text_field($name);
    $email = sanitize_email($email);
    $subject = sanitize_text_field($subject);
    $message = sanitize_textarea_field($message);
    
    $to = 'yyyy@xxxxx.zzz'; // The destination email where form message will be sent
    $headers = 'From: ' . $name . ' desde formulario web <xxxxxxxx@yyyy.zzz>' . "\r\n" . 'Reply-To: ' . $email . "\r\n";
    return wp_mail($to, $subject, strip_tags($message), $headers);
}

function ascf_autorespond_email($sent_name, $sent_email, $sent_subject, $sent_message){
    
    $sent_name = sanitize_text_field($sent_name);
    $sent_email = sanitize_email($sent_email);
    $sent_subject = sanitize_text_field($sent_subject);
    $sent_message = sanitize_textarea_field($sent_message);
    
    $to = $sent_email;
    $subject = 'Has enviado un mensaje a ' .get_bloginfo('name');
    $headers = 'From: ' . get_bloginfo('name') . ' <xxxxx@yyyyy.zzz>' . "\r\n" . 'Reply-To: xxxxxx@yyyyyy.zzz' . "\r\n";
    $message = 'Hola,

Esta es una respuesta automática. 
Has enviado un email a través del formulario de contacto de la web yyyyyy.zzz .
Gracias por contactar conmigo, te responderé lo más rápido posible.

Si has encontrado este email en tu bandeja de spam, recuerda moverlo a tu bandeja de entrada para que recibas correctamente futuros emails míos.

Si recibes este email por error, por favor responde a esta misma dirección de email para solucionar el problema.

Un saludo.

Este es el mensaje que has enviado, tal y como lo recibiré:

Nombre: ' . $sent_name . '
Email: ' . $sent_email . '
Asunto: ' . $sent_subject . '
Mensaje:
' . $sent_message;
    return wp_mail($to, $subject, strip_tags($message), $headers);
    
}

function ascf_callback() {
    wp_enqueue_style( 'ascf_style',  plugins_url( '/almighty-simple-contact-form.css', __FILE__ ));
    
    ob_start();
    
    if ( isset($_POST['submit']) ) :
        if ( !empty($_POST['sugar']) || wp_verify_nonce( 'form_nonce', 'form_nonce_' . get_the_ID() ) ) {
            echo '<p class="ascf-error">Error de seguridad</p>';
            return ob_get_clean();
        };
        
        if ( empty($_POST['nametxt']) || empty($_POST['emailtxt']) || empty($_POST['subjecttxt']) || empty($_POST['messagetxt']) ) 
           $msg = '<p class="ascf-error">Debes rellenar todas las celdas</p>';
        
        if ( !empty($_POST['emailtxt']) && !filter_var($_POST['emailtxt'], FILTER_VALIDATE_EMAIL) )  
            $msg = '<p class="ascf-error">Dirección de email no válida</p>';
         
        
        if ( !isset($_POST['checkfield']) || $_POST['checkfield'] !== 'on') 
            $msg = '<p class="ascf-error">Debes marcar conforme entiendes el uso de los datos introducidos en este formulario</p>';
            
        if ( !isset($msg) ) { 
            $sent = ascf_send_email($_POST['nametxt'], $_POST['emailtxt'], $_POST['subjecttxt'], $_POST['messagetxt']); 
           
            if ($sent) : 
                ascf_autorespond_email($_POST['nametxt'], $_POST['emailtxt'], $_POST['subjecttxt'], $_POST['messagetxt']); ?>
                <p class="ascf-success">
                    <?php echo $_POST['nametxt'] ?>, gracias por contactar conmigo.
                    <br />Automáticamente, la web te ha enviado un email como confirmación del correcto envío.
                    <br />Si no lo recibes, revisa tu carpeta de spam, y muévelo a tu bandeja de entrada para recibir correctamente futuros emails míos.
                </p>
                <?php return ob_get_clean();
            else: ?>
                <p class="ascf-error">Ha habido un error de servidor en el envío del formulario. 
                <br />Puedes contactar conmigo por email en <a href="mailto: xxxxxx@yyyyyy.zzz">xxxxxx@yyyyyyy.zzz</a>
                <br />Siento mucho las molestias</p>
            <?php endif;
        }
        
    endif;

    require_once( dirname( __FILE__ ) . '/ascf-form.inc.php' );
    
    return ob_get_clean();
}
add_shortcode('almighty_simple_contact_form', 'ascf_callback');
?>
