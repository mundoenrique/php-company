<? if( ! $this->session->userdata('logged_in') ){redirect($urlBase);}
$pais = $this->uri->segment(1);
?>
<h1>Descargas</h1>
<div id="campos-config-descarga">
	<div id="datos-1"><p id="user-name">Manuales</p></div>
	<div id="campos-descarga">
		<a href="<?= get_cdn() . "downloads/" . lang('DWL_MANUAL'); ?>" target="_blank">
			<span>
				<p id="first">Manual de Usuario Conexión Empresas Online (.pdf)</p>
			</span>
			<button id='manual_usuario'>
				<span aria-hidden="true" class="icon" data-icon="&#xe02e;"></span>
			</button>
		</a>
	</div>
	<?php
	if($pais != 'Ve' && $pais != 'Ec-bp'):
		if($pais != 'Co' && $pais != 'Usd' && $pais != 'Ec' && $pais != 'Ec-bp'):
	?>
			<div id="campos-descarga">
				<a href="<?php echo get_cdn() ?>downloads/TP-MUS-002_gestor_lotes_V_1.pdf" target="_blank">
					<span>
							<p id="first">Manual de Usuario Gestor de Lotes (.pdf)</p>
					</span>
					<button id='manual_GL'>
						<span aria-hidden="true" class="icon" data-icon="&#xe02e;"></span>
					</button>
				</a>
			</div>
	<?php
		endif;
	?>
		<div id="datos-1"><p id="user-name">Aplicaciones</p></div>
	<?php
		if ($pais != 'Co' && $pais != 'Usd'  && $pais != 'Ec' && $pais != 'Ec-bp'):
	?>
			<div id="campos-descarga">
				<a href="<?php echo get_cdn() ?>downloads/Gestor.rar">
					<span>
							<p id="first">Gestor de lotes (.zip 1.759kb) </p>
					</span>
					<button id='gestor_lotes'>
						<span aria-hidden="true" class="icon" data-icon="&#xe06e;"></span>
					</button>
				</a>
			</div>
	<?php
		endif;
	?>
		<div id="campos-descarga">
				<a href="<?php echo get_cdn() ?>downloads/JRE_6.zip" >
						<span>
								<p id="first">Java JRE 1.6 (.zip 14.226kb) </p>
						</span>
				<button id='javaJRE'><span aria-hidden="true" class="icon" data-icon="&#xe06e;"></span></button></a>
		</div>
	<?php
	endif;
	if ($pais == 'Co' || $pais == 'Pe' || $pais == 'Ec-bp'):
	?>
    <div id="datos-1">
			<p id="user-name">Archivos de gestión Conexión Empresas Online</p>
    </div>
    <div id="campos-descarga">
			<a href="<?php echo get_cdn() ?>downloads/ArchivosLotes.rar">
				<span>
						<p id="first">Archivos lotes operativos (.rar 194kb)</p>
				</span>
				<button id='archivos-xls'>
					<span aria-hidden="true" class="icon" data-icon="&#xe06e;"></span>
				</button>
			</a>
    </div>
	<?php
	endif;
	if($pais == 'Ve'):
	?>
		<div id="datos-1">
			<p id="user-name">Archivos para emisión de Lotes</p>
		</div>
		<div id="campos-descarga">
			<a href="<?php echo get_cdn() ?>downloads/Archivo_Lote_Guarderia.xls">
				<span>
					<p id="first">Archivo Plata Guardería / Transferencia (.xls 42kb)</p>
				</span>
				<button id='lote-guarderia'>
					<span aria-hidden="true" class="icon" data-icon="&#xe05a;"></span>
				</button>
			</a>
		</div>
		<div id="campos-descarga">
			<a href="<?php echo get_cdn() ?>downloads/Generador_Lotes.xlsm">
				<span>
						<p id="first">Archivo Generador de Lotes Emisión / Recarga (.xlsm 85kb)</p>
				</span>
				<button id='gestor_lotes'>
					<span aria-hidden="true" class="icon" data-icon="&#xe05a;"></span>
				</button>
			</a>
		</div>
	<?php
	endif;
	?>
</div>
