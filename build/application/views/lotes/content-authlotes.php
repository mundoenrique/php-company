<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$ceo_name = $this->security->get_csrf_token_name();
$ceo_cook = $this->security->get_csrf_hash();

$entrar;
$orden="";
$disableF="";
$disableA="";
$info = $data[0];
$borrar='';
$cantXauth=0;
$loteXdesa=false;
$lotesxAuth=false;
$icon = $pais != "Ec-bp" ? '<span aria-hidden="true" class="icon" data-icon="&#xe03c;"></span>' : '';

if( !array_key_exists('ERROR', $data[0]) ){
		$entrar=true;
		$orden = $info->usuario->orden;

		$this->session->set_userdata('ordenS',$orden);
		log_message("info", 'Orden Auth '.$info->usuario->orden);

		if($orden=='0'  || $orden==''){
				$disableF="checkbox";
				$disableA="checkbox";
		}elseif($orden=='1'){
				$disableF="checkbox";
				$disableA="hidden";
		}elseif($orden=='2'){
				$disableF="hidden";
				$disableA="checkbox";
		}
}else{
		if($data[0]['ERROR']=='-29'){
				echo "<script>alert('Usuario actualmente desconectado'); location.reload();</script>";
		}
		$entrar=false;
}
if(!in_array('tebeli', $funciones)){
		$borrar='hidden';
}
?>

