<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="pt-3 px-5 pb-5">
	<h1 class="primary h3 regular inline">Carga de lotes</h1>
	<span class="ml-2 regular tertiary"> Prepago B-Bogotá</span>
	<div class="mb-2 flex items-center">
		<div class="flex tertiary">
			<nav class="main-nav nav-inferior">
				<ul class="mb-0 h6 light tertiary list-style-none list-inline">
					<li class="inline"><a class="tertiary" href="<?= base_url('empresas') ?>">Empresas</a></li> /
					<li class="inline"><a class="tertiary" href="<?= base_url('productos') ?>">Productos</a></li> /
					<li class="inline"><a class="tertiary" href="<?= base_url('detalle-producto') ?>">Detalle del producto</a></li> /
					<li class="inline"><a class="tertiary" href="#">Cargar lotes</a></li>
				</ul>
			</nav>
		</div>
	</div>
	<div class="flex mt-1 mb-5 bg-color flex-wrap justify-between">
		<div class="flex flex-auto flex-column">
			<div class="flex flex-column">
				<span class="line-text mb-2 h4 semibold primary">Nuevos lotes</span>
				<form method="post">
					<div class="flex px-5 pb-4 items-center row">
						<div class="col">
							<label class="mt-1 h6" or="">Tipo de Lote</label>
							<select class="select-box custom-select mb-3 h6 w-100">
								<option selected disabled>Seleccionar</option>
								<option>Option 1</option>
								<option>Option 2</option>
								<option>Option 3</option>
							</select>
						</div>
						<div class="col-6 bg-color mt-1">
							<input type="file" name="file" id="file" class="input-file">
							<label for="file" class="label-file js-label-file">
								<i class="icon icon-upload mr-1 pr-3 right"></i>
								<span class="js-file-name h6 regular">Clic aquí para seleccionar el archivo de Lote.</span>
							</label>
						</div>
						<div class="col mt-1">
							<button class="btn btn-primary btn-small flex mx-auto">
								Seleccionar
							</button>
						</div>
					</div>
				</form>
			</div>
			<div class="flex flex-column">
				<span class="line-text mb-2 h4 semibold primary">Lotes pendientes</span>
				<div class="center mx-1">
					<table id="pendingLots" class="cell-border h6 display">
						<thead class="regular secondary bg-primary">
							<tr>
								<th>Nro. Lote</th>
								<th>Nombre</th>
								<th>Fecha de carga</th>
								<th>Estatus</th>
								<th>Opciones</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>19062016</td>
								<td>919062024.txt</td>
								<td>20/06/2019 - 09:35:58</td>
								<td>
									<div class="status-pr flex items-center justify-center">
										<div class="icon-circle bg-vista-blue" alt=""></div>
										<span class="pl-1 uppercase">Válido</span>
									</div>
								</td>
								<td>
									<button class="btn pr-1" title="" data-toggle="tooltip">
										<i class="icon icon-ok" aria-hidden="true"></i>
									</button>
									<button class="btn pl-0" title="Eliminar" data-toggle="tooltip">
										<i class="icon icon-remove mr-1" aria-hidden="true"></i>
									</button>
								</td>
							</tr>
							<tr>
								<td>20062016</td>
								<td>020642026.txt</td>
								<td>20/07/2019 - 10:35:58</td>
								<td>
									<div class="status-pr flex items-center justify-center">
										<div class="icon-circle bg-trikemaster" alt=""></div>
										<span class="pl-1 uppercase">Válido</span>
									</div>
								</td>
								<td>
									<button class="btn px-0" title="" data-toggle="tooltip">
										<i class="icon icon-ok" aria-hidden="true"></i>
									</button>
									<button class="btn px-1" title="Ver" data-toggle="tooltip">
										<i class="icon icon-find mr-1" aria-hidden="true"></i>
									</button>
									<button class="btn px-0" title="Eliminar" data-toggle="tooltip">
										<i class="icon icon-remove mr-1" aria-hidden="true"></i>
									</button>
								</td>
							</tr>
							<tr>
								<td>20062016</td>
								<td>545158421.txt</td>
								<td>21/07/2019 - 11:35:58</td>
								<td>
									<div class="flex items-center justify-center">
										<div class="icon-circle bg-pink-salmon" alt=""></div>
										<span class="pl-1 uppercase">Con errores</span>
									</div>
								</td>
								<td>
									<button class="btn pr-1" title="Ver" data-toggle="tooltip">
										<i class="icon icon-find mr-1" aria-hidden="true"></i>
									</button>
									<button class="btn pl-0" title="Eliminar" data-toggle="tooltip">
										<i class="icon icon-remove mr-1" aria-hidden="true"></i>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="mx-3 h3">
						<div class="flex mt-4 items-center">
							<div class="icon-square bg-vista-blue" alt=""></div>
							<span class="pl-1 h6">Todos los registros serán procesados</span>
						</div>
						<div class="flex mt-2 items-center">
							<div class="icon-square bg-trikemaster" alt=""></div>
							<span class="pl-1 h6">Existen registros que no serán procesados</span>
						</div>
						<div class="flex mt-2 items-center">
							<div class="icon-square bg-pink-salmon" alt=""></div>
							<span class="pl-1 h6">Ningún registro será procesado</span>
						</div>
					</div>
				</div>
				<div class="my-5 py-4 center none">
					<span class="h4">No tiene lotes pendientes</span>
				</div>
			</div>
		</div>
		<?php if($widget): ?>
		<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
		<?php endif; ?>
	</div>
</div>
