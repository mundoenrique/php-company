

$(function(){

 // Busqueda button

 $('#sProducto').addClass('novo-btn-primary');
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
        },resizesContainer:false});
  }
}
// fin mostrar resultados nulos



  $('button#sProducto').on('click', function(){
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
    var idproducto = $(this).attr("data-idproducto");
    var nombreProducto = $(this).attr("data-nombreProducto");
		var marcaProducto = $(this).attr("data-marcaProducto");
		$('form#productos').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
    $('form#productos').append('<input type="hidden" name="data-idproducto" value="'+idproducto+'" />');
    $('form#productos').append('<input type="hidden" name="data-nombreProducto" value="'+nombreProducto+'" />');
    $('form#productos').append('<input type="hidden" name="data-marcaProducto" value="'+marcaProducto+'" />');
    $('form#productos').submit();
  });
//


// Datos a enviar

var acrif;
var acnomcia;
var acrazonsocial;
var acdesc;
var accodcia;
var accodgrupoe;

// -- fin datos a enviar



// Cambiar empresa


  // $("#sEmpresa").on("click",function(){

    $('#sEmpresa').hide();
    $("#widget-info-2").append("<img class='load-widget' id='cargando' src='"+$('#cdn').val()+"media/img/loading.gif'>");//'<h4 id="cargando">Cargando...</h4>'

    $.getJSON(baseURL+api+isoPais+'/empresas/lista').always(function(response) {

			data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))

      $("#widget-info-2").find($('#cargando')).remove();

      $('#sEmpresaS').show();
      $('#productosS').hide();

        if(!data.ERROR){
          $.each(data.lista, function(k,v){
          $("#empresasS").append('<option value="'+v.acrif+'" acnomcia="'+v.acnomcia+'" acrazonsocial="'+v.acrazonsocial+'" acdesc="'+v.acdesc+'" accodcia="'+v.accodcia+'" accodgrupoe='+v.accodgrupoe+'>'+v.acnomcia+'</option>');
        });
        }else{
          if(data.ERROR=='-29'){
          alert('Usuario actualmente desconectado'); location.reload();
          }
        }

      });

  // });


//--Fin Cambiar empresa

// Seleccionar empresa

  $("#empresasS").on("change",function(){
    acrif = $(this).val();
    acnomcia = $('option:selected', this).attr('acnomcia');
    acrazonsocial = $('option:selected', this).attr('acrazonsocial');
    acdesc = $('option:selected', this).attr('acdesc');
    accodcia = $('option:selected', this).attr('accodcia');
    accodgrupoe = $('option:selected', this).attr('accodgrupoe');

  });

//--Fin Seleccionar empresa


//  Enviar todo

  $('#aplicar').on('click',function(){


    if( acrif !== undefined ){
			var ceo_cook = decodeURIComponent(
				document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
			);

			var dataRequest = JSON.stringify ({
				data_accodgrupoe:accodgrupoe,
				data_acrif:acrif,
				data_acnomcia:acnomcia,
				data_acrazonsocial:acrazonsocial,
				data_acdesc:acdesc,
				data_accodcia:accodcia,
				llamada:'soloEmpresa'
			})
				dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
				$.post( baseURL+api+isoPais+"/empresas/cambiar", {request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)} )
				.done(function(response){
					data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))

          if(data === 1){
            $(location).attr('href',baseURL+isoPais+"/dashboard/productos/");
          }else{
            MarcarError('Intente de nuevo');
          }
         }
      );
    }else{
      MarcarError('Seleccione una empresa');
    }
  });

$('#sPrograms').on('click',function(){
  $(location).attr('href',baseURL+isoPais+"/dashboard/programas");
 });

function MarcarError(msj){
  $.balloon.defaults.classname = "error-login-2";
  $.balloon.defaults.css = null;
  $("#aplicar").showBalloon({position: "left", contents: msj});  //mostrar tooltip
  setTimeout( function(){ $("#aplicar").hideBalloon({position: "left", contents: msj}); }, 2500 );  // ocultar tooltip
}

//-- Fin enviar todo


// widget FIXED

var top = ($('#sidebar-products').offset().top-170) - parseFloat($('#sidebar-products').css('marginTop').replace(/auto/, 0));
       $(window).scroll(function (event) {

         var y = $(this).scrollTop();

          if (y >= top) {

            $('#sidebar-products').addClass('sub-widget');
            $('#sidebar-products').css('top',160);

        } else {

            $('#sidebar-products').removeClass('sub-widget');
         }
     });

//--FIN widget FIXED



  });
