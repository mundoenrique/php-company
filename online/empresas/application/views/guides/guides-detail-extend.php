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
			echo '</ul>';
		?>
	</div>
	<br>

	<div class="navprueba">
		<?php
			echo '<div class="description" id="subtitle">' .  $info->subtitle . '</div><br>' .
						'<div class="description">';
						foreach($info->description as $item) {
							echo '<p align="justify">' .  $item . '</p>';
						}
						if ($item != end($info->description)) {
							echo '<br>';
						}
					echo '</div>';
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

<div id="ppal_left">
	<ul>
		<?php
			foreach($category_guides as $guide) {
				echo '<a href="' . $urlBase . '/guias-detalle/' . $guide->_id . '">';
								if ($guide->_id == $info->_id) {
									echo '<li class="extended-titles-selected" style="color:#696969;">' . $guide->title . '</li>';
								} else {
									echo '<li class="extended-titles" style="color:#696969;">' . $guide->title . '</li>';
								}
				echo '</a>';
			}
		?>
	</ul>
</div>

<div class="slideshow-container">
	<?php
		for($i = 1; $i <= $size; $i++) {
			echo
				'<div class="mySlides fade">
					<div id="ppal_right">
						<strong>' . $info->title . '</strong>';
						foreach($items[$i-1]->caption as $item2){
							echo '<p>' . $item2 . '</p>';
						}
						if ($item2 != end($items[$i-1]->caption)) {
							echo '<br>';
						}
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


