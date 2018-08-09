<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div id="content-products">

	<h1><?php echo $action; ?></h1>
	<h2 class="title-marca">
			<?php echo ucwords(mb_strtolower($programa)); ?>
	</h2>
	<ol class="breadcrumb">

		<li>
			<a href="<?php echo base_url($pais . '/dashboard'); ?>" rel="start">
				<?php echo lang('BREADCRUMB_INICIO'); ?>
			</a>
		</li>
		/
		<li>
			<a href="<?php echo base_url($pais . '/dashboard'); ?>" rel="section">
				<?php echo lang('BREADCRUMB_EMPRESAS'); ?>
			</a>
		</li>
		/
		<li>
			<a href="<?php echo base_url($pais . '/dashboard/productos'); ?>" rel="section">
				<?php echo lang('BREADCRUMB_PRODUCTOS'); ?>
			</a>
		</li>
		/
		<li>
			<a class="cursor-default">
				<?php echo lang('BREADCRUMB_CONSULTAS'); ?>
			</a>
		</li>
		/
		<li class="breadcrumb-item-current">
			<a class="cursor-default">
				<?php echo lang('BREADCRUMB_LOTES_POR_FACTURAR'); ?>
			</a>
		</li>

	</ol>

</div>

<div class="container">

	<div class="container-header">
		<span aria-hidden="true" class="icon icon-list" data-icon="&#xe046;"></span>
		<?= lang('TITULO_LOTES_POR_FACTURAR'); ?>
	</div>

	<div class="container-body">
		<div id="loading" style="text-align:center; margin-top: 90px;">
			<?= insert_image_cdn("loading.gif"); ?>
		</div>
		<div id="display-table"style="display:none">
			<table id="novo-table" class="hover cell-border" style="width: 635px;">
				<thead>
					<tr>
						<th class="thead" style="width: 86px;">Tipo de lote</th>
						<th class="thead" style="width: 60px;">Cantidad de lotes</th>
						<th class="thead" style="width: 60px;">Total de registros</th>
						<th class="thead" style="width: 84px;">Precio unitario Bs.</th>
						<th class="thead" style="width: 89px;">Total Bs.</th>
						<th class="thead" style="width: 50px;">Ver listado</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="item-td">Emisión</td>
						<td>2</td>
						<td>10</td>
						<td>45.000,00</td>
						<td>450.000,00</td>
						<td class="os-info-show">
							<a>
								<span aria-hidden="true" class="icon icon-list"></span>
							</a>
							<span class="show-table" style="display: none">
								<div class="table">
									<div class="heading">
										<div class="cell"><span>Nro de lote</span></div>
										<div class="cell"><span>Fecha</span></div>
										<div class="cell"><span>Cant</span></div>
										<div class="cell"><span>Estatus</span></div>
										<div class="cell"><span>Monto de comisión</span></div>
										<div class="cell"><span>Monto total</span></div>
									</div>
									<div class="row">
										<div class="cell"><span><a href="">12011199</a></span></div>
										<div class="cell"><span>28/02/2018</span></div>
										<div class="cell"><span>1</span></div>
										<div class="cell"><span>Procesado</span></div>
										<div class="cell"><span>45000</span></div>
										<div class="cell"><span>4500000</span></div>
									</div>
									<div class="row">
										<div class="cell"><span><a href="">12011199</a></span></div>
										<div class="cell"><span>28/02/2018</span></div>
										<div class="cell"><span>1</span></div>
										<div class="cell"><span>Procesado</span></div>
										<div class="cell"><span>45000</span></div>
										<div class="cell"><span>4500000</span></div>
									</div>
								</div>
							</span>
						</td>
					</tr>
					<tr>
					<td class="item-td">Reposición de clave</td>
						<td>2</td>
						<td>10</td>
						<td>45.000,00</td>
						<td>450.000,00</td>
						<td class="os-info-show">
							<a>
								<span aria-hidden="true" class="icon icon-list"></span>
							</a>
							<span class="show-table" style="display: none">
								<div class="table">
									<div class="heading">
										<div class="cell"><span>Nro de lote</span></div>
										<div class="cell"><span>Fecha</span></div>
										<div class="cell"><span>Cant</span></div>
										<div class="cell"><span>Estatus</span></div>
										<div class="cell"><span>Monto de comisión</span></div>
										<div class="cell"><span>Monto total</span></div>
									</div>
									<div class="row">
										<div class="cell"><span><a href="">12011199</a></span></div>
										<div class="cell"><span>28/02/2018</span></div>
										<div class="cell"><span>1</span></div>
										<div class="cell"><span>Procesado</span></div>
										<div class="cell"><span>45000</span></div>
										<div class="cell"><span>4500000</span></div>
									</div>
									<div class="row">
										<div class="cell"><span><a href="">12011199</a></span></div>
										<div class="cell"><span>28/02/2018</span></div>
										<div class="cell"><span>1</span></div>
										<div class="cell"><span>Procesado</span></div>
										<div class="cell"><span>45000</span></div>
										<div class="cell"><span>4500000</span></div>
									</div>
								</div>
							</span>
						</td>
					</tr>
					<tr>
					<td class="item-td">Reposición de tarjeta</td>
						<td>2</td>
						<td>10</td>
						<td>45.000,00</td>
						<td>450.000,00</td>
						<td class="os-info-show">
							<a>
								<span aria-hidden="true" class="icon icon-list"></span>
							</a>
							<span class="show-table" style="display: none">
								<div class="table">
									<div class="heading">
										<div class="cell"><span>Nro de lote</span></div>
										<div class="cell"><span>Fecha</span></div>
										<div class="cell"><span>Cant</span></div>
										<div class="cell"><span>Estatus</span></div>
										<div class="cell"><span>Monto de comisión</span></div>
										<div class="cell"><span>Monto total</span></div>
									</div>
									<div class="row">
										<div class="cell"><span><a href="">12011199</a></span></div>
										<div class="cell"><span>28/02/2018</span></div>
										<div class="cell"><span>1</span></div>
										<div class="cell"><span>Procesado</span></div>
										<div class="cell"><span>45000</span></div>
										<div class="cell"><span>4500000</span></div>
									</div>
									<div class="row">
										<div class="cell"><span><a href="">12011199</a></span></div>
										<div class="cell"><span>28/02/2018</span></div>
										<div class="cell"><span>1</span></div>
										<div class="cell"><span>Procesado</span></div>
										<div class="cell"><span>45000</span></div>
										<div class="cell"><span>4500000</span></div>
									</div>
								</div>
							</span>
						</td>
					</tr>
					<tr>
					<td class="item-td">Renovación</td>
						<td>2</td>
						<td>10</td>
						<td>45.000,00</td>
						<td>450.000,00</td>
						<td class="os-info-show">
							<a>
								<span aria-hidden="true" class="icon icon-list"></span>
							</a>
							<span class="show-table" style="display: none">
								<div class="table">
									<div class="heading">
										<div class="cell"><span>Nro de lote</span></div>
										<div class="cell"><span>Fecha</span></div>
										<div class="cell"><span>Cant</span></div>
										<div class="cell"><span>Estatus</span></div>
										<div class="cell"><span>Monto de comisión</span></div>
										<div class="cell"><span>Monto total</span></div>
									</div>
									<div class="row">
										<div class="cell"><span><a href="">12011199</a></span></div>
										<div class="cell"><span>28/02/2018</span></div>
										<div class="cell"><span>1</span></div>
										<div class="cell"><span>Procesado</span></div>
										<div class="cell"><span>45000</span></div>
										<div class="cell"><span>4500000</span></div>
									</div>
									<div class="row">
										<div class="cell"><span><a href="">12011199</a></span></div>
										<div class="cell"><span>28/02/2018</span></div>
										<div class="cell"><span>1</span></div>
										<div class="cell"><span>Procesado</span></div>
										<div class="cell"><span>45000</span></div>
										<div class="cell"><span>4500000</span></div>
									</div>
								</div>
							</span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="contanier-footer">
	</div>
</div>