<div id="content-products" style="width:720px;padding:20px 2px;float:left;">
	<h1><?= lang('TITULO_LOTES_AUTORIZACION'); ?></h1>
	<h2 class="title-marca"><?= ucwords(mb_strtolower($programa));?></h2>
	<ol class="breadcrumb">
	<li>
			<a href="<?= $urlBase; ?>/dashboard" rel="start"><?= lang('BREADCRUMB_INICIO'); ?></a>
	</li>
	/
	<li>
			<a href="<?= $urlBase; ?>/dashboard" rel="section"><?= lang('BREADCRUMB_EMPRESAS'); ?></a>
	</li>
	/
	<li>
					<a href="<?= $urlBase; ?>/dashboard/productos" rel="section">
							<?= lang('BREADCRUMB_PRODUCTOS'); ?>
					</a>
	</li>
	/
	<li>
			<a href="<?= $urlBase; ?>/lotes" rel="section"><?= lang('BREADCRUMB_LOTES'); ?></a>
	</li>
	/
	<li class="breadcrumb-item-current">
			<a href="#" rel="section"><?= lang('BREADCRUMB_AUTORIZACION'); ?></a>
	</li>
	</ol>
	<div id="lotes-general" style='display:none'>
	<div id='lotes-2' >
			<?php
					if($entrar){
							if(count($info->listaPorFirmar)==0 && count($info->listaPorAutorizar)==0){
									echo "<div id='products-general' style='margin-top:10px'>
													<h2 >No hay Lotes por autorizar</h2>
												</div>";
							}
							if( count($info->listaPorFirmar)>0 ){
									echo '
									<div id="top-batchs">
											'.$icon.lang("TITULO_LOTES_PORFIRMAR").'
									</div>
									<div id="lotes-contenedor" class="lotes-contenedor-autorizacion">
											<div id="check-all" '.$disableF.'>
													<input id="select-allF" type="'.$disableF.'" /><em '.$disableF.'>'.lang("SELECT_ALL").' ('.count($info->listaPorFirmar).' lotes)</em>
											</div>';
											echo '<table class="table-text-aut" id="table-firmar">';
											echo    '<thead>';
											echo        '<tr>';
											echo            '<th '.$disableF.' class="checkbox-select"><span aria-hidden="true" class="icon" data-icon="&#xe083;"></span></th>';
											echo            '<th>'.lang('TABLA_LOTESP_NROLOTE').'</th>';
											echo            '<th id="td-nombre-2">'.lang('ID_FISCAL').'</th>';
											echo            '<th id="td-nombre-2">Empresa</th>';
											echo            '<th>Fecha carga</th>';
											echo            '<th>Tipo / Reg</th>';
											echo            '<th>Monto</th>';
											echo            '<th>Opciones</th>';
											echo        '</tr>';
											echo    '</thead>';
											echo    '<tbody>';
											foreach($info->listaPorFirmar as $firmar){  // cargar lotes por firmar
													echo "<tr>";
															echo "<td ".$disableF." class='checkbox-select'><input id='check-oneF' type='".$disableF."'  value='$firmar->acidlote' numlote='$firmar->acnumlote' ctipolote='$firmar->ctipolote'/></td>";
															echo "<td>$firmar->acnumlote</td>";
															echo "<td id='td-nombre-2'>$firmar->acrif</td>";
															echo "<td id='td-nombre-2'>".ucwords(mb_strtolower(substr($firmar->acnomcia,0,20)))."</td>";
															echo "<td class='field-date'>$firmar->dtfechorcarga</td>";
															echo "<td>".ucwords(mb_strtolower(substr($firmar->acnombre, 0,13)))." / $firmar->ncantregs</td>";
															echo "<td>".amount_format($firmar->nmonto)."</td>";
															echo "<td id='icons-options'>";
																	if($orden=='0' || $orden=='1'  || $orden==''){
																			echo "<a ".$borrar." id='borrar' title='Eliminar' idlote='$firmar->acidlote' numlote='$firmar->acnumlote' ctipolote='$firmar->ctipolote'><span aria-hidden='true' class='icon' data-icon='&#xe067;'></span></a>";
																	}
															echo "<a id='detalle' title='Ver lote' idlote='$firmar->acidlote'><span aria-hidden='true' class='icon' data-icon='&#xe003;'></span></a>";
															echo "</td>";
													echo "</tr>";
																	}
							echo "</tbody></table>";
									echo '</div>';

									if($orden=='0' || $orden=='1'  || $orden==''){
											echo '
													<div id="batchs-last">
															<form name="no-form" onsubmit="return false">';
																	if($pais == 'Ec-bp') { ?>
																			<center>
																					<table>
																							<tr>
																									<td valign="top" colspan="2">
																											<?= '<center><input id="clave" class="input-clave" type="password" placeholder="'.lang("MSG_INGRESE_CLAVE").'" value=""/></center>' ?>
																									</td>
																							</tr>
																							<tr>
																									<td valign="top">
																											<?= '<center><button '.$borrar.' id="eliminarF" type="submit" class="novo-btn-secondary">'.lang('TITULO_LOTESBTN_ELIMINAR').'</button></center>'; ?>
																									</td>
																									<td valign="top">
																											<?= '<center><button id="firma" class="novo-btn-primary">'.lang("TITULO_LOTESBTN_FIRMAR").'</button></center>' ?>
																									</td>
																							</tr>
																					</table>
																			</center>
																			<?php } else {
																					echo '<input id="clave" class="input-clave" type="password" placeholder="'.lang("MSG_INGRESE_CLAVE").'" value="" />
																					<button '.$borrar.' id="eliminarF" type="submit">'.lang('TITULO_LOTESBTN_ELIMINAR').'</button>
																					<button id="firma" >'.lang("TITULO_LOTESBTN_FIRMAR").'</button>';
																			}
															echo '</form>';
													echo '</div>';
									}else{
											echo '
													<div id="batchs-last">
															<h3>'.lang('MSJ_NO_FIRMA').'</h3>
													</div>';
									}
							}
					?>
							<div id="lotes-2" class='listaxAuth'>
									<?php
											if( $entrar && count($info->listaPorAutorizar)>0 ){
													echo '
													<div id="top-batchs">
															'.$icon.lang("TITULO_LOTES_PORAUTORIZAR").'
													</div>
													<div id="lotes-contenedor">
															<div id="check-all" '.$disableA.'>
																	<input id="select-allA" type="'.$disableA.'"  />
															</div>';
															echo '<table id="table-auth" class="table-text-aut" order-payable="'.$info->ordenXPagar.'">';
																	echo    '<thead>';
																			echo        '<tr>';
																					echo            '<th class="checkbox-select">'; if($disableA=='checkbox'){echo '<span aria-hidden="true" class="icon" data-icon="&#xe083;"></span></th>';}else{echo '<span aria-hidden="true" class="icon" data-icon="&#xe06f;"></span></th>';};
																					echo            '<th>'.lang('TABLA_LOTESP_NROLOTE').'</th>';
																					echo            '<th id="td-nombre-2">'.lang('ID_FISCAL').'</th>';
																					echo            '<th id="td-nombre-2">Empresa</th>';
																					echo            '<th class="field-date">Fecha carga</th>';
																					echo            '<th>Tipo / Reg</th>';
																					echo            '<th>Monto</th>';
																					echo            '<th>Opciones</th>';
																			echo        '</tr>';
																	echo    '</thead>';
																	echo    '<tbody>';
																			$lotesxAuth=false;
																			foreach($info->listaPorAutorizar as $autorizar){  // cargar lotes por autorizar
																					echo "<tr>";
																							if( strtoupper($autorizar->accodusuarioa) != strtoupper($this->session->userdata('userName')) ){ //para desasociar
																									echo "<td class='checkbox-select'>
																																	<input id='check-oneA' type='".$disableA."' value='$autorizar->acidlote'
																																			numlote='$autorizar->acnumlote' ctipolote='$autorizar->ctipolote'
																																			accodusuarioa='$autorizar->accodusuarioa' accodusuarioa2='$autorizar->accodusuarioa2'/>
																															</td>";
																									if($disableA=='checkbox'){
																											$lotesxAuth=true;
																									}
																									$cantXauth+=1;
																							}else{
																									echo "<td class='checkbox-select' id='icons-options'>
																																	<a class='icon-desa' title='Desasociar firma' idlote='$autorizar->acidlote' numlote='$autorizar->acnumlote'>
																																			<span aria-hidden='true' class='icon' data-icon='&#xe06f;'></span>
																																	</a>
																															</td>";
																									$loteXdesa=true;
																							}
																							echo "<td>$autorizar->acnumlote</td>";
																							echo "<td id='td-nombre-2'>$autorizar->acrif</td>";
																							echo "<td id='td-nombre-2'>".ucwords(mb_strtolower(substr($autorizar->acnomcia,0,20)))."</td>";
																							echo "<td class='field-date'>$autorizar->dtfechorcarga</td>";
																							echo "<td>".ucwords(mb_strtolower(substr($autorizar->acnombre, 0,13)))." / $autorizar->ncantregs</td>";
																							echo "<td>".amount_format($autorizar->nmonto)."</td>";
																							echo "<td id='icons-options'>";
																									if($orden=='0' || $orden=='2'  || $orden==''){
																											echo "<a ".$borrar." id='borrar' title='Eliminar' idlote='$autorizar->acidlote'
																																					numlote='$autorizar->acnumlote' ctipolote='$autorizar->ctipolote'>
																																			<span aria-hidden='true' class='icon' data-icon='&#xe067;'></span>
																																	</a>";
																									}
																									if($autorizar->ctipolote == 'Z' || $autorizar->ctipolote == 'Y'){
																										echo "";
																									}else{
																										echo "<a id='detalle' title='Ver lote' idlote='$autorizar->acidlote'>
																										<span aria-hidden='true' class='icon' data-icon='&#xe003;'></span>
																								</a>";
																									}

																							echo '</td>';
																					echo "</tr>";
																			}
																	echo "</tbody>";
															echo "</table>";
													echo '</div>';
													if( ($orden=='0' || $orden=='2' || $orden=='') && $lotesxAuth ){
															echo '<div id="batchs-last">
																							<form name="no-form" onsubmit="return false">';
																									if($pais=='Ec-bp'){ ?>
																											<center>
																													<table>
																															<tr>
																																	<td valign="top">
																																			<?php
																																					echo '
																																							<select id="selec_tipo_lote" name="tipo_lote_select">
																																									<option value="0">'.lang('SELECT_OPTION_XLOTE').'</option>
																																									<option value="1" selected>'.lang('SELECT_OPTION_XTIPO_lOTE').'</option>
																																							</select>';
																																							if($pais=='Ec-bp'){
																																			?>
																																	</td>
																																	<td valign="top">
																																			<?php
																																					echo '<input id="claveAuth" type="password" name="claveAuth" placeholder="'.lang("MSG_INGRESE_CLAVE").'" value="" #batchs-last input style="margin-left: 10px;    margin-bottom: 0px;"/>';
																																			?>
																																	</td>
																															</tr>
																															<tr>
																																	<td valign="top">
																																			<?php
																																			}
																																			echo    '<center><button '.$borrar.' id="button-eliminar" type="submit" class="novo-btn-secondary">'.lang('TITULO_LOTESBTN_ELIMINAR').'</button></center>';
																																			?>
																																	</td>
																																	<td valign="top">
																																			<?php
																																			echo'<center>
																																						<button id="button-autorizar" type="submit" class="novo-btn-primary btn-authorization">'.lang('TITULO_LOTESBTN_AUTORIZAR').'
																																						</button>
																																					</center>';
																																			?>
																																	<td>
																															</tr>
																													</table>
																									</center>
																									<?php
																									}else{
																											echo    '<button '.$borrar.' id="button-eliminar" type="submit" >'.lang('TITULO_LOTESBTN_ELIMINAR').'</button>';
																											echo    '<button id="button-autorizar" type="submit">'.lang('TITULO_LOTESBTN_AUTORIZAR').'</button>';
																									}
																									if($pais!='Ec-bp'){
																											echo '<input id="claveAuth" type="password" name="claveAuth" placeholder="'.lang("MSG_INGRESE_CLAVE").'" value="" #batchs-last input style="margin-left: 10px;"/>';
																											$selectTipoLote = $pais == 'Ve' ? '<input type="hidden" id="selec_tipo_lote" value="1">' :'<select id="selec_tipo_lote" name="tipo_lote_select"><option value="0">'.lang('SELECT_OPTION_XLOTE').'</option><option value="1" selected>'.lang('SELECT_OPTION_XTIPO_lOTE').'</option></select>';
																											echo $selectTipoLote;
																									}
																							echo '</form>
																					</div>';
													}elseif ( ($orden=='0' || $orden=='2' || $orden=='') && !$lotesxAuth ) {
															echo '<div id="batchs-last">
																							<h3>'.lang('MSJ_NO_LOTESXAUTH').'</h3>
																					</div>';
													}
													else{
															echo '<div id="batchs-last">
																	<h3>'.lang('MSJ_NO_AUTORIZA').'</h3>
																	</div>';
													}
											}
									?>

									<?php
											if ($pais == 'Ve') {
									?>
											<div class="info"></div>
									<?php
									}
									?>
							</div>
			<?php
					}else{
							echo '
							<div id="products-general" style="margin-top: 10px;">
									<h2 style="text-align:center">'.$data[0]['ERROR'].'</h2>
							</div>';
					}
			?>
	</div>
	</div>
</div>

<input type='hidden' id='current_user' value='<?= $this->session->userdata('userName'); ?>'/>
<input type='hidden' id='cantXauth' value='<?= $cantXauth ?>'/>
<input type='hidden' id='loteXdesa' value='<?= $loteXdesa ?>'/>
<input type='hidden' id='lotesxAuth' value='<?= $lotesxAuth ?>'/>
<div class='elem-hidden'>
    <h3 id='msg_2dafirma'> <?= lang('MSG_2DA_FIRMA') ?> </h3>
</div>

<?php
    echo "<form id='autorizacion' method='post' action='$urlBase/lotes/calculo'>
        <input type='hidden' name='data-COS' value='' id='data-COS'/>
    </form>";
    echo "<form id='detalleAuth' method='post' action= '$urlBase/lotes/autorizacion/detalle'>
        <input type='hidden' name='$ceo_name' value='$ceo_cook'>
    </form>";
?>
<?php
if ($pais == 'Ve'):
    if(isset($info->{'nuevoIva'})){

        $iva = $info->{'nuevoIva'} ;
        if ($iva=='true') {
            $iva = '1';
        }else{
            $iva = '0';
        }
    }else{
        $iva = '0';
    }
?>
<input type="hidden" id="nuevo-iva" style="display:none;" value="<?= $iva; ?>">
<div class="modal-lote" id="modal-lote" style="display:none; overflow: hidden; text-overflow: ellipsis;">
    <style type="text/css">
        .modal-table {
            border: 0;
            border-collapse: collapse;
            width: 100%;
        }

        .modal-table td {
            padding: 2px 5px 5px;
            vertical-align: top;
        }
    </style>
    <table border="0" class="modal-table" width="100%">
        <tbody>
            <?php
                $count = 0;
                foreach ($info->mediosPago as $medio):
                    $count = $count + 1;
                    $checked = ($count === 1) ? 'checked' : '';
                    $id = $medio->idPago;
                    $description = $medio->descripcion;
            ?>
                    <tr>
                        <td width="10%">
                            <input type="radio" id="modalidad-<?= $count; ?>" <?= $checked; ?> name="modalidad" value="<?= $id; ?>">
                        </td>
                        <td>
                            <label for="modalidad-<?= $count; ?>"><?= $description; ?></label>
                        </td>
                    </tr>
                <?php endforeach; ?>
        </tbody>
    </table>
    <input type="hidden" id="select-modal" name="select-modal">
</div>
<?php
endif;
?>

<div id='loading' style='text-align:center' class='elem-hidden'><?= insert_image_cdn("loading.gif"); ?></div>
