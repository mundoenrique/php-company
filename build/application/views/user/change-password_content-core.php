<?php defined('BASEPATH') or exit('No direct script access allowed '); ?>

<div class="logout-content max-width-4 mx-auto p-responsive py-4">
    <header class="">
      <h1 class="primary h0">Bienvenido(a) Luisana Iglesias</h1>
    </header>

    <section>
      <hr class="separador-one">
      <div class="pt-3">
        <p>Clave vencida. Por motivos de seguridad es necesario que cambies tu contraseña antes de continuar en nuestro
          sistema <strong>"Conexión Empresas Online"</strong>.</p>
        <form id="formChangePassword" class="mt-4" method="post">
          <div class="row">
            <div class="col-6 col-lg-8 col-xl-6">
              <div class="row">
                <div class="form-group col-12 col-lg-6">
                  <label for="currentPassword">Contraseña actual </label>
                  <div class="input-group">
                    <input id="currentPassword" class="form-control" type="password" name="currentPassword">
                    <div class="input-group-append">
                      <span id="pwdAddon" class="input-group-text" title="Clic aquí para mostrar/ocultar contraseña"><i class="icon-view mr-0"></i></span>
                    </div>
                  </div>
                  <div class="help-block"></div>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-12 col-lg-6">
                  <label for="newPassword">Nueva contraseña </label>
                  <div class="input-group">
                    <input id="newPassword" class="form-control" type="password" name="newPassword">
                    <div class="input-group-append">
                      <span id="pwdAddon2" class="input-group-text" title="Clic aquí para mostrar/ocultar contraseña"><i class="icon-view mr-0"></i></span>
                    </div>
                  </div>
                  <div class="help-block"></div>
                </div>
                <div class="form-group col-12 col-lg-6">
                  <label for="confirmPassword">Confirmar contraseña</label>
                  <div class="input-group">
                    <input id="confirmPassword" class="form-control" type="password" name="confirmPassword">
                    <div class="input-group-append">
                      <span id="pwdAddon3" class="input-group-text" title="Clic aquí para mostrar/ocultar contraseña"><i class="icon-view mr-0"></i></span>
                    </div>
                  </div>
                  <div class="help-block"></div>
                </div>
              </div>
            </div>

            <div class="col-6 col-lg-4 col-xl-6">
              <div class="field-meter" id="password-strength-meter">
                <h4>Requerimientos de contraseña:</h4>
                <ul class="pwd-rules">
                  <li id="length" class="pwd-rules-item rule-invalid">De 8 a 15 <strong>caracteres.</li>
									<li id="letter" class="pwd-rules-item rule-invalid">Al menos una <strong>letra minúscula.</strong>></li>
                  <li id="capital" class="pwd-rules-item rule-invalid">Al menos una <strong>letra mayúscula.</strong>
                  </li>
                  <li id="number" class="pwd-rules-item rule-invalid">De 1 a 3 <strong>números.</strong></li>
                  <li id="especial" class="pwd-rules-item rule-invalid">Al menos un
                    <strong>carácter especial </strong>(ej: ! @ * - ? ¡ ¿ + / . , _ #).</li>
                  <li id="consecutivo" class="pwd-rules-item rule-invalid">No debe tener más de 2
                    <strong>caracteres</strong> iguales consecutivos.</li>
                </ul>
              </div>
            </div>
          </div>

          <hr class="separador-one mt-2 mb-4">
          <div class="flex items-center justify-end">
          <a class="btn underline" href="#">Cancelar</a>
            <button id="btnChangePassword" class="btn btn-small btn-loading btn-primary" type="submit" name="btnChangePassword">Aceptar</button>
          </div>
        </form>
      </div>
    </section>
  </div>
</div>
