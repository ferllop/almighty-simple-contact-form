<div id="ascf-wrapper">
    <h2>Formulario de contacto</h2>
    <?php echo (!empty($msg)) ? $msg : ''; ?>
    <p class="nota">* Todos los campos son obligatorios</p>
    <form id="ascf" action="" method="post" >
        
        <?php wp_nonce_field( 'form_nonce_' . get_the_ID(), 'form_nonce' ); ?>
        <input class="sugar" tabindex="-1" type="text" name="sugar" value="" />
        
        
        <input type="text" col="20" id="nametxt" name="nametxt" value="<?= (isset($_POST['nametxt'])) ? esc_attr($_POST['nametxt']) : ''; ?>" placeholder="Nombre" />
        
        <input type="text" col="50" id="emailtxt" name="emailtxt" value="<?= (isset($_POST['emailtxt'])) ? esc_attr($_POST['emailtxt']) : ''; ?>" placeholder="Email" />
        
        <input type="text" col="70" id="subjecttxt" name="subjecttxt" value="<?= (isset($_POST['subjecttxt'])) ? esc_attr($_POST['subjecttxt']) : ''; ?>" placeholder="Asunto" />
        
        <textarea rows="5" id="messagetxt" name="messagetxt" placeholder="Mensaje"><?= (isset($_POST['messagetxt'])) ? esc_textarea($_POST['messagetxt']) : ''; ?></textarea>
        
        <p>
            <input type="checkbox" name="checkfield" value="on" <?php echo (isset($_POST['checkfield']) && $_POST['checkfield'] == 'on') ? 'checked' : '' ?>/>
            <label for="checkfield">Entiendo que ninguno de estos datos será almacenado y que sólo serán utilizados para enviar el mensaje a través de email.</label>
        </p>
        
        <input type="submit" id="checkfield" name="submit" value="Enviar" />
        
    </form
</div>