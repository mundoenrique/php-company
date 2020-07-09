<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<h1 class="primary h3 regular inline"><?= lang('GEN_MENU_SERV_TRANS_LIMITS'); ?></h1>
<span class="ml-2 regular tertiary"><?= $productName ?></span>
<div class="mb-2 flex items-center">
    <div class="flex tertiary">
        <nav class="main-nav nav-inferior">
            <ul class="mb-0 h6 light tertiary list-style-none list-inline">
                <li class="inline"><a class="tertiary big-modal" href="<?= base_url('empresas') ?>"><?= lang('GEN_MENU_ENTERPRISE') ?></a></li> /
                <li class="inline"><a class="tertiary big-modal" href="<?= base_url('productos') ?>"><?= lang('GEN_PRODUCTS') ?></a></li> /
                <li class="inline"><a class="tertiary big-modal" href="<?= base_url('detalle-producto') ?>"><?= lang('PRODUCTS_DETAIL_TITLE') ?></a></li> /
                <li class="inline"><a class="tertiary not-pointer" href="javascript:"><?= lang('GEN_MENU_SERVICES'); ?></a></li>
            </ul>
        </nav>
    </div>
</div>
<div class="flex mt-1 bg-color flex-nowrap justify-between">
	<div id="pre-loader" class="mt-2 mx-auto">
			<span class="spinner-border spinner-border-lg" role="status" aria-hidden="true"></span>
	</div>
    <div class="w-100 hide-out hide">
			<div class="flex flex-auto flex-column <?= $widget ? '' : 'max-width-6'; ?>">
				<div class="search-criteria-order flex pb-3 flex-column w-100">
					<span class="line-text mb-2 h4 semibold primary"><?= lang('GEN_SEARCH_CRITERIA'); ?></span>
					<div class="flex my-2 px-5">
						<form method="post" class="w-100">
							<div class="row flex justify-between">
								<div class="form-group col-4 col-xl-4">
									<label for="idNumberP"><?= lang('GEN_TABLE_DNI'); ?></label>
									<input id="idNumberP" name="idNumberP" class="form-control h5 select-group" type="text">
									<div class="help-block"></div>
								</div>
								<div class="form-group col-4 col-xl-4">
									<label for="cardNumberP"><?= lang('GEN_CARD_NUMBER'); ?></label>
									<input id="cardNumberP" name="cardNumberP" class="form-control h5 select-group" type="text">
									<div class="help-block"></div>
								</div>
								<div class="flex items-center justify-end col-3">
									<button type="submit" id="card-holder-btn" class="btn btn-primary btn-small btn-loading">
										<?= lang('GEN_BTN_SEARCH'); ?>
									</button>
								</div>
							</div>
						</form>
					</div>
					<div class="line mb-2"></div>
				</div>
				<div class="flex pb-5 px-2 flex-column">
					<div class="flex flex-column">
						<div class="flex light items-center line-text mb-5">
							<div class="flex tertiary">
								<span class="inline h4 semibold primary">Resultados</span>
							</div>
							<div class="flex h6 flex-auto justify-end">
								<span>Fecha de actualización: 3/07/2020 5:36 PM</span>
							</div>
						</div>
						<div class="row flex justify-between my-3">
							<div class="form-group col-4 center">
								<p class="mr-5 h5 semibold tertiary"><?= lang('GEN_CARD_NUMBER'); ?>: <span class="light text">**********270300</span></p>
							</div>
							<div class="form-group col-4 center">
								<p class="mr-5 h5 semibold tertiary"><?= lang('GEN_TABLE_NAME'); ?>: <span class="light text">Jhonatan Ortiz</span></p>
							</div>
							<div class="form-group col-4 center">
								<p class="mr-5 h5 semibold tertiary"><?= lang('GEN_TABLE_DNI'); ?>: <span class="light text">1803752318</span></p>
							</div>
						</div>
					</div>
					<div class="flex mb-5 flex-column">
						<span class="line-text slide-slow flex mb-2 h4 semibold primary">Con tarjeta presente
							<i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
						</span>
						<div class="section my-2 px-5">
							<form id="">
								<div class="container">
									<div class="row">
										<div class="col-10 bolck mx-auto">
											<div class="row">
												<div class="form-group col-12 col-lg-4">
													<label class ="pr-3" for="numberDayPurchasesCtp">Número de compras diarias</label>
													<div class="input-group">
														<input id="numberDayPurchasesCtp" class="form-control pwd-input text-right" value="" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="numberWeeklyPurchasesCtp">Número de compras semanales</label>
													<div class="input-group">
														<input id="numberWeeklyPurchasesCtp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="numberMonthlyPurchasesCtp">Número de compras mensuales</label>
													<div class="input-group">
														<input id="numberMonthlyPurchasesCtp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label class ="pr-3" for="dailyPurchaseamountCtp">Monto diario de compras</label>
													<div class="input-group">
														<input id="dailyPurchaseamountCtp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="weeklyAmountPurchasesCtp">Monto semanal de compras</label>
													<div class="input-group">
														<input id="weeklyAmountPurchasesCtp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="monthlyPurchasesAmountCtp">Monto mensual de compras</label>
													<div class="input-group">
														<input id="monthlyPurchasesAmountCtp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="purchaseTransactionCtp">Monto por transacción de compras</label>
													<div class="input-group">
														<input id="purchaseTransactionCtp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="flex mb-5 flex-column">
						<span class="line-text slide-slow flex mb-2 h4 semibold primary">Sin tarjeta presente
							<i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
						</span>
						<div class="section my-2 px-5">
							<form id="">
								<div class="container">
									<div class="row">
										<div class="col-10 bolck mx-auto">
											<div class="row">
												<div class="form-group col-12 col-lg-4">
													<label class ="pr-3" for="numberDayPurchasesStp">Número de compras diarias</label>
													<div class="input-group">
														<input id="numberDayPurchasesStp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="numberWeeklyPurchasesStp">Número de compras semanales</label>
													<div class="input-group">
														<input id="numberWeeklyPurchasesStp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="numberMonthlyPurchasesStp">Número de compras mensuales</label>
													<div class="input-group">
														<input id="numberMonthlyPurchasesStp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label class ="pr-3" for="dailyPurchaseamountStp">Monto diario de compras</label>
													<div class="input-group">
														<input id="dailyPurchaseamountStp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="weeklyAmountPurchasesStp">Monto semanal de compras</label>
													<div class="input-group">
														<input id="weeklyAmountPurchasesStp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="monthlyPurchasesAmountStp">Monto mensual de compras</label>
													<div class="input-group">
														<input id="monthlyPurchasesAmountStp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="purchaseTransactionStp">Monto por transacción de compras</label>
													<div class="input-group">
														<input id="purchaseTransactionStp" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="flex mb-5 flex-column ">
						<span class="line-text slide-slow flex mb-2 h4 semibold primary">Retiros
							<i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
						</span>
						<div class="section my-2 px-5">
							<form id="">
								<div class="container">
									<div class="row">
										<div class="col-10 bolck mx-auto">
											<div class="row">
												<div class="form-group col-12 col-lg-4">
													<label class ="pr-3" for="dailyNumberWithdraw">Número diario de retiros</label>
													<div class="input-group">
														<input id="dailyNumberWithdraw" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="weeklyNumberWithdraw">Número semanal de retiros</label>
													<div class="input-group">
														<input id="weeklyNumberWithdraw" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="monthlyNumberWithdraw">Número mensual de retiros</label>
													<div class="input-group">
														<input id="monthlyNumberWithdraw" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label class ="pr-3" for="dailyAmountWithdraw">Monto diario de retiros</label>
													<div class="input-group">
														<input id="dailyAmountWithdraw" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="weeklyAmountWithdraw">Monto semanal de retiros</label>
													<div clxs="input-group">
														<input id="weeklyAmountWithdraw" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="monthlyAmountwithdraw">Monto mensual de retiros</label>
													<div class="input-group">
														<input id="monthlyAmountwithdraw" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="WithdrawTransaction">Monto por transacción de retiros</label>
													<div class="input-group">
														<input id="WithdrawTransaction" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="flex mb-5 flex-column ">
						<span class="line-text slide-slow flex mb-2 h4 semibold primary">Créditos
							<i class="flex mr-1 pl-2 icon icon-chevron-down flex-auto" aria-hidden="true"></i>
						</span>
						<div class="section my-2 px-5">
							<form id="">
								<div class="container">
									<div class="row">
										<div class="col-10 bolck mx-auto">
											<div class="row">
												<div class="form-group col-12 col-lg-4">
													<label class="pr-3" for="dailyNumberCredit">Número diario de créditos</label>
													<div class="input-group">
														<input id="dailyNumberCredit" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="weeklyNumberCredit">Número semanal de créditos</label>
													<div class="input-group">
														<input id="weeklyNumberCredit" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="monthlyNumberCredit">Número mensual de créditos</label>
													<div class="input-group">
														<input id="monthlyNumberCredit" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label class="pr-3" for="dailyAmountCredit">Monto diario de créditos</label>
													<div class="input-group">
														<input id="dailyAmountCredit" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="weeklyAmountCredit">Monto semanal de créditos</label>
													<div clxs="input-group">
														<input id="weeklyAmountCredit" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="monthlyAmountCredit">Monto mensual de créditos</label>
													<div class="input-group">
														<input id="monthlyAmountCredit" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
												<div class="form-group col-12 col-lg-4">
													<label for="CreditTransaction">Monto por transacción de créditos</label>
													<div class="input-group">
														<input id="CreditTransaction" class="form-control pwd-input text-right" type="text" autocomplete="off" name="" required>
													</div>
													<div class="help-block"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
					<form id="sign-bulk-form" method="post">
						<div class="flex row mt-3 mb-2 mx-2 justify-end">
							<div class="col-5 col-lg-3 col-xl-3 form-group">
								<div class="input-group">
									<input id="password-sign" name="password" class="form-control pwd-input pr-0" type="password" autocomplete="off" placeholder="Contraseña">
									<div class="input-group-append">
										<span id="pwd_action" class="input-group-text pwd-action" title="Mostrar contraseña"><i class="icon-view mr-0"></i></span>
									</div>
								</div>
								<div class="help-block bulk-select text-left"></div>
							</div>
							<div class="col-auto">
								<button id="sign-bulk-btn" class="btn btn-primary btn-small btn-loading flex mx-auto">
									Actualizar</button>
							</div>
						</div>
					</form>
				</div>

			</div>
    </div>
    <?php if($widget): ?>
    <?php $this->load->view('widget/widget_enterprise-product_content'.$newViews, $widget) ?>
    <?php endif; ?>
</div>
