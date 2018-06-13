<head>
<style>
.navprueba li a.aqui {
  color: black;
  background-color: coral;
}
.navprueba li a:hover{
  background-color: coral;
  color: black;
}
.menu_slider {
list-style: none;
margin-left: -37px;
}

.menu_slider li {
display: inline;
}
</style>
</head>

<div class="navprueba">
  <ul class="menu_slider" id="menu-opt-ppal">
		<?php
			echo $info;
		?>
  </ul>
</div>

<div class="navprueba">
  <ul class="menu_slider" id="menu-opt-ppal">
		<?php
			// create curl resource
			$ch = curl_init();
			// set url
			curl_setopt($ch, CURLOPT_URL, 'https://jsonplaceholder.typicode.com/posts/1');
			//set method
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			//return the transfer as a string
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			// $output contains the output string
			$output = curl_exec($ch);
			// close curl resource to free up system resources
			curl_close($ch);

			echo $output;
		?>
  </ul>
</div>

<center>
  <div class="navprueba">
    <ul class="menu_slider" id="menu-opt-ppal">
			<?php
				echo '<li><a >Reportes de Gestión</a></li>';
				$categories = ["Ingreso y Configuración","Gestión de Lotes","Órdenes y Facturas","Descargas","Reportes de Gestión"];
				for($i = 0; $i < sizeof($categories); $i++){
					echo '<li><a >' . $categories[$i] . '</a></li>';
				}
			?>
    </ul>
  </div>
</center>

<center>
	<table style="width:100%">
		<?php
			$titles_string = '[{"title": "Ingresar al sistema", "link":"https://online.novopayment.net/empresas-ayuda/Ve/guias/detalle"},{ "title":"Reestablecer su contraseña", "link":" "},
			{ "title":"Cómo configurar cuenta", "link":" "}, { "title":"Generar archivo lote", "link":" "},
			{ "title":"Procesar lote", "link":" "}, { "title":"Eliminar lote", "link":" "},
			{ "title":"Consultar / Descargar órdenes y facturas", "link":" "},{ "title":"Anular orden", "link":" "},
			{ "title":"Descargar manuales", "link":" "}, { "title":"Tarjetahabiente", "link":" "},
			{ "title":"Tarjetas emitidas", "link":" "}, { "title":"Recargas realizadas", "link":" "},
			{ "title":"Estatus de Lotes", "link":" "}, { "title":"Reposiciones de tarjetas y claves", "link":" "},
			{ "title":"Actividad por usuario", "link":" "}, { "title":"Cuenta concentradora", "link":" "},
			{ "title":"Estado de cuenta", "link":" "}, { "title":"Saldos al cierre", "link":" "},
			{ "title":"Reportes de Gestión", "link":" "}]';
			$titles = json_decode($titles_string);
			$size = sizeof($titles);
			for($i = 0; $i < $size; $i=$i+4){
				if ($i+1 < $size and $i+2 < $size and $i+3 < $size and $i+4 < $size) {
					echo '<tr>' .
						'<td align="center"><a href="' . $titles[$i]->link . '"><h3>' . $titles[$i]->title . '</h3></a></td>' .
						'<td align="center"><a href="' . $titles[$i+1]->link . '"><h3>' . $titles[$i+1]->title . '</h3></a></td>' .
						'<td align="center"><a href="' . $titles[$i+2]->link . '"><h3>' . $titles[$i+2]->title . '</h3></a></td>' .
						'<td align="center"><a href="' . $titles[$i+3]->link . '"><h3>' . $titles[$i+3]->title . '</h3></a></td>' .
						'<td align="center"><a href="' . $titles[$i+4]->link . '"><h3>' . $titles[$i+4]->title . '</h3></a></td>' .
					'</tr>';
				} else {
					echo '<tr>' .
						'<td align="center"><a href="' . $titles[$i]->link . '"><h3>' . $titles[$i]->title . '</h3></a></td>';
						if ($i+1 < $size) { echo  '<td align="center"><a href="' . $titles[$i]->link . '"><h3>' . $titles[$i]->title . '</h3></a></td>'; } else { break; }
						if ($i+2 < $size) { echo  '<td align="center"><a href="' . $titles[$i+1]->link . '"><h3>' . $titles[$i+1]->title . '</h3></a></td>'; } else { break; }
						if ($i+3 < $size) { echo  '<td align="center"><a href="' . $titles[$i+2]->link . '"><h3>' . $titles[$i+2]->title . '</h3></a></td>'; } else { break; }
						if ($i+4 < $size) { echo  '<td align="center"><a href="' . $titles[$i+4]->link . '"><h3>' . $titles[$i+4]->title . '</h3></a></td>'; }
					'</tr>';
				}
			}
			?>
	</table>
</center>
