<?php
$pais = $this->uri->segment(1);
$urlBaseA = $this->config->item('base_url');
$urlBase = $urlBaseA.$pais;
?>

		<?php
		echo '<ul class="ul-style">';
			foreach($categories as $category){
				echo	'<li class="categories" id="' . $category . '">
								<a href="' . $urlBase . '/guias/' . $category . '">
									<div style="color:white;">' . lang($category) . '</div>
								</a>
							</li>';
			}
		echo '</ul>';
		?>

		<?php
			$size = sizeof($guides);
			echo '<ul class="ul-style">';
			for($i = 0; $i < $size; $i=$i+5){
				if ($i+1 < $size and $i+2 < $size and $i+3 < $size and $i+4 < $size) {
					echo
						'<li class="titles" id="' . $guides[$i]->category . '"><a href="' . $urlBase . '/guias-detalle/' . $guides[$i]->_id . '"><div style="color:white;">' . $guides[$i]->title . '</div></a></li>' .
						'<li class="titles" id="' . $guides[$i+1]->category . '"><a href="' . $urlBase . '/guias-detalle/' . $guides[$i+1]->_id . '"><div style="color:white;">' . $guides[$i+1]->title . '</div></a></li>' .
						'<li class="titles" id="' . $guides[$i+2]->category . '"><a href="' . $urlBase . '/guias-detalle/' . $guides[$i+2]->_id . '"><div style="color:white;">' . $guides[$i+2]->title . '</div></a></li>' .
						'<li class="titles" id="' . $guides[$i+3]->category . '"><a href="' . $urlBase . '/guias-detalle/' . $guides[$i+3]->_id . '"><div style="color:white;">' . $guides[$i+3]->title . '</div></a></li>' .
						'<li class="titles" id="' . $guides[$i+4]->category . '"><a href="' . $urlBase . '/guias-detalle/' . $guides[$i+4]->_id . '"><div style="color:white;">' . $guides[$i+4]->title . '</div></a></li>';
				} else {
					echo
						'</ul><ul class="ul-style2"><li class="titles2" id="' . $guides[$i]->category . '"><a href="' . $urlBase . '/guias-detalle/' . $guides[$i]->_id . '"><div style="color:white;">' . $guides[$i]->title . '</div></a></li>';
						if ($i+1 < $size) { echo  '<li class="titles2" id="' . $guides[$i+1]->category . '"><a href="' . $urlBase . '/guias-detalle/' . $guides[$i+1]->_id . '"><div style="color:white;">' . $guides[$i+1]->title . '</div></a></li>'; } else { break; }
						if ($i+2 < $size) { echo  '<li class="titles2" id="' . $guides[$i+2]->category . '"><a href="' . $urlBase . '/guias-detalle/' . $guides[$i+2]->_id . '"><div style="color:white;">' . $guides[$i+2]->title . '</div></a></li>'; } else { break; }
						if ($i+3 < $size) { echo  '<li class="titles2" id="' . $guides[$i+3]->category . '"><a href="' . $urlBase . '/guias-detalle/' . $guides[$i+3]->_id . '"><div style="color:white;">' . $guides[$i+3]->title . '</div></a></li>'; } else { break; }
				}
			}
			echo '</ul>';
			?>
