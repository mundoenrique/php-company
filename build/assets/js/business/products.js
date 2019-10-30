$(function(){


// Datos a enviar
var acrif;
var acnomcia;
var acrazonsocial;
var acdesc;
var accodcia;
var accodgrupoe;
var btnSelectProduct = document.querySelectorAll('button#sProducto');

// -- fin datos a enviar

var top = ($('#sidebar-products').offset().top-170) - parseFloat($('#sidebar-products').css('marginTop').replace(/auto/, 0));

// Busqueda button
var $container = $('#products-list');

$container.isotope({
	itemSelector : '.product-description',
	animationEngine :'jQuery',
	animationOptions: {
		duration: 400,
		easing: 'linear',
		queue: false,
		layoutMode : 'fitRows'
  },
  onLayout: function( $elems, instance ){
  	$('#products-list').height($('#products-list').height()+30);
  }
});

var $optionSets = $('.filter'),
$optionLinks = $optionSets.find('a');

$optionLinks.click(function(){
	var $optionSet = $(this).parents('.filter-ul');
  $optionSet.find('.selected').removeClass('selected');
  $(this).addClass('selected');
  value = $(this).attr('data-option-value');
  $container.isotope( {filter:value,resizesContainer:true} );
  $('select.categories-products').val('*');
  resultNull();
});

$('select.categories-products').change(function(){  // busqueda select option
  var filters = $(this).val();
	$container.isotope({filter: filters,resizesContainer:true});
  resultNull();
  $(this).hasClass('area')? $('.tarjeta').val('*'):$('.area').val('*');
});
//-- Fin busqueda button

// Busqueda campo de texto

var items = [];
$('li.product-description').each(function(){
    var tmp = {};
    tmp.id = $(this).attr('id');
    tmp.name = ($(this).text().toLowerCase());
    items.push( tmp );
  });

$('#search-filter').bind('keyup', function() {
    isotopeSearch( $(this).val().toLowerCase() );
  });

function isotopeSearch(kwd)
{
	var matches = []; // arreglo que contiene las coincidencias
	if ( (kwd != '')  ) { // min 2 chars to execute query:
	  for (var i = 0; i < items.length; i++) {
    	if( items[i].name.indexOf(kwd) !==-1 ){
	      matches.push( $('#'+items[i].id)[0] );
    	}
  	}
  	$container.isotope({ filter: $(matches),resizesContainer:true });
	} else {
	  $container.isotope({ filter: '.product-description',resizesContainer:true });
	}
	resultNull();
	$('select.categories-products').val('*');
}

//-- Fin busqueda campo de texto
// Mostrar/ocultar campo de texto
$("#buscar").click(
	function () {
		$("#search-filter").fadeToggle('fast');
	}
);
//-- Fin mostrar/ocultar campo de texto

// mostrar resultados nulos
function resultNull(){
  if ( !$container.data('isotope').$filteredAtoms.length ) {
    $('.results').fadeIn('slow');
    $container.isotope({onLayout: function( $elems, instance ){
           $('#products-list').height($('#products-list').height()-60);
        },resizesContainer:false});
  } else {
		$('.results').fadeOut('fast');
    $container.isotope({onLayout: function( $elems, instance ){
        $('#products-list').height($('#products-list').height()+30);
				},resizesContainer:false
			});
  }
}
// fin mostrar resultados nulos

for (const button of btnSelectProduct) {
  button.addEventListener('click', function() {
		productSelected = this;
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		document.getElementsByName('ceo_name')[0].value = ceo_cook;
		document.getElementsByName('data-idproducto')[0].value = productSelected.getAttribute('data-idproducto');
		document.getElementsByName('data-nombreProducto')[0].value = productSelected.getAttribute('data-nombreProducto');
		document.getElementsByName('data-marcaProducto')[0].value = productSelected.getAttribute('data-marcaProducto');
		document.getElementById('productos').submit();
  })
}
//
if (elemEmpresa = document.getElementById('sEmpresa')){

	elemEmpresa.addEventListener('click', function(){

		document.getElementById('sEmpresa').style.display = 'none';
		urlImageLoading = document.getElementsByTagName('body')[0].getAttribute('asset-url')+'images/loading-gif/loading-novo.gif';
		newNode = document.createElement('span');
		newNode.innerHTML = "<img class='load-widget' id='cargando' src='"+urlImageLoading+"'>";
		document.getElementById('widget-info-2').appendChild(newNode);

		callNovoCore('POST', 'Business', 'listEnterprises', '', function(response) {
			document.getElementById('cargando').style.display = 'none';
			document.getElementById('productosS').style.display = 'none';
			document.getElementById('sEmpresaS').style.display = 'block';
			if(!response.ERROR){
				sel = document.getElementById('empresasS');
				$.each(response.lista, function(k,v){
          $("#empresasS").append('<option value="'+v.acrif+'" acnomcia="'+v.acnomcia+'" acrazonsocial="'+v.acrazonsocial+'" acdesc="'+v.acdesc+'" accodcia="'+v.accodcia+'" accodgrupoe='+v.accodgrupoe+'>'+v.acnomcia+'</option>');
				});
			}else{
				if(response.ERROR=='-29'){
					document.location.reload();
				}
			}
		})

	});
}else{

	callNovoCore('POST', 'Business', 'listEnterprises', '', function(response) {
		if(!response.ERROR){
			sel = document.getElementById('empresasS');
			$.each(response.lista, function(k,v){
				$("#empresasS").append('<option value="'+v.acrif+'" acnomcia="'+v.acnomcia+'" acrazonsocial="'+v.acrazonsocial+'" acdesc="'+v.acdesc+'" accodcia="'+v.accodcia+'" accodgrupoe='+v.accodgrupoe+'>'+v.acnomcia+'</option>');
			});
		}else{
			if(response.ERROR=='-29'){
				document.location.reload();
			}
		}
	})
}

// Seleccionar empresa
$("#empresasS").on("change",function(){
	acrif = $(this).val();
	acnomcia = $('option:selected', this).attr('acnomcia');
	acrazonsocial = $('option:selected', this).attr('acrazonsocial');
	acdesc = $('option:selected', this).attr('acdesc');
	accodcia = $('option:selected', this).attr('accodcia');
	accodgrupoe = $('option:selected', this).attr('accodgrupoe');
	console.log($(this));
});
//--Fin Seleccionar empresa


//  Enviar todo
$('#aplicar').on('click',function(){
	if( acrif !== undefined ){

		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		formTofill = document.getElementById('empresas');

		var newContentForm = document.createElement('span');
		newContentForm.innerHTML += '<input type="hidden" name="ceo_name" value="' + ceo_cook + '"/>';
		newContentForm.innerHTML += '<input type="hidden" name="data-acrif" value="' + acrif + '" />';

		newContentForm.innerHTML += '<input type="hidden" name="data-acnomcia" value="' + acnomcia + '" />';
		newContentForm.innerHTML += '<input type="hidden" name="data-acrazonsocial" value="' + acrazonsocial + '" />';
		newContentForm.innerHTML += '<input type="hidden" name="data-acdesc" value="' + acdesc + '" />';
		newContentForm.innerHTML += '<input type="hidden" name="data-accodcia" value="' + accodcia + '" />';
		newContentForm.innerHTML += '<input type="hidden" name="data-accodgrupoe" value="' + accodgrupoe + '" />';

		formTofill.appendChild(newContentForm);
		formTofill.submit();
	}else{
		MarcarError('Selecciona una empresa');
	}
});

$('#sPrograms').on('click',function(){
	pais = pais+'/';
	var pattern = new RegExp(pais, "gi");
	$(location).attr('href',baseURL.replace(pattern, pais)+"dashboard/programas");
 });

function MarcarError(msj){
  $.balloon.defaults.classname = "error-login-2";
  $.balloon.defaults.css = null;
  $("#aplicar").showBalloon({position: "left", contents: msj});  //mostrar tooltip
  setTimeout( function(){ $("#aplicar").hideBalloon({position: "left", contents: msj}); }, 2500 );  // ocultar tooltip
}
//-- Fin enviar todo

// widget FIXED
$(window).scroll(function (event) {
	var y = $(this).scrollTop();
	if (y >= top) {
		$('#sidebar-products').addClass('sub-widget');
		$('#options').addClass('sub');
		$('#sidebar-products').css('top',160);
	} else {
		$('#options').removeClass('sub');
			$('#sidebar-products').removeClass('sub-widget');
	}
});
//--FIN widget FIXED

});
