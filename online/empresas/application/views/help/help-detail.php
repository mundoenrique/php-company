<head>
<style>
#ppal_left{
  width: 50%;
  float: left;
  padding: 5px;
}
#ppal_right{
  width: 50%;
  float: left;
  padding: 5px;
}
</style>
</head>

<center>
    <div>
        <ul style="list-style-type:none">
            <?php
								$categories = ["Ingreso y Configuración","Gestión de Lotes","Órdenes y Facturas","Descargas","Reportes de Gestión"];
                foreach($categories as $category) {
										echo '<li style="display:inline-block;">';
										echo '<a href="">' . $category . '</a>';
										echo '</li>';
                }
            ?>
        </ul>
    </div>
</center>
<br>
<center>
    <div>
        <ul style="list-style-type:none">
            <?php
								$category_titles = ["Ingresar al sistema","Reestablecer su contraseña","Cómo configurar cuenta"];
                foreach($category_titles as $title) {
										echo '<li style="display:inline-block;">';
										echo '<a href="">' . $title . '</a>';
										echo '</li>';
                }
            ?>
        </ul>
    </div>
</center>

<div>
	<?php
		$json_title = '{
			"category": "Ingreso y Configuración",
			"title": "Ingresar al sistema",
			"subtitle": "¿Cómo configurar la cuenta de la empresa en CEO?",
			"href": "",
			"description": "En la opción Configurar podrá consultar todo lo relativo a la cuenta de su empresa. Contiene las secciones de Usuario, Empresas, Sucursales y Descargas, en las cuales tendrá la facilidad de actualizar, modificar o registrar los datos y descargar manuales de usuario o archivos de gestión de Lotes.",
			"country": "VE",
			"language": "ES",
			"items":
			[
					{
							"step": 1,
							"caption": "Ingrese en las páginas web de Bonus o Plata y haga clic en el botón Conexión Empresas Online.",
							"list": null,
							"image": "imagenes/ingresar_sistema_CEO/ingresar_sistema_CEO_01.png",
							"tip": null
					},
					{
							"step": 2,
							"caption": "En la pantalla de inicio coloque sus datos de usuario y contraseña, suministrados previamente vía correo electrónico.",
							"list": null,
							"image": "imagenes/ingresar_sistema_CEO/ingresar_sistema_CEO_02.png",
							"tip": null
					},
					{
							"step": 3,
							"caption": "Revise y acepte las “Condiciones generales, Términos de uso y Confidencialidad”.",
							"list": null,
							"image": "imagenes/ingresar_sistema_CEO/ingresar_sistema_CEO_03.png",
							"tip": null
					},
					{
							"step": 4,
							"caption": "Cambie la contraseña. Su nueva clave será más segura y robusta. El sistema le indicará los requerimientos que debe cumplir.",
							"list": null,
							"image": "imagenes/ingresar_sistema_CEO/ingresar_sistema_CEO_04.png",
							"tip": {
									"text": "Requerimientos de la contraseña:",
									"list": ["De 8 a 15 caracteres", "Al menos una letra", "Al menos una letra mayúscula", "De 1 a 3 números", "Al menos un carácter especial (* & $ # % . ?)"]
							}
					}
			]
		}';
		$selected_title = json_decode($json_title);
		echo '<div>
          	<h2>' . $selected_title->subtitle . '</h2>
						<p>' . $selected_title->description . '</p>';

		echo  	'<div>
							<div class="opt-ingsis0">
								<p>  Paso' . $selected_title->items[0]->step . '</p>
								<p>' . $selected_title->items[0]->caption . '</p>
						</div>
						<div>';

		echo		'<div>
							<img widt="300px" height="300x" src="https://upload.wikimedia.org/wikipedia/commons/f/f9/Phoenicopterus_ruber_in_S%C3%A3o_Paulo_Zoo.jpg"/>
						<div>
					</div>';
  ?>
</div>
