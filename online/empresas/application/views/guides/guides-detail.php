<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$info = $title_info[0];
$items = $info->items;
$size = sizeof($items);
?>

<div class="container">
	<div class="categories">
		<?php
			foreach($categories as $category){
				if($category==$info->category){
					echo	' <a class="item rect-border" id="' . $category . '" href="' . $urlBase . '/guias/' . $category . '">'
									.lang($category) . '
								</a>';
				}else{
					echo	' <a class="item rect-border" style="opacity:0.5; filter: alpha(opacity=50)" id="' . $category . '" href="' . $urlBase . '/guias/' . $category . '">'
									.lang($category) . '
								</a>';
				}
			}
		?>
	</div>

	<div class="help-container">
		<div class="help-nav">

			<div class="nav">
				<a class="nav-title"id="<? echo $category_guides[0]->category?>" href="<?php echo $urlBase . '/guias/' . $category_guides[0]->category;?>">
					<?php echo lang($category_guides[0]->category)?>
				</a>
				<div class="triangulo" id="<? echo $category_guides[0]->category.'-border'?>"></div>

			</div>

			<?php
					$active='item-active-'.$category_guides[0]->category;
					foreach($category_guides as $i=>$guide){
						if($i==0){

							if($guide->title==$info->title){
								echo	'<div class="nav-first" ><a class="'.$active.'" href="' . $urlBase . '/guias-detalle/' . $guide->_id . '">' . $guide->title . '</a></div>';
							}else{
								echo	'<div class="nav-first" ><a href="' . $urlBase . '/guias-detalle/' . $guide->_id . '">' . $guide->title . '</a></div>';

							}
						}else{

							if($guide->title==$info->title){
								echo	'
								<div class="nav-child" >
									<span class="nav-arrow">&#10093;</span>
									<a class="'.$active.'" href="' . $urlBase . '/guias-detalle/' . $guide->_id . '">' . $guide->title . '</a>
								</div>';
							}else{
								echo	'
								<div class="nav-child" >
									<span class="nav-arrow">&#10093;</span>
									<a href="' . $urlBase . '/guias-detalle/' . $guide->_id . '">' . $guide->title . '</a>
								</div>';
							}

						}
					}
			?>
		</div>
		<div class="help-title">
			<h1><?php echo $info->subtitle ?></h1>
			<?php
				foreach($info->description as $item) {
					echo '<p>' .  $item . '</p>';
				}
				if ($item != end($info->description)) {
					echo '<br>';
				}
			?>
    </div>
		<div class="show-nav ">
			<div class="help-show-nav" >
						<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
						<div class="dot-container">
							<?php
								for($j = 1; $j <= $size; $j++) {
								echo
									'<span class="dot" onclick="currentSlide(' . $j . ')"></span>';
								}
							?>
						</div>
						<a class="next" onclick="plusSlides(1)">&#10095;</a>
				</div>
		</div>

		<?php for($i = 1; $i <= $size; $i++) { ?>
			<div class="help-info mySlides fade">
				<div class="help-step">
					<?php
						echo '<center><div class="circle" id="' . $info->category . '-circle' . '">' .$i. '</div></center><br>';
						echo '<strong>Paso ' . $i . '</strong>';
						foreach($items[$i-1]->caption as $item2){
							if(preg_match('/\[icon-[0-9]\]/',$item2)){
								$fragments=preg_split("/\[icon-[0-9]\]/", $item2);

								echo '<p>'.$fragments[0].insert_image_cdn('guides/icono_documento.svg').$fragments[1]. '</p>';
							}else{
								echo '<p>' . $item2 . '</p>';
							}

						}

						if (isset($items[$i-1]->list)) {
							echo '<ul>';
							foreach($items[$i-1]->list as $element){
								$class="";
								if(preg_match('/\[icon-[0-9]\]/',$element,$coincidencia)){
									$element=preg_replace('/\[icon-[0-9]\]/', "", $element);
									$class=str_replace(['[',']'], "", $coincidencia[0]);
								}

								echo '<li class="'.$class.'"><p>' . $element . '</p></li>';
							}
							echo '</ul>';
						}?>

				</div>
				<div class="help-show">
					<div class="help-show-img"><?php
						$imageUrl = 'guides/' . $items[$i-1]->image;
						echo insert_image_cdn($imageUrl);?>
					</div>
					<?php
					if (sizeof($items[$i-1]->tip->list) != 0 or isset($items[$i-1]->tip->text)) {

						echo '
						<div class="help-tip">';
							echo insert_image_cdn('guides/tip-ceo.png');
							if (isset($items[$i-1]->tip->text)) {
								echo $items[$i-1]->tip->text;
							}
							if (sizeof($items[$i-1]->tip->list) != 0) {
								echo '<ul style="	margin-top: 0px;">';
								foreach($items[$i-1]->tip->list as $element){
									echo '<li>' . $element . '</li>';
								}
							echo '</ul>';
						}
						echo '</div>';
				}?>
				</div>

			</div><?php }?>
	</div>

</div>

