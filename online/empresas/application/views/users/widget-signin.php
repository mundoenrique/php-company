<?php
$pais = $this->uri->segment(1);
$recoverPwdLink = $this->config->item('base_url') .$pais. '/users/pass_recovery';
?>

<div class="widget tooltip" id="widget-signin">
    <h2 class="widget-title">
        <span aria-hidden="true" class="icon" data-icon="&#xe03f;"></span>
        <?php echo lang('WIDGET_LOGIN_TITLE'); ?></h2>
    <div class="widget-content">
        <?php echo validation_errors(); ?>

        <?=form_open('ve/eol/login' , array('id'=>
            'adminLoginForm','accept-charset'=>'utf-8'))?>
        <?=form_fieldset()?>
        <?=form_hidden('hash', '123456');?>
        <?=form_label('Usuario', 'user_login');?>
        <?=form_input(array('name' =>
            'user_login',
            'id' => 'user_login',
            'value' => '',
            'placeholder' => 'Usuario',
        ))?>
        <?=form_label('Contraseña', 'user_pass');?>
        <?=form_password(array(
            'name' =>
                'user_pass',
            'id' => 'user_pass',
            'value' => '',
            'placeholder' => 'Contraseña'
        ))?>
        <?=form_fieldset_close()?>

        <?=form_close();?>
        <div id="sliderbutton-login"></div>
        
            <p class="align-center" style="font-size: 12px; margin-top: 50px; text-align: center;">Restablecer contraseña
                <br>
                <a href="<?php echo $recoverPwdLink;?>" rel="section">¿Olvidó o bloqueó su<br> clave de acceso?</a>
            </p>
        
        <div id="login-mobile" style="display:none">
            <span class="verifica_sesion" style="display:none">Verificado...</span> <button id="button-login">Ingresar</button>
        </div>
    </div>
</div>
