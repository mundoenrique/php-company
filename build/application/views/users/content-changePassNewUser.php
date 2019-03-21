<div id="content-condiciones">
    <h1><?= lang('BREADCRUMB_WELCOME'); ?> <span class='first-title'>{titulo}</span></h1>
    <p id="text-alerta">
        {mensaje}
    </p>

    <div id="sidebar-cambioclave">
        <div id="widget-area">
            <div class="widget" id="widget-signin">
                <h2 class="widget-title">
                    <span aria-hidden="true" class="icon" data-icon="&#xe03f;"></span>
                    <?= lang('INFO_CHANGE_PASS'); ?>
                </h2>
                <div class="widget-content">
                    <?=form_fieldset()?>
                    <?php if($this->session->userdata('userold')){
                        echo form_hidden('useractive', '1');
                    }else{
                        echo form_hidden('useractive', '0');
                    }
                    ?>
                    <?=form_label('Contraseña actual *', 'userpwdOld');?>
                    <?=form_password(array(
                        'name' => 'userpwdOld',
                        'id' => 'userpwdOld',
                        'value' => '',
                        'placeholder' => 'Contraseña actual',
                    ))?>
                    <?=form_label('Contraseña nueva *', 'userpwd');?>
                    <?=form_password(array(
                        'name' =>'userpwd',
                        'id' => 'userpwd',
                        'value' => '',
                        'placeholder' => 'Contraseña nueva'
                    ))?>
                    <?=form_label('Confirme contraseña nueva *', 'userpwdConfirm');?>
                    <?=form_password(array(
                        'name' => 'userpwdConfirm',
                        'id' => 'userpwdConfirm',
                        'value' => '',
                        'placeholder' => 'Contraseña nueva'
                    ))?>
                    <?=form_fieldset_close()?>
                    <?=form_button(array('name'=>'cambioClave','id'=>'cambioClave','type'=>'submit','content'=>'Aceptar'))?>
                </div>
            </div>
            <div id="psw_info" style="display: none;">
    					<h5>Requerimientos para configurar la contraseña. La clave debe tener:</h5>
    					<ul style="list-style-type: none; padding: 0px; margin: 0px; font-size: 11px;">
								<li id="letter" class="invalid">De 8 a 15 <strong>Caracteres</strong></li>
								<li id="capital" class="invalid">Al menos una <strong>letra</strong></li>
								<li id="number" class="invalid">Al menos una <strong>letra mayúscula</strong></li>
								<li id="length" class="invalid">De 1 a 3 <strong>números</strong></li>
								<li id="especial" class="invalid">Al menos un <strong>caracter especial</strong> (ej: * & $ # . ?)</li>
								<li id="consecutivo" class="invalid">No debe tener más de 2 <strong>caracteres</strong> iguales consecutivos</li>
    				</ul>
</div>
        </div>
    </div>
</div>

<div id='loading' style='text-align:center' class='elem-hidden'><?php echo insert_image_cdn("loading.gif"); ?></div>
