<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
$info = $title_info[0];
$items = $info->items;
$size = sizeof($items);
?>


	<div class="lists-category-title">
		<?php
		echo '<ul class="ul-style">';
			foreach($categories as $category){
				echo	'<li class="categories" id="' . $category . '">
						<a href="' . $urlBase . '/guias/' . $category . '">
							<div style="color:white;">' . lang($category) . '</div>
						</a>
					</li>';
			}
			echo '</ul><ul class="ul-style3">
							<li class="titles-detail" id="' . $category_guides[0]->category . '">
								<a href="' . $urlBase . '/guias/' . $category_guides[0]->category . '">
									<div style="color:white;">' . lang($category_guides[0]->category) . '</div>
								</a>';
			foreach($category_guides as $guide){
				echo	'<li class="titles-detail" id="generic">
						<a href="' . $urlBase . '/guias-detalle/' . $guide->_id . '">
							<div style="color:#696969;">' . $guide->title . '</div>
						</a>
					</li>';
			}
			echo '</ul>';
		?>
	</div>
	<br>

	<div class="navprueba">
		<?php
			echo '<div id="subtitle">' .  $info->subtitle . '</div><br>' .
					'<div><p>' .  $info->description . '</p></div>';
		?>
	</div>
	<br>

<div id="ppal_right">
	<?php
		echo '<div style="text-align:center">
			<a class="prev" onclick="plusSlides(-1)">&#10094;</a>';
			for($j = 1; $j <= $size; $j++) {
				echo
					'<span class="dot" onclick="currentSlide(' . $j . ')"></span>';
			}
				echo '<a class="next" onclick="plusSlides(1)">&#10095;</a></div>';
	?>
</div>

<div class="slideshow-container">
	<?php
		for($i = 1; $i <= $size; $i++) {
			echo
				'<div class="mySlides fade">
					<div id="ppal_left"><center>
					<div class="circle" id="' . $info->category . '-circle' . '">' .
						$i
					. '</div></center><br>';
						echo '<strong>Paso ' . $i . '</strong>' .
									'<p>' . $items[$i-1]->caption . '</p>';
									if (isset($items[$i-1]->list)) {
										echo '<ul>';
										foreach($items[$i-1]->list as $element){
											echo '<li><p>' . $element . '</p></li>';
										}
										echo '</ul>';
									}
					echo '</div>
					<div id="ppal_right">';
								$imageUrl = 'guides/' . $items[$i-1]->image;
							echo insert_image_cdn($imageUrl);
						if (sizeof($items[$i-1]->tip->list) != 0 or isset($items[$i-1]->tip->text)) {
							echo '<section style="background-color: #fdf5a8;">
											<h1 style="color: #0949a2;"><i class="fas fa-star"></i> Tip...</h1>';
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
										echo '</section>';
						}
					echo '</div>
				</div>';
		}
	?>
</div>


