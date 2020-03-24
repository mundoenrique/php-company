<?php
//echo $countryUri='co';
?>

<div class="bg-color">
  <div class="pt-3 pb-5 px-5 bg-content-config">
    <h1 class="primary h3 regular inline">Configuración</h1>
    <div class="flex mt-3 bg-color justify-between">
      <div class="flex mx-2">
        <nav class="nav-config">
          <ul class="nav-config-box">
            <li id="user" class="nav-item-config ">
              <a href="#">
                <img class="icon-config" src="<?= $this->asset->insertFile($countryUri.'/icon-user.svg');?>">
                <h5>Usuario</h5>
                <div class="box up left">
                  <img src="<?= $this->asset->insertFile($countryUri.'/icon-user.svg');?>" class="bg">
                  <h4>Usuario</h4>
                </div>
              </a>
            </li>
            <li id="enterprise" class="nav-item-config">
              <a href="#">
                <img class="icon-config" src="<?= $this->asset->insertFile($countryUri.'/icon-briefcase.svg');?>">
                <h5>Empresas</h5>
                <div class="box up left">
                  <img src="<?= $this->asset->insertFile($countryUri.'/icon-briefcase.svg');?>" class="bg">
                  <h4>Empresas</h4>
                </div>
              </a>
            </li>
            <li id="branch" class="nav-item-config"><a href="#">
                <img class="icon-config" src="<?= $this->asset->insertFile($countryUri.'/icon-building.svg');?>">
                <h5>Sucursales</h5>
                <div class="box up left">
                  <img src="<?= $this->asset->insertFile($countryUri.'/icon-building.svg');?>" class="bg">
                  <h4>Sucursales</h4>
                </div>
              </a>
            </li>
            <li id="downloads" class="nav-item-config active">
              <a href="#">
                <img class="icon-config" src="<?= $this->asset->insertFile($countryUri.'/icon-download.svg');?>">
                <h5>Descargas</h5>
                <div class="box up left">
                  <img src="<?= $this->asset->insertFile($countryUri.'/icon-download.svg');?>" class="bg ">
                  <h4>Descargas</h4>
                </div>
              </a>
            </li>
          </ul>
        </nav>
      </div>
        <div class="flex flex-auto flex-column" style="display:none">
        <div id="userView" style="display:none">
          <div class="flex mb-1 mx-4 flex-column">
            <span class="line-text mb-2 h4 semibold primary">Configuración de usuario</span>
            <div class="px-5">
              <div class="container">
                <div class="row my-2">
                  <div class="form-group col-12">
                    <span aria-hidden="true" class="icon icon-user"></span>
                    <span id="userName">Pedro</span>
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="form-group col-3">
                    <label for="firstName" id="firstName">Nombre</label>
                    <span id="firstNameUser" class="form-control px-1" readonly="readonly">Pedro</span>
                  </div>

                  <div class="form-group col-3">
                    <label for="lastName" id="lastName">Apellido</label>
                    <span id="firstNameUser" class="form-control px-1" readonly="readonly">Perez</span>
                  </div>

                  <div class="form-group col-3">
                    <label for="ocupation" id="ocupation">Cargo</label>
                    <span id="ocupationUser" class="form-control px-1" readonly="readonly">Analista</span>
                  </div>

                  <div class="form-group col-3">
                    <label for="area" id="area">Área</label>
                    <span id="areaUser" class="form-control px-1" readonly="readonly">Tecnologia</span>
                  </div>
                </div>
                <form method="post">
                  <div class="row">
                    <div class="form-group col-6 col-lg-5 col-xl-6">
                      <label for="emailUser" id="emailUser">Correo</label>
                      <input type="email" class="form-control" id="emailUser" name="emailUser">
                      <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6 flex justify-end">
                      <button id="changesSave" class="btn btn-primary btn-small" type="submit">Guardar cambios</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="flex flex-auto flex-column">
            <div class="flex mb-5 mx-4 flex-column ">
              <span class="line-text slide-slow flex mb-2 h4 semibold primary">Cambio de contraseña
                <i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
              </span>
              <div class="section my-2 px-5">
                <form method="post">
                  <div class="container">
                    <div class="row">
                      <div class="col-6">
                        <div class="row">
                          <div class="form-group col-12 col-lg-12">
                            <label for="currentUserPwd">Contraseña actual</label>
                            <div class="input-group">
                              <input id="currentUserPwd" class="form-control" type="password" name="currentUserPwd">
                              <div class="input-group-append">
                                <span id="pwd-addon" class="input-group-text"
                                  title="Clic aquí para mostrar/ocultar contraseña"><i class="icon-view mr-0"></i></span>
                              </div>
                            </div>
                            <div class="help-block"></div>
                          </div>
                          <div class="form-group col-12 col-lg-6">
                            <label for="newUserPwd">Nueva Contraseña</label>
                            <div class="input-group">
                              <input id="newUserPwd" class="form-control" type="password" name="newUserPwd">
                              <div class="input-group-append">
                                <span id="pwd-addon" class="input-group-text"
                                  title="Clic aquí para mostrar/ocultar contraseña"><i class="icon-view mr-0"></i></span>
                              </div>
                            </div>
                            <div class="help-block"></div>
                          </div>
                          <div class="form-group col-12 col-lg-6">
                            <label for="confirmUserPwd">Confirmar Contraseña</label>
                            <div class="input-group">
                              <input id="confirmUserPwd" class="form-control" type="password" name="confirmUserPwd">
                              <div class="input-group-append">
                                <span id="pwd-addon" class="input-group-text"
                                  title="Clic aquí para mostrar/ocultar contraseña"><i class="icon-view mr-0"></i></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-6 flex justify-center">
                        <div class="field-meter" id="password-strength-meter">
                          <h4>Requerimientos de contraseña:</h4>
                          <ul class="pwd-rules">
                            <li id="length" class="pwd-rules-item rule-invalid">De 8 a 15 <strong>Caracteres</strong>
                            </li>
                            <li id="letter" class="pwd-rules-item rule-invalid">Al menos una <strong>letra
                                minúscula</strong>
                            </li>
                            <li id="capital" class="pwd-rules-item rule-invalid">Al menos una <strong>letra
                                mayúscula</strong>
                            </li>
                            <li id="number" class="pwd-rules-item rule-invalid">De 1 a 3 <strong>números</strong></li>
                            <li id="especial" class="pwd-rules-item rule-invalid">Al menos un <strong>caracter
                                especial</strong><br>(ej: ! @ ? + - . , #)</li>
                            <li id="consecutivo" class="pwd-rules-item rule-invalid">No debe tener más de 2
                              <strong>caracteres</strong> iguales consecutivos</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-6 flex justify-end">
                        <button id="changesSave1" class="btn btn-primary btn-small" type="submit">Guardar cambios</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div id="enterpriseView" style="display:none">
          <div class="flex mb-1 mx-4 flex-column">
            <span class="line-text mb-2 h4 semibold primary">Configuración de la empresa</span>
            <div class="px-5">

              <div class="container">
                <div class="row mb-2">
                  <div class="form-group col-12 col-lg-8 col-xl-6">
                    <label class="mt-1">Empresa</label>
                    <select class="select-box custom-select mb-3 h6 w-100">
                      <option selected disabled>Seleccionar</option>
                      <option>Option 1</option>
                      <option>Option 2</option>
                      <option>Option 3</option>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                    <label for="idNumber" id="idNumber">Nro. identificador</label>
                    <span id="idNumberUser" class="form-control px-1" readonly="readonly">20602985971</span>
                  </div>

                  <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                    <label for="compName" id="compName">Nombre</label>
                    <span id="compNameUser" class="form-control px-1" readonly="readonly">RAPPI SAC</span>
                  </div>

                  <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                    <label for="busiName" id="busiName">Razón social</label>
                    <span id="busiNameUser" class="form-control px-1" readonly="readonly">RAPPI</span>
                  </div>

                  <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                    <label for="contact" id="contact">Contacto</label>
                    <span id="contactUser" class="form-control px-1" readonly="readonly">EUGENIO LA ROSA SABA</span>
                  </div>

                  <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                    <label for="address" id="address">Dirección</label>
                    <span id="addressUser" class="form-control px-1" readonly="readonly">Lorem ipsum dolor sit amet</span>
                  </div>

                  <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                    <label for="TempAddress" id="TempAddress">Dirección de facturación</label>
                    <span id="TempAddressUser" class="form-control px-1" readonly="readonly">Lorem ipsum dolor sit
                      amet</span>
                  </div>
                </div>

                <form action="post">
                  <div class="row">
                    <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                      <label for="phone">Teléfono 1</label>
                      <input id="phone1" name="phone1" type="text" class="form-control" value="" />
                      <div class="help-block"></div>
                    </div>

                    <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                      <label for="phone">Teléfono 2</label>
                      <input id="phone2" name="phone2" type="text" class="form-control" value="" />
                      <div class="help-block"></div>
                    </div>
                    <div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
                      <label for="phone">Teléfono 3</label>
                      <input id="phone3" name="phone3" type="text" class="form-control" value="" />
                      <div class="help-block"></div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="flex mb-2 justify-end col-12">
                      <button id="changesSave2" class="btn btn-primary btn-small" type="submit">Guardar cambios</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="flex flex-auto flex-column">
            <div class="flex flex-column mx-4 mb-5">
              <span class="line-text slide-slow flex mb-2 h4 semibold primary">Agregar contacto
                <i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
              </span>
              <div class="section my-2 px-5">
                <form method="post">
                  <div class="container">
                    <div class="row">
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="contName">Nombre</label>
                        <input id="contName" name="contName" type="text" class="form-control" value="" />
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="surname">Apellido</label>
                        <input id="surname" name="surname" type="text" class="form-control" value="" />
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="contOcupation">Cargo</label>
                        <input id="contOcupation" name="contOcupation" type="text" class="form-control" value="" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="contNIT">NIT</label>
                        <input id="contNIT" name="contNIT" type="text" class="form-control" value="" />
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="contEmail">Correo Electrónico</label>
                        <input type="email" class="form-control" id="contEmail" name="email" value="">
                        <div class="help-block"></div>
                      </div>
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label or="">Empresa</label>
                        <select class="select-box custom-select mb-3 h6 w-100">
                          <option selected disabled>Seleccionar</option>
                          <option value="F">Contacto Administracion y finanzas</option>
                          <option value="H">Contacto RRHH</option>
                          <option value="C">Contacto</option>
                        </select>
                      </div>
                    </div>

                    <div class="row flex mb-4 mt-2 justify-end items-center">
                      <div class="col-6 col-lg-4 col-xl-3">
                        <input id="password" class="form-control" type="password" placeholder="Ingresa tu contraseña">
                      </div>
                        <div class="col-3 col-lg-2 col-xl-auto">
                          <button class="btn btn-primary btn-small flex mx-auto">Limpiar</button>
                        </div>
                        <div class="col-3 col-lg-2 col-xl-auto">
                          <button class="btn btn-primary btn-small flex mx-auto">Agregar</button>
                        </div>
                      </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div id="branchView" style="display:none">
          <div class="flex mb-1 mx-4 flex-column">
            <span class="line-text mb-2 h4 semibold primary">Configuración de sucursales</span>
            <div class="px-5">
              <div class="container">
                <div class="row">
                  <div class="form-group col-7">
                    <label class="mt-1" or="">Sucursal</label>
                    <select class="select-box custom-select mb-2 h6 w-100">
                      <option selected disabled>Seleccionar</option>
                      <option>Option 1</option>
                      <option>Option 2</option>
                      <option>Option 3</option>
                    </select>
                  </div>
                </div>
                <form method="post">
                  <div class="row mb-2">
                    <div class="col-7 mt-1 bg-color">
                      <input type="file" name="file" id="file" class="input-file">
                      <label for="file" class="label-file js-label-file">
                        <i class="icon icon-upload mr-1 pr-3 right"></i>
                        <span class="js-file-name h6 regular">Selecciona archivo de sucursales.</span>
                      </label>
                    </div>

                    <div class="col-auto mt-5">
                      <button class="btn btn-primary btn-small">
                        Seleccionar
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="flex flex-auto flex-column">
            <div class="flex flex-column mx-4 mb-5">
              <span class="line-text slide-slow flex mb-2 h4 semibold primary">Agregar nueva sucursal
                <i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
              </span>
              <div class="section my-2 px-5">
                <form method="post">
                  <div class="container">
                    <div class="row">
                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="branchName">Nombre</label>
                        <input id="branchName" name="branchName" type="text" class="form-control" value=""
                          placeholder="Nombre de la empresa" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="zoneName">Zona</label>
                        <input id="zoneName" name="zoneName" type="text" class="form-control" value=""
                          placeholder="Punto de referencia" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="address1">Dirección 1</label>
                        <input id="address1" name="address1" type="text" class="form-control" value=""
                          placeholder="Dirección principal" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="address2">Dirección 2</label>
                        <input id="address2" name="address1" type="text" class="form-control" value=""
                          placeholder="Dirección alternativa" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="address3">Dirección 3</label>
                        <input id="address3" name="address1" type="text" class="form-control" value=""
                          placeholder="Dirección alternativa" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label or="">País</label>
                        <select class="select-box custom-select mb-3 h6 w-100">
                          <option selected disabled>Seleccionar</option>
                          <option>Option 1</option>
                          <option>Option 2</option>
                          <option>Option 3</option>
                        </select>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label or="">Departamento</label>
                        <select class="select-box custom-select mb-3 h6 w-100">
                          <option selected disabled>Seleccionar Departamento</option>
                          <option>Option 1</option>
                          <option>Option 2</option>
                          <option>Option 3</option>
                        </select>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label or="">Provincia</label>
                        <select class="select-box custom-select mb-3 h6 w-100">
                          <option selected disabled>Seleccionar provincia</option>
                          <option>Option 1</option>
                          <option>Option 2</option>
                          <option>Option 3</option>
                        </select>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="areaCode">Código de área</label>
                        <input id="areaCode" name="areaCode" type="text" class="form-control" value=""
                          placeholder="Código de área" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="phone">Teléfono</label>
                        <input id="phone" name="phone" type="text" class="form-control" value="" placeholder="Teléfono" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="contact">Nombre del contacto</label>
                        <input id="contact" name="contact" type="text" class="form-control" value=""
                          placeholder="Nombre del contacto" />
                        <div class="help-block"></div>
                      </div>

                      <div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
                        <label for="branchCode">Código de sucursal</label>
                        <input id="branchCode" name="branchCode" type="text" class="form-control" value=""
                          placeholder="Código de la sucursal" />
                        <div class="help-block"></div>
                      </div>

                    </div>

                    <div class="row flex mb-4 mt-2 pl-5 justify-end items-center">
                      <div class="col-7 col-lg-4 col-xl-3">
                        <input id="password1" class="form-control" type="password" placeholder="Ingresa tu contraseña">
                      </div>
                        <div class="col-auto">
                          <button class="btn btn-primary btn-small flex mx-auto">Agregar</button>
                        </div>
                      </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div id="downloadsView" style="display:none">
          <div class="flex mb-1 mx-4 flex-column">
            <span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_DOWNLOADS') ?></span>
            <div class="px-5">
              <div class="my-2 tertiary h4 semibold">
                <span><?= lang('GEN_MANUALS') ?></span>
              </div>
              <div class="container">
                <div class="row">
                  <div class="mb-3 col-auto col-lg-6 col-xl-5">
                    <a id="download-file" href="<?= $this->asset->insertFile($countryUri.'/archivo.pdf','downloads');?>" download>
                      <div class="files btn-link flex items-center">
                        <div class="file">
                          <img src="<?= $this->asset->insertFile($countryUri.'/icon-pdf.svg');?>" />
                        </div>
                        <span class="ml-2 flex justify-center"><?= lang('GEN_CEO_USER_MANUAL')?></span>
                      </div>
                    </a>
                  </div>
                  <?php if(verifyDisplay('body','configuration', lang('GEN_GL_USER_MANUAL'))): ?>
                  <div class="mb-3 col-auto col-lg-6 col-xl-5">
                    <a id="download-file" href="<?= $this->asset->insertFile($countryUri.'/archivo.pdf','downloads');?>" download>
                      <div class="files btn-link flex items-center">
                        <div class="file">
                          <img src="<?= $this->asset->insertFile($countryUri.'/icon-pdf.svg');?>" />
                        </div>
                        <span class="ml-2 flex justify-center"><?= lang('GEN_GL_USER_MANUAL')?></span>
                      </div>
                    </a>
                  </div>
				        <?php endif; ?>  
                </div>
                <div class="container">
                  <div class="row">
                    <div class="col-sm-12 col-lg-11 col-xl-12 py-2">
                      <div class="manual-video">
                        <video controls preload>
                          <source src="<?= $this->asset->insertFile($countryUri.'/video.mp4','downloads');?>" type="video/mp4">
                        </video>
                      </div>
                    </div>
                  </div>
                </div>
				<?php if(verifyDisplay('body','configuration', lang('GEN_GL_USER_MANUAL'))): ?>
			        	<div class="my-2 tertiary h4 semibold">
                  <span><?= lang('GEN_APPLICATIONS') ?></span>
                </div>
                <div class="row">
                  <div class="mb-3 col-auto col-lg-6 col-xl-5">
                    <a href="#">
                      <div class="files btn-link flex items-center">
                        <div class="file">
                          <img src="<?= $this->asset->insertFile($countryUri.'/icon-zip.svg');?>"/>
                        </div>
                        <span class="ml-2 flex justify-center">Gestor de lotes (1.759kb)</span>
                      </div>
                    </a>
                  </div>
                  <div class="mb-3 col-auto col-lg-6 col-xl-5">
                    <a href="#">
                      <div class="files btn-link flex items-center">
                        <div class="file">
                          <img src="<?= $this->asset->insertFile($countryUri.'/icon-zip.svg');?>" />
                        </div>
                        <span class="ml-2 flex justify-center">Java JRE 1.6 (14.226kb)</span>
                      </div>
                    </a>
                  </div>
                </div>
                <?php endif; ?>
                
								<div class="my-2 tertiary h4 semibold">
                  <span>Archivos de gestión Conexión Empresas Online</span>
                </div>
                <div class="row">
                  <div class="form-group col-auto mb-3 col-xl-5">
                    <a href="#">
                      <div class="files btn-link flex items-center">
                        <div class="file">
                          <img src=<?= $this->asset->insertFile($countryUri.'/icon-rar.svg');?> />
                        </div>
                        <span class="ml-2 flex justify-center">Archivos lotes operativos (194kb)</span>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>