
<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="pt-3 px-5 pb-5">
  <h1 class="primary h3 regular inline">Detalles del lote</h1>
  <span class="ml-2 regular tertiary"> Prepago B-Bogotá</span>
	<div class="flex mb-2 items-center">
	<div class="flex tertiary">
		<nav class="main-nav nav-inferior">
			<ul class="mb-0 h6 light tertiary list-style-none list-inline">
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>">Empresas</a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>">Productos</a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>">Detalle del producto</a></li> /
				<li class="inline"><a class="tertiary big-modal" href="<?= base_url('consulta-orden-de-servicio') ?>">Órdenes de servicio</a></li>/
				<li class="inline"><a class="tertiary not-pointer" href="javascript:">Detalles del lote</a></li>
			</ul>
		</nav>
	</div>
</div>
  <div class="flex mt-1 mb-5 bg-color flex-nowrap justify-between">
    <div class="flex flex-auto flex-column">
      <div class="flex flex-column">
        <span class="line-text mb-2 h4 semibold primary">Detalles</span>
        <div class="row mb-2 px-5">
          <div class="form-group mb-3 col-4">
            <label for="confirmNIT" id="confirmNIT">NIT</label>
            <span id="confirmNIT" class="form-control px-1" readonly="readonly"><?= $detail->detail->acrif; ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="confirmName" id="confirmName">Nombre de la empresa</label>
            <span id="confirmName" class="form-control px-1" readonly="readonly"><?= $detail->detail->acnomcia; ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="typeLot" id="typeLot">Tipo de lote</label>
            <span id="typeLotName" class="form-control px-1 bold pink-salmon" readonly="readonly"><?= $detail->detail->acnombre; ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="lot" id="lot">Lote nro.</label>
            <span id="numLot" class="form-control px-1" readonly="readonly"><?= $detail->detail->acnumlote; ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="regNumber" id="regNumber">Cantidad de registros</label>
            <span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $detail->detail->ncantregs; ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="regNumber" id="regNumber">Usuario carga</label>
            <span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $detail->detail->accodusuarioc; ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="regNumber" id="regNumber">Fecha de carga</label>
            <span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $detail->detail->dtfechorcarga; ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="regNumber" id="regNumber">Estatus</label>
            <span id="amountNumber" class="form-control px-1 " readonly="readonly"><?= $detail->detail->status; ?></span>
          </div>

          <div class="form-group mb-3 col-4">
            <label for="amount" id="amount">Monto total</label>
            <span id="totalAmount" class="form-control px-1" readonly="readonly"><?= amount_format($detail->detail->nmonto); ?></span>
          </div>
        </div>
      </div>

	    <div id="pre-loader" class="mt-2 mx-auto">
		<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
		</div>
		<div class="w-100 hide-out hide">
			<div class="flex pb-5 flex-column">
				<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SERVICE_ORDERS_TITLE'); ?></span>
				<div class="center mx-1">
			<div class="flex justify-end items-center">
            <div class="mr-3 py-1">
              <a id="downXLS" class="btn px-1" title="Exportar a EXCEL" data-toggle="tooltip">
                <i class="icon icon-file-excel" aria-hidden="true"></i>
              </a>
              <a id="downPDF" class="btn px-1" title="Exportar a PDF" data-toggle="tooltip">
                <i class="icon icon-file-pdf" aria-hidden="true"></i>
              </a>
            </div>
          </div>
					<table id="authLotDetail" class="cell-border h6 display">
						<thead class="bg-primary secondary regular">
							<tr>
								<th>NIT</th>
								<th>Cuenta</th>
								<th>Monto</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($detail->detail->registrosLoteRecarga AS $lotes): ?>
						  <tr>
							<td><?= $lotes->id_ext_per; ?></td>
							<td><?= substr_replace($lotes->nro_cuenta,'*************',0,-4); ?></td>
							<td><?= $lotes->monto; ?></td>
						  </tr>
						<?php endforeach; ?>
						</tbody>
					</table>
					<div class="line my-2"></div>
				</div>

				<div class="my-5 py-4 center none">
					<span class="h4">No fue posible obtener las ordenes de servicio asociadas</span>
				</div>

				<div class="flex flex-column mb-4 mt-4 px-5 justify-center items-center">
					<div class="flex flex-row">
						<div class="mb-3 mr-4">
							<a href="<?= base_url('consulta-orden-de-servicio') ?>" class="btn btn-link btn-small"><?= lang('GEN_BTN_BACK') ?></a>
						</div>
					</div>
				</div>

			</div>
		</div>

    </div>
	<?php if($widget): ?>
	<?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
	<?php endif; ?>
  </div>
</div>
<form id='exportTo' method='post'>
 <input type='hidden' id='data_lote' name='data_lote' value="<?= $detail->detail->acidlote; ?>"/>
 <input type='hidden' id='file_type' name='file_type' value=""/>
</form>
