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
					echo	' <a class="item rect-border" style="opacity:0.5;filter: alpha(opacity=50)" id="' . $category . '" href="' . $urlBase . '/guias/' . $category . '">'
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
			<div class="nav-first" ><a class="item-active-management-reports" href="#">Reportes disponibles</a></div>

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


			<div class="help-info">
				<div class="help-nav2">
					<?php
						foreach($category_guides as $i=>$guide){
							if($guide->title==$info->title){
								echo	'<div class="nav2 nav2-active" ><a href="' . $urlBase . '/guias-detalle/' . $guide->_id . '">' . $guide->title . '</a></div>';
							}else{
								echo	'<div class="nav2" ><a href="' . $urlBase . '/guias-detalle/' . $guide->_id . '">' . $guide->title . '</a></div>';
							}


						}
					?>
				</div>
				<?php for($i = 1; $i <= $size; $i++) { ?>
					<div class="help-show mySlides fade">
						<div class="text-help">
						<p><strong> <?echo $info->title?></strong></p>
							<?php
								foreach($items[$i-1]->caption as $item2){
									echo '<p>' . $item2 . '</p>';
								}
								if ($item2 != end($items[$i-1]->caption)) {
									echo '<br>';
								}
								if (isset($items[$i-1]->list)) {
								echo '<ul>';
								foreach($items[$i-1]->list as $element){
									echo '<li><p>' . $element . '</p></li>';
								}
								echo '</ul>';
							}?>
						</div>
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
									echo '<strong>' . $items[$i-1]->tip->text . '</strong>';
								}
								if (sizeof($items[$i-1]->tip->list) != 0) {
									echo '<ul>';
									foreach($items[$i-1]->tip->list as $element){
										echo '<li>' . $element . '</li>';
									}
								echo '</ul>';
							}
							echo '</div>';
					}?>
					</div>
				<?php }?>
			</div>

	</div>
</div>
