<div class="bg-color">
	<div class="pt-3 pb-5 px-5 bg-content-config">
		<h1 class="primary h3 regular inline"><?= lang('GEN_SETTINGS_TITLE') ?></h1>
		<div class="flex mt-3 bg-color justify-between">
			<div class="flex mx-2">
				<nav class="nav-config">
					<ul class="nav-config-box">

						<?php if (lang('CONF_USER_BOOL') == 'ON' ): ?>

						<li id="user" class="nav-item-config">
							<a href="javascript:">
								<img class="icon-config" src="<?= $this->asset->insertFile('icon-user.svg', 'images/icons');?>">
								<h5><?= lang('GEN_BTN_USER') ?></h5>
								<div class="box up left">
									<img src="<?= $this->asset->insertFile('icon-user.svg', 'images/icons');?>" class="bg">
									<h4><?= lang('GEN_BTN_USER') ?></h4>
								</div>
							</a>
						</li>
						<?php endif; ?>


						<?php if (lang('CONF_COMPANIES_BOOL') == 'ON'): ?>

						<li id="enterprise" class="nav-item-config">
							<a href="javascript:">
								<img class="icon-config" src="<?= $this->asset->insertFile('icon-briefcase.svg', 'images/icons');?>">
								<h5><?= lang('GEN_BTN_ENTERPRISE') ?></h5>
								<div class="box up left">
									<img src="<?= $this->asset->insertFile('icon-briefcase.svg', 'images/icons');?>" class="bg">
									<h4><?= lang('GEN_BTN_ENTERPRISE') ?></h4>
								</div>
							</a>
						</li>
						<?php endif; ?>

						<?php if (lang('CONF_BRANCHES_BOOL') == 'ON'): ?>
						<li id="branch" class="nav-item-config">
							<a href="javascript:">
								<img class="icon-config" src="<?= $this->asset->insertFile('icon-building.svg', 'images/icons');?>">
								<h5><?= lang('GEN_BTN_BRANCH') ?></h5>
								<div class="box up left">
									<img src="<?= $this->asset->insertFile('/icon-building.svg', 'images/icons');?>" class="bg">
									<h4><?= lang('GEN_BTN_BRANCH') ?></h4>
								</div>
							</a>
						</li>
						<?php endif; ?>

						<?php if (lang('CONF_DOWNLOADS_BOOL') == 'ON'): ?>
						<li id="downloads" class="nav-item-config">
							<a href="javascript:">
								<img class="icon-config" src="<?= $this->asset->insertFile('/icon-download.svg', 'images/icons');?>">
								<h5><?= lang('GEN_BTN_DOWNLOADS') ?></h5>
								<div class="box up left">
									<img src="<?= $this->asset->insertFile('/icon-download.svg', 'images/icons');?>" class="bg ">
									<h4><?= lang('GEN_BTN_DOWNLOADS') ?></h4>
								</div>
							</a>
						</li>
						<?php endif; ?>

					</ul>
				</nav>
			</div>
			<div class="flex flex-auto flex-column" style="display:none">
				<div id="userView" style="display:none">
					<?php if (lang('CONF_CHANGE_EMAIL_BOOL') == 'ON'): ?>
					<div class="flex mb-1 mx-4 flex-column">
						<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SETTINGS_USER') ?></span>
						<div class="px-5">
							<div class="container">
								<div class="row my-2">
									<div class="form-group col-12">
										<span aria-hidden="true" class="icon icon-user"></span>
										<span id="userName"><?= $fullName ?></span>
									</div>
								</div>
								<div class="row mb-2">
									<div class="form-group col-3">
										<label for="firstName" id="firstName">Nombre</label>
										<span id="firstNameUser" class="form-control px-1" readonly="readonly"><?= $name ?></span>
									</div>

									<div class="form-group col-3">
										<label for="lastName" id="lastName">Apellido</label>
										<span id="firstNameUser" class="form-control px-1" readonly="readonly"><?= $firstName ?></span>
									</div>

									<div class="form-group col-3">
										<label for="ocupation" id="ocupation">Cargo</label>
										<span id="ocupationUser" class="form-control px-1" readonly="readonly"><?= $position ?></span>
									</div>
									<div class="form-group col-3">
										<label for="area" id="area">Área</label>
										<span id="areaUser" class="form-control px-1" readonly="readonly"><?= $area ?></span>
									</div>
								</div>

								<form id="formChangeEmail">
									<div class="row">
										<div class="form-group col-6 col-lg-5 col-xl-6">
											<label for="email" id="email">Correo</label>
											<input type="email" class="form-control" id="currentEmail" name="email" value="<?= $email ?>"
												onkeyup="this.value = this.value.toLowerCase();">
											<div class="help-block"></div>
										</div>
									</div>
									<div id="loader" class="none">
										<span class="spinner-border secondary" role="status" aria-hidden="true"></span>
									</div>
									<div class="row">
										<div class="col-6 flex justify-end">
											<button id="btnChangeEmail" class="btn btn-primary btn-small " style="width: 159px" type="submit">Guardar cambios</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<?php endif; ?>
					<?php if (lang('CONF_CHANGE_PASSWORD_BOOL') == 'ON'): ?>
					<div class="flex flex-auto flex-column">
						<div class="flex mb-5 mx-4 flex-column ">
							<span class="line-text slide-slow flex mb-2 h4 semibold primary"><?= lang('GEN_CHANGE_PASS') ?>
								<i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
							</span>
							<div class="section my-2 px-5">
								<form id="formChangePass">
									<div class="container">
										<div class="row">
											<div class="col-6">
												<div class="row">
													<div class="form-group col-12 col-lg-12">
														<label for="currentPass">Contraseña actual</label>
														<div class="input-group">
															<input id="currentPass" class="form-control pwd-input" type="password" name="current-pass" required>
															<div class="input-group-append">
																<span id="pwd-addon" class="input-group-text pwd-action" title="Clic aquí para mostrar/ocultar contraseña"><i
																		class="icon-view mr-0"></i></span>
															</div>
														</div>
														<div class="help-block"></div>
													</div>
													<div class="form-group col-12 col-lg-6">
														<label for="newPass">Nueva Contraseña</label>
														<div class="input-group">
															<input id="newPass" class="form-control pwd-input" type="password" name="new-pass" required>
															<div class="input-group-append">
																<span id="pwd-addon" class="input-group-text pwd-action" title="Clic aquí para mostrar/ocultar contraseña"><i
																		class="icon-view mr-0"></i></span>
															</div>
														</div>
														<div class="help-block"></div>
													</div>
													<div class="form-group col-12 col-lg-6">
														<label for="confirmPass">Confirmar Contraseña</label>
														<div class="input-group">
															<input id="confirmPass" class="form-control pwd-input" type="password" name="confirm-pass" required>
															<div class="input-group-append">
																<span id="pwd-addon" class="input-group-text pwd-action" title="Clic aquí para mostrar/ocultar contraseña"><i
																		class="icon-view mr-0"></i></span>
															</div>
														</div>
														<div class="help-block"></div>
													</div>
												</div>
											</div>

											<div class="cover-spin" id=""></div>
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
														<li id="special" class="pwd-rules-item rule-invalid">Al menos un <strong>caracter
																especial</strong><br>(ej: ! @ ? + - . , #)</li>
														<li id="consecutive" class="pwd-rules-item rule-invalid">No debe tener más de 2
															<strong>caracteres</strong> iguales consecutivos</li>
													</ul>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-6 flex justify-end">
												<button id="btnChangePass" class="btn btn-primary btn-small " style="width: 159px" type="button">Guardar cambios</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<div id="enterpriseView" style="display:none">
					<?php if (lang('CONF_CHANGE_TELEPHONES_BOOL') == 'ON'): ?>
					<div class="flex mb-1 mx-4 flex-column">
						<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SETTINGS_ENTERPRISE') ?></span>
						<div class="px-5">
							<div class="container">
								<div class="row mb-2">
									<div class="form-group col-12 col-lg-8 col-xl-6">
										<label class="mt-1">Empresa</label>
										<form id="enterprise-widget-form" method="POST">
											<select id="enterprise-select" class="select-box custom-select mt-3 mb-4 h6 w-100">
												<option selected disabled><?= lang('GEN_SELECT_ENTERPRISE'); ?></option>
												<?php $numpos = 0; foreach($enterpriseList1 AS $enterprise) : ?>
												<option name="<?= $enterprise->acnomcia; ?>" acrif="<?= $enterprise->acrif ?>" numpos="<?= $numpos; ?>"
													razonSocial="<?= $enterprise->acrazonsocial; ?>" contacto="<?= $enterprise->acpercontac ?>"
													ubicacion="<?= $enterprise->acdirenvio ?>" fact="<?= $enterprise->acdirenvio ?>" tel1="<?= $enterprise->actel; ?>"
													tel2="<?= $enterprise->actel2; ?>" tel3="<?= $enterprise->actel3; ?>">
													<?= $enterprise->acnomcia; ?>
												</option>
												<?php $numpos++; endforeach; ?>
											</select>
										</form>
									</div>
								</div>
								<div class="hide-out hide">
									<div id="pre-loader" class="mt-2 mx-auto flex justify-center">
										<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
									</div>
								</div>
								<div id="completeForm" class="hide">

									<div class="row" id="blockEnterprice">
										<div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
											<label for="idNumber" id="idNumber">Nro. identificador</label>
											<span id="idNumberUser" class="form-control px-1" readonly="readonly"></span>
										</div>

										<div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
											<label for="compName" id="compName">Nombre</label>
											<span id="compNameUser" class="form-control px-1" readonly="readonly"></span>
										</div>

										<div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
											<label for="busiName" id="busiName">Razón social</label>
											<span id="busiNameUser" class="form-control px-1" readonly="readonly"></span>
										</div>

										<div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
											<label for="contact" id="contact">Contacto</label>
											<span id="contactUser" class="form-control px-1" readonly="readonly"></span>
										</div>

										<div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
											<label for="address" id="address">Dirección</label>
											<span id="addressUser" class="form-control px-1" readonly="readonly"></span>
										</div>

										<div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
											<label for="TempAddress" id="TempAddress">Dirección de facturación</label>
											<span id="TempAddressUser" class="form-control px-1" readonly="readonly"></span>
										</div>
									</div>

									<?php if (lang('CONF_TEL_BOOL') == 'ON'): ?>
									<form id="formChangeTelephones">
										<div class="row">
											<div class="form-group mb-3 col-6 col-lg-4 col-xl-4" hidden>
												<label for="acrif">Teléfono 1</label>
												<input id="acrif" name="phone" class="form-control " type="text" />
												<div class="help-block"></div>
											</div>
											<div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
												<label for="tlf1">Teléfono 1</label>
												<input id="tlf1" name="tlf1" class="form-control" required />
												<div class="help-block"></div>
											</div>

											<div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
												<label for="tlf2">Teléfono 2</label>
												<input id="tlf2" name="id-number1" class="form-control" />
												<div class="help-block"></div>
											</div>
											<div class="form-group mb-3 col-6 col-lg-4 col-xl-4">
												<label for="tlf3">Teléfono 3</label>
												<input id="tlf3" name="id-number1" class="form-control" />
												<div class="help-block"></div>
											</div>
										</div>

										<div class="row">
											<div class="flex mb-2 justify-end col-12">
												<button id="btnChangeTelephones" style="width: 159px" class="btn btn-primary btn-small " type="submit">Guardar
													cambios</button>
											</div>
										</div>
									</form>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>

					<div class="flex flex-auto flex-column">
						<div class="flex flex-column mx-4 mb-5">
							<?php if (lang('CONF_ADD_CONTACT_BOOL') == 'ON'): ?>
							<span class="line-text slide-slow flex mb-2 h4 semibold primary"><?= lang('GEN_ADD_CONTACT') ?>
								<i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
							</span>
							<div class="section my-2 px-5">
								<form id="formAddContact">
									<div class="container">
										<div class="row">
											<div class="form-group mb-3 col-6 col-lg-4 col-xl-4" hidden>
												<label for="contUser"></label>
												<input id="contUser" name="contUser" type="text" class="form-control" />
												<div class="help-block"></div>
											</div>
											<div class="form-group mb-3 col-6 col-lg-4 col-xl-4" hidden>
												<label for="contAcrif"></label>
												<input id="contAcrif" name="contAcrif" type="text" class="form-control" />
												<div class="help-block"></div>
											</div>
											<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
												<label for="contName">Nombre</label>
												<input id="contName" name="contName" type="text" class="form-control" />
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
												<label for="contType">Empresa</label>
												<select class="select-box custom-select mb-3 h6 w-100" name="contType" id="contType">
													<option selected disabled>Seleccionar</option>
													<option value="F">Contacto Administracion y finanzas</option>
													<option value="H">Contacto RRHH</option>
													<option value="C">Contacto</option>
												</select>
											</div>
										</div>

										<div class="row flex mb-4 mt-2 justify-end items-center form-group">
											<div class="col-6 col-lg-4 col-xl-3 input-group">
												<label for="contPass"></label>
												<input id="contPass" class="form-control pwd-input" type="password" name="Ingresa tu contraseña"
													placeholder="Ingresa tu contraseña">
												<div class="input-group-append">
													<span id="pwd-addon" class="input-group-text pwd-action" title="Clic aquí para mostrar/ocultar contraseña"><i
															class="icon-view mr-0"></i></span>
												</div>
											</div>
											<div class="col-3 col-lg-2 col-xl-auto">
												<button class="btn btn-primary btn-small flex mx-auto " style="width: 159px" id="btnLimpiar" type="button">Limpiar</button>
											</div>
											<div class="col-3 col-lg-2 col-xl-auto">
												<button class="btn btn-primary btn-small flex mx-auto " style="width: 159px" id="btnAddContact" type="submit">Agregar</button>
											</div>
										</div>
									</div>
								</form>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div id="branchView" style="display:none">
					<?php if (lang('CONF_ADD_FILE_BOOL') == 'ON'): ?>
					<div class="flex mb-1 mx-4 flex-column">
						<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SETTINGS_BRANCH') ?></span>
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
					<?php endif; ?>
					<?php if (lang('CONF_ADD_BRANCH_BOOL') == 'ON'): ?>
					<div class="flex flex-auto flex-column">
						<div class="flex flex-column mx-4 mb-5">
							<span class="line-text slide-slow flex mb-2 h4 semibold primary"><?= lang('GEN_ADD_BRANCH') ?>
								<i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
							</span>
							<div class="section my-2 px-5">
								<form method="post">
									<div class="container">
										<div class="row">
											<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
												<label for="branchName">Nombre</label>
												<input id="branchName" name="branchName" type="text" class="form-control" value="" placeholder="Nombre de la empresa" />
												<div class="help-block"></div>
											</div>

											<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
												<label for="zoneName">Zona</label>
												<input id="zoneName" name="zoneName" type="text" class="form-control" value="" placeholder="Punto de referencia" />
												<div class="help-block"></div>
											</div>

											<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
												<label for="address1">Dirección 1</label>
												<input id="address1" name="address1" type="text" class="form-control" value="" placeholder="Dirección principal" />
												<div class="help-block"></div>
											</div>

											<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
												<label for="address2">Dirección 2</label>
												<input id="address2" name="address1" type="text" class="form-control" value="" placeholder="Dirección alternativa" />
												<div class="help-block"></div>
											</div>

											<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
												<label for="address3">Dirección 3</label>
												<input id="address3" name="address1" type="text" class="form-control" value="" placeholder="Dirección alternativa" />
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
												<input id="areaCode" name="areaCode" type="text" class="form-control" value="" placeholder="Código de área" />
												<div class="help-block"></div>
											</div>

											<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
												<label for="phone">Teléfono</label>
												<input id="phone" name="phone" type="text" class="form-control" value="" placeholder="Teléfono" />
												<div class="help-block"></div>
											</div>

											<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
												<label for="contact">Nombre del contacto</label>
												<input id="contact" name="contact" type="text" class="form-control" value="" placeholder="Nombre del contacto" />
												<div class="help-block"></div>
											</div>

											<div class="form-group mb-1 col-6 col-lg-4 col-xl-4">
												<label for="branchCode">Código de sucursal</label>
												<input id="branchCode" name="branchCode" type="text" class="form-control" value="" placeholder="Código de la sucursal" />
												<div class="help-block"></div>
											</div>
										</div>

										<div class="row flex mb-4 mt-2 pl-5 justify-end items-center form-group">
											<div class="col-7 col-lg-4 col-xl-3 input-group">
												<input id="password1" class="form-control pwd-input" type="password" name="Ingresa tu contraseña"
													placeholder="Ingresa tu contraseña">
												<div class="input-group-append">
													<span id="pwd-addon" class="input-group-text pwd-action" title="Clic aquí para mostrar/ocultar contraseña"><i
															class="icon-view mr-0"></i></span>
												</div>
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
					<?php endif; ?>
				</div>
				<div id="downloadsView" style="display:none">
					<div class="flex mb-1 mx-4 flex-column">
						<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_BTN_DOWNLOADS') ?></span>
						<div class="px-5">
							<div class="container">
								<?php if (lang('CONF_MANUAL_BOOL') == 'ON'): ?>
								<div class="my-2 tertiary h4 semibold">
									<span><?= lang('GEN_MANUALS') ?></span>
								</div>
								<div class="row">
									<?php if (lang('CONF_PDF_FILE') != ''): ?>
									<?php foreach(lang('CONF_PDF_FILE') as $value): ?>
									<div class="mb-3 col-auto col-lg-6 col-xl-5">
										<a href="<?= $this->asset->insertFile($value[0], 'statics', $countryUri); ?>" download>
											<div class="files btn-link flex items-center">
												<div class="file">
													<img src="<?= $this->asset->insertFile(lang('CONF_PDF_ICON'), 'images/icons');?>" />
												</div>
												<span class="ml-2 flex justify-center"><?= $value[1]?></span>
											</div>
										</a>
									</div>
									<?php endforeach; ?>
									<?php endif; ?>
								</div>
								<?php endif; ?>



								<?php if (lang('CONF_VIDEO_BOOL') == 'ON'):?>
								<?php foreach(lang('CONF_MP4_VIDEO') as $value): ?>
								<div class="container">
									<div class="row">
										<div class="col-sm-12 col-lg-11 col-xl-12 py-2">
											<div class="manual-video">
												<video controls preload>
													<source src="<?= $this->asset->insertFile($value, 'statics', $countryUri);?>" type="video/mp4">
												</video>
											</div>
										</div>
									</div>
								</div>
								<?php endforeach; ?>
								<?php endif; ?>


								<?php if (lang('CONF_APPS_BOOL') == 'ON'): ?>
								<div class="my-2 tertiary h4 semibold">
									<span><?= lang('CONF_APPLICATIONS') ?></span>
								</div>
								<div class="row">
									<?php foreach(lang('CONF_ZIP_FILE') as $value): ?>
									<div class="mb-3 col-auto col-lg-6 col-xl-5">
										<a href="<?= $this->asset->insertFile($value[0], 'statics', $countryUri); ?>" download>
											<div class="files btn-link flex items-center">
												<div class="file">
													<img src="<?= $this->asset->insertFile(lang('CONF_ZIP_ICON'), 'images/icons');?>" />
												</div>
												<span class="ml-2 flex justify-center"><?= $value[1] ?></span>
											</div>
										</a>
									</div>
									<?php endforeach; ?>
								</div>
								<?php endif; ?>


								<?php if (lang('CONF_FILES_MANAGMENT_BOOL') == 'ON'): ?>
								<div class="my-2 tertiary h4 semibold">
									<span><?= lang('CONF_FILE') ?></span>
								</div>
								<div class="row">
									<?php foreach(lang('CONF_RAR_FILE') as $value): ?>
									<div class="form-group col-auto mb-3 col-xl-5">
										<a href="<?= $this->asset->insertFile($value[0], 'statics', $countryUri); ?>" download>
											<div class="files btn-link flex items-center">
												<div class="file">
													<img src=<?= $this->asset->insertFile(lang('CONF_RAR_ICON'), 'images/icons');?> />
												</div>
												<span class="ml-2 flex justify-center"><?= $value[1]  ?></span>
											</div>
										</a>
									</div>
									<?php endforeach; ?>
								</div>
								<?php endif; ?>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
