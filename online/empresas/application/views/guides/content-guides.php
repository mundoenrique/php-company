<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
?>

	<div class="container">
		<div class="publicidad">
		<?php

			switch(uri_string()){
				case 'Ve/guias/get-started': $imageUrl = 'guides/header-get-started.png'; break;
				case 'Ve/guias/batches': $imageUrl = 'guides/header-batches.png'; break;
				case 'Ve/guias/billing': $imageUrl = 'guides/header-billing.png'; break;
				case 'Ve/guias/downloads': $imageUrl = 'guides/header-downloads.png'; break;
				case 'Ve/guias/management-reports': $imageUrl = 'guides/header-management-reports.png'; break;
				default:	$imageUrl = 'guides/header-guides.png';
			}

			echo insert_image_cdn($imageUrl);
		?>
		</div>
		<div class="categories">
		<?php
			foreach($categories as $category){
				echo	'<a class="item" id="' . $category . '" href="' . $urlBase . '/guias/' . $category . '">
								' . lang($category) . '
							</a>';
			}
		?>
		</div>

		<div class="help-childs">
			<?php
				$size = sizeof($guides);

				for($i = 0; $i < $size; $i++){
					echo '<a class="item" id="' . $guides[$i]->category . '" href="' . $urlBase . '/guias-detalle/' . $guides[$i]->_id . '">
					<div class="help-icon"><i class="'.$guides[$i]->icon.'"></i></div>
					<div class="help-text">' . $guides[$i]->title . '</div>
					</a>';
				}
			?>
		</div>
		<strong></strong>
	</div>
