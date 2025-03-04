$(function () {
	var $container = $('#listCompanies');
	var sectores = '{';
	sectores += '"ve": {';
	sectores += '"1":"&#xe088;",'; //agricultura
	sectores += '"2":"&#xe086;",'; //alimentación y bebidas
	sectores += '"3":"&#xe07e;",'; //banca
	sectores += '"4":"&#xe076;",'; //comunicaciones
	sectores += '"5":"&#xe073;",'; //construcción
	sectores += '"6":"&#xe071;",'; //consultoría
	sectores += '"7":"&#xe06b;",'; //educación
	sectores += '"8":"&#xe068;",'; //electrónica
	sectores += '"9":"&#xe061;",'; //energía
	sectores += '"10":"&#xe05f;",'; //entretenimiento
	sectores += '"11":"&#xe05e;",'; //envío
	sectores += '"12":"&#xe057;",'; //finanzas
	sectores += '"13":"&#xe054;",'; //gobierno
	sectores += '"14":"&#xe04e;",'; //hotelero
	sectores += '"15":"&#xe04a;",'; //ingeniería
	sectores += '"16":"&#xe095;",'; //manufactura
	sectores += '"17":"&#xe094;",'; //maquinaría
	sectores += '"18":"&#xe092;",'; //medio ambiente
	sectores += '"19":"&#xe091;",'; //medios de comunicación
	sectores += '"20":"&#xe016;",'; //sin fines de lucro
	sectores += '"21":"&#xe033;",'; //productos químicos
	sectores += '"22":"&#xe024;",'; //recreación
	sectores += '"23":"&#xe01c;",'; //salud
	sectores += '"24":"&#xe01b;",'; //seguros
	sectores += '"25":"&#xe01a;",'; //servicios básicos
	sectores += '"26":"&#xe00e;",'; //tecnología
	sectores += '"27":"&#xe076;",'; //telecomunicaciones
	sectores += '"28":"&#xe033;",'; //textiles
	sectores += '"29":"&#xe05e;",'; //transporte
	sectores += '"30":"&#xe004;",'; //ventas por menor(al detal)
	sectores += '"31":"&#xe033;"'; //otros
	sectores += '},';
	sectores += '"co": {';
	sectores += '"1":"&#xe088;",'; //agrícola
	sectores += '"2":"&#xe088;",'; //ganadero
	sectores += '"3":"&#xe088;",'; //pesquero
	sectores += '"4":"&#xe033;",'; //minero
	sectores += '"5":"&#xe033;",'; //forestal
	sectores += '"6":"&#xe095;",'; //industrial
	sectores += '"7":"&#xe061;",'; //energético
	sectores += '"8":"&#xe073;",'; //de la construcción
	sectores += '"9":"&#xe05e;",'; //transporte
	sectores += '"10":"&#xe076;",'; //comunicaciones
	sectores += '"11":"&#xe09a;",'; //comercial
	sectores += '"12":"&#xe033;",'; //turístico
	sectores += '"13":"&#xe01c;",'; //sanitario
	sectores += '"14":"&#xe06b;",'; //educativo
	sectores += '"15":"&#xe057;",'; //financiero
	sectores += '"16":"&#xe07e;",'; //de la administración
	sectores += '"17":"&#xe054;"'; //gobierno
	sectores += '},';
	sectores += '"pe": {';
	sectores += '"1":"&#xe088;",'; //agrícola
	sectores += '"2":"&#xe088;",'; //ganadero
	sectores += '"3":"&#xe088;",'; //pesquero
	sectores += '"4":"&#xe033;",'; //minero
	sectores += '"5":"&#xe033;",'; //forestal
	sectores += '"6":"&#xe095;",'; //industrial
	sectores += '"7":"&#xe061;",'; //energético
	sectores += '"8":"&#xe073;",'; //de la construcción
	sectores += '"9":"&#xe05e;",'; //transporte
	sectores += '"10":"&#xe076;",'; //comunicaciones
	sectores += '"11":"&#xe09a;",'; //comercial
	sectores += '"12":"&#xe033;",'; //turístico
	sectores += '"13":"&#xe01c;",'; //sanitario
	sectores += '"14":"&#xe06b;",'; //educativo
	sectores += '"15":"&#xe057;",'; //financiero
	sectores += '"16":"&#xe07e;",'; //de la administración
	sectores += '"17":"&#xe054;",'; //gobierno
	sectores += '"18":"&#xe086;",'; //alimentos
	sectores += '"19":"&#xe033;"'; //otros sectores
	sectores += '}';
	sectores += '}';

	var objson = $.parseJSON(sectores);

	var scroll_interval;
	var ancho = 0;
	var pasa = 1;

	var dash_var = {
		paginar: false,
		cantEmp: 9,
		filtro: '',
		pgActual: 1,
		pgTotal: 1,
		items: [],
		p: true,
		pp: 1
	}




	paginar();

$("#listCompanies").on('mouseenter','.style-companies-item, #more-info',function(e){
	$(this).next("span#more-info").fadeIn("fast");

})
$("#listCompanies").on('mouseleave','.space-companies',function(){
	$("span#more-info").fadeOut("fast");
})

	// Busqueda alfabetica



	// Inicializar filtro
	$container.isotope({
		itemSelector: '.space-companies',
		animationEngine: 'jQuery',
		animationOptions: {
			duration: 400,
			easing: 'linear',
			queue: false
		},

		layoutMode: 'fitRows'
	});
	//--Fin inicializar filtro

	var $optionSets = $('#options'),  // contenedor de filtros
		$optionLinks = $optionSets.find('a');

	//Evento que filtra
	$optionLinks.on('click', function () {
		var $seletor = $(this);
		if ($seletor.attr('id') != 'buscar') {
			$(".isotope-item").show();
			$('#listCompanies').find('.style-companies-item-activa').removeClass('style-companies-item-activa');
			$("span#more-info").fadeOut("fast");
			$(".isotope-item").css('z-index', '2');
			// dash_var.p=true;


			var $optionSet = $seletor.parents('.option-set');
			$optionSet.find('.selected').removeClass('selected');
			$seletor.addClass('selected');

			var value = $seletor.attr('data-option-value');
			albafiltro(value, $seletor);
		}

	});
	$('#filtrar').on('change', function(){
		$(".isotope-item").show();
		$('#listCompanies').find('.style-companies-item-activa').removeClass('style-companies-item-activa');
		$("span#more-info").fadeOut("fast");
		$(".isotope-item").css('z-index', '2');
		var $seletor = $(this).find('option:selected');
		var value = $(this).find('option:selected').attr('data-option-value');
		albafiltro(value, $seletor);
	})
	//--Fin evento que filtra


	//-- Fin busqueda alfabetica


	// Busqueda campo de texto


	$('#search-filter').bind('keyup', function () {
		isotopeSearch($(this).val().toLowerCase());
	});

	function isotopeSearch(kwd) {

		var matches = []; // arreglo que contiene las coincidencias
		var item = 1, pgitem = 1;

		//  dash_var.p=true;
		$(".isotope-item").show();
		$('#listCompanies').find('.style-companies-item-activa').removeClass('style-companies-item-activa');
		$("span#more-info").fadeOut("fast");
		$(".isotope-item").css('z-index', '2');

		if ((kwd.length > 3)) { // min 5 chars to execute query:

			for (var i = 0; i < dash_var.items.length; i++) {
				$('#' + dash_var.items[i].id).attr('class', ($('#' + dash_var.items[i].id).attr('class').split(/[b]\d*/).join('')));
				if (dash_var.items[i].name.indexOf(kwd) !== -1) {
					matches.push($('#' + dash_var.items[i].id)[0]);
					if (item > dash_var.cantEmp) {
						item = 1; pgitem += 1;
					}
					$('#' + dash_var.items[i].id).addClass('b' + pgitem);
					item += 1;
				}
			}
			$container.isotope({ filter: $(matches) });
			// $(".isotope-hidden").hide();

			itemsFiltro = $('.space-companies').not('.isotope-hidden').length;
			itemsFiltroD = Math.floor(itemsFiltro / dash_var.cantEmp);
			if (itemsFiltro > 0) {

				(itemsFiltro / dash_var.cantEmp) == itemsFiltroD ? paginas = itemsFiltroD : paginas = itemsFiltroD + 1;

				$container.isotope({ filter: '.b1' });

				//paginado(paginas, '.b');
				paginacion(paginas, '.b');

			}

		} else if ((kwd.length == 0) && $('.space-companies').length > 0) {
			$container.isotope({ filter: '.space-companies' });
			//paginado(dash_var.pgTotal,'.');
			paginacion(dash_var.pgTotal, '.');

			$container.isotope({ filter: '.1' });
			// $(".isotope-hidden").hide();
		}
		$(".isotope-hidden").hide();
		noResults();

	}

	//-- Fin busqueda campo de texto
	function albafiltro(value, $seletor) {
		console.log('Value', value);
		console.log('Selector', $seletor.attr('value'));
		console.log('Selector', $seletor.attr('value') + 1);
		$container.isotope({ filter: value });
		noResults();

		itemsFiltro = $('.space-companies').not('.isotope-hidden').length;
		console.log('itemsfiltro', itemsFiltro);
		itemsFiltroD = Math.floor(itemsFiltro / dash_var.cantEmp);
		if (itemsFiltro > 0) {

			(itemsFiltro / dash_var.cantEmp) == itemsFiltroD ? paginas = itemsFiltroD : paginas = itemsFiltroD + 1;


			$container.isotope({ filter: '.' + $seletor.attr('value') + 1 });


			paginacion(paginas, '.' + $seletor.attr('value'));
			$(".isotope-hidden").hide();

		}
	}
	// Mostrar/ocultar Text sin resultados
	function noResults() {

		if (!$container.data('isotope').$filteredAtoms.length && $('.space-companies').length > 0) {
			$('.resultSet').show();
			$('#contend-pagination-p').hide();
			//}

		} else {
			$('.resultSet').hide();
			$('#contend-pagination-p').show();
		}
	}
	// Mostrar/ocultar Text sin resultados

	// Mostrar/ocultar campo de texto
	$("#buscar").click(
		function () {
			//  dash_var.p=true;
			$('#search-filter').val('');
			$('#search-filter').fadeToggle('fast');
		}
	);
	//-- Fin mostrar/ocultar campo de texto

	$("#listCompanies").on("click", '.style-companies-item', function () {
		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		var rif = $(this).attr("data-acrif");
		var activ = $(this).attr("data-acnomcia");
		var razon = $(this).attr("data-acrazonsocial");
		var desc = $(this).attr("data-acdesc");
		var accodcia = $(this).attr("data-accodcia");
		var accodgrupoe = $(this).attr("data-accodgrupoe");
		$('form#empresas').append('<input type="hidden" name="ceo_name" value="' + ceo_cook + '"/>');
		$('form#empresas').append('<input type="hidden" name="data-acrif" value="' + rif + '" />');
		$('form#empresas').append('<input type="hidden" name="data-acnomcia" value="' + activ + '" />');
		$('form#empresas').append('<input type="hidden" name="data-acrazonsocial" value="' + razon + '" />');
		$('form#empresas').append('<input type="hidden" name="data-acdesc" value="' + desc + '" />');
		$('form#empresas').append('<input type="hidden" name="data-accodcia" value="' + accodcia + '" />');
		$('form#empresas').append('<input type="hidden" name="data-accodgrupoe" value="' + accodgrupoe + '" />');
		$('form#empresas').submit();
	});

	//--Fin Extraer data y dirigir sgt pagina

	function paginar() {
		var dataIcon;


		$('#loading').show();
		$('#more').hide();

		var ceo_cook = decodeURIComponent(
			document.cookie.replace(/(?:(?:^|.*;\s*)ceo_cook\s*\=\s*([^;]*).*$)|^.*$/, '$1')
		);
		var dataRequest = JSON.stringify({
			data_filtroEmpresas: dash_var.filtro,
			data_paginar: dash_var.paginar,
			data_tamanoPagina: dash_var.cantEmp,
			data_paginaActual: dash_var.pgActual
		});
		var dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, { format: CryptoJSAesJson }).toString();
		$.post(baseURL + api + isoPais + "/empresas/lista",
			{ request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook) },
			function (response) {
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, { format: CryptoJSAesJson }).toString(CryptoJS.enc.Utf8))
				if (!data.ERROR) {
					var item = 1, pg = 1, cat, pgfa = 1, pgfd = 1, pgfh = 1, pgfl = 1, pgfp = 1, pgft = 1, pgfx = 1, pgf;
					var itemfa = 1, itemfd = 1, itemfh = 1, itemfl = 1, itemfp = 1, itemft = 1, itemfx = 1;
					$('.resultSet2').hide();
					$('#loading').hide();

					dash_var.pgActual = parseInt(data.listadoEmpresas.paginaActual, 10);
					dash_var.pgTotal = parseInt(data.listadoEmpresas.totalPaginas, 10);

					$('#totalEmpresas').text("Total Empresas: " + data.listadoEmpresas.totalRegistros);

					if (data.listadoEmpresas.lista.length > 0) {

						$.each(data.listadoEmpresas.lista, function (k, v) {

							if (item > dash_var.cantEmp) {
								item = 1; pg += 1;
							}

							cat = v.acnomcia.trim().substr(0, 1);
							if ('ABC'.indexOf(cat) != -1) {
								if (itemfa > dash_var.cantEmp) {
									itemfa = 1; pgfa += 1;
								}
								pgf = 'A-C' + pgfa;
								itemfa += 1;
							} else if ('DEFG'.indexOf(cat) != -1) {
								if (itemfd > dash_var.cantEmp) {
									itemfd = 1; pgfd += 1;
								}
								pgf = 'D-G' + pgfd;
								itemfd += 1;
							} else if ('HIJK'.indexOf(cat) != -1) {
								if (itemfh > dash_var.cantEmp) {
									itemfh = 1; pgfh += 1;
								}
								pgf = 'H-K' + pgfh;
								itemfh += 1;
							} else if ('LMNO'.indexOf(cat) != -1) {
								if (itemfl > dash_var.cantEmp) {
									itemfl = 1; pgfl += 1;
								}
								pgf = 'L-O' + pgfl;
								itemfl += 1;
							} else if ('PQRS'.indexOf(cat) != -1) {
								if (itemfp > dash_var.cantEmp) {
									itemfp = 1; pgfp += 1;
								}
								pgf = 'P-S' + pgfp;
								itemfp += 1;
							} else if ('TUVW'.indexOf(cat) != -1) {
								if (itemft > dash_var.cantEmp) {
									itemft = 1; pgft += 1;
								}
								pgf = 'T-W' + pgft;
								itemft += 1;
							} else if ('XYZ'.indexOf(cat) != -1) {
								if (itemfx > dash_var.cantEmp) {
									itemfx = 1; pgfx += 1;
								}
								pgf = 'X-Z' + pgfx;
								itemfx += 1;
							}


							if (isoPais == "Ve") {
								dataIcon = objson.ve[v.actividadeconm];
							} else if (isoPais == "Co") {
								dataIcon = objson.co[v.actividadeconm];
							} else if (isoPais == "Pe" || isoPais == "Usd") {
								dataIcon = objson.pe[v.actividadeconm];
							}
							if (!dataIcon) {
								dataIcon = "&#xe033;";
							}
							var s = v.resumenProductos > 1 || v.resumenProductos < 1 ? 's' : '';
							var canvas = '<li class="space-companies ' + cat + ' ' + pg + ' ' + pgf + '" id=' + v.accodcia + ' data-category=' + cat + '>';
							canvas += '<a class=style-companies-item data-accodcia="' + v.accodcia.trim() + '" data-acrif="' + v.acrif.trim() + '" data-acnomcia="' + v.acnomcia.trim() + '" data-acrazonsocial="' + v.acrazonsocial.trim() + '" data-acdesc="' + v.acdesc.trim() + '" data-accodgrupoe="' + v.accodgrupoe.trim() + '"><span aria-hidden=true class=icon data-icon=' + dataIcon + '></span>';
							canvas += '<p id=text-companies-T>' + charset(v.acnomcia.trim(), 'empresa asociada') + '</p> ';
							canvas += '<p id=text-companies>' + $("#estandar").attr("data-fiscal") + ': ' + v.acrif.trim() + '</p> ';
							canvas += '<p id=text-companies>' + v.acdesc.trim() + '</p></a>';
							canvas += '<span id=more-info><a><p><b>' + v.resumenProductos + '</b>Producto'+s+'</p>';
							canvas += '<p><b>' + v.resumenSucursal.trim() + '</b>Sucursales</p>';
							canvas += '<p><b></b>' + charset(v.acpercontac.trim(), 'contacto asociado').toLowerCase().replace(/(^| )(\w)/g, function (x) { return x.toUpperCase(); }) + '</p>';//resumenTarjetaHabiente Tarjetahabientes
							canvas += '</a></span></li>';

							var $newitem = $(canvas);

							$container.isotope('insert', $newitem);

							var tmp = {};
							tmp.id = v.accodcia;
							tmp.name = $("#" + v.accodcia).text().toLowerCase();

							dash_var.items.push(tmp);

							item += 1;

						}); //Fin each

						$container.isotope({ filter: '.1' });
						//paginado(dash_var.pgTotal,'.');
						paginacion(dash_var.pgTotal, '.');
						$('.isotope-hidden').hide();
						$('#contend-pagination-p').show();
					} else {
						$('.resultSet2').show();
						$('.resultSet2 h2').text("Usuario sin empresa asignada");
						$('#contend-pagination-p').hide();
					}

				} else {
					$('#loading').hide();
					$('.resultSet2').show();

					if (data.ERROR == "-29") {
						alert("Usuario actualmente desconectado");
						$(location).attr('href', baseURL + isoPais + '/login');
					} else {
						$('.resultSet2 h2').text(data.ERROR);
					}

				}
			}).done(function (data) {

				//  dash_var.p=true;
				// dash_var.pp=dash_var.pgActual;

				if (dash_var.paginar && dash_var.pgActual < dash_var.pgTotal) {
					$('#more').show();
				} else {
					$('#more').hide();
				}

				$('[id=more-info]').on('click', function(event) {
					var companyCont = $(event.target).closest('.space-companies');
					var companyLink = companyCont.find('.style-companies-item');
					companyLink.trigger('click');
				});

			}); //Fin post


	} //Fin paginar    /***********************Paginacion inicio***********************/

	function paginateArrow( accion, filtro)
  {
      if(accion == 'next')
      {
        pasa = (dash_var.pgActual == dash_var.pgTotal)? dash_var.pgTotal :  parseInt(pasa) + 1;
      }
      else
      {

        if(pasa == 2)
        {
          pasa = parseInt(pasa) - 1;
           $('#anterior-1, #anterior-22').attr("style","color:grey !important; pointer-events: none")
        }
        else
        {
          pasa = parseInt(pasa) - 1;
          $('#anterior-1, #anterior-22').removeAttr("style")
        }
      }

    if(parseInt(dash_var.pgTotal) == pasa )
      {
        $('#siguiente-1, #siguiente-22').attr("style","color:grey !important; pointer-events: none")
        $('#anterior-1, #anterior-22').removeAttr("style")
			}
			else
			{
				$('#siguiente-1, #siguiente-22').removeAttr("style")
			}

      //buscarReposiciones(id[1]);
      $(".isotope-item").show();
      $('#listCompanies').find('.style-companies-item-activa').removeClass('style-companies-item-activa');
      $("span#more-info").fadeOut("fast");
      $(".isotope-item").css('z-index', '2');

      $container.isotope({ filter: filtro + pasa });
      $(".isotope-hidden").hide();

      $(".num-pagina").css('text-decoration', 'none');
      $("#page_" + pasa).css('text-decoration', 'underline');
  }


	function paginacion(total, filtro) {

		if(dash_var.pgActual == 1)
      {
        $('#anterior-1, #anterior-22').attr("style","color:grey !important; pointer-events: none")
			}

		var texHtml = "";
		$("#list_pagination").html("");
		$("#list_pagination").css("max-width","80px");
		$("#anterior-2, #siguiente-2").hide();
		for (var i = 1; i <= total; ++i) {
			texHtml += '<span class="cajonNum"><a href="javascript:" id="page_' + i + '" class="num-pagina">' + i + '</a></span>';
		}
		$("#list_pagination").html(texHtml);

		$("#list_pagination").scrollLeft(0);

		ancho = $("#page_" + dash_var.pgActual).position();

		$("#list_pagination").animate({
			scrollLeft: ancho
		}, 200);

		$(".num-pagina").css('text-decoration', 'none');
		$("#page_" + dash_var.pgActual).css('text-decoration', 'underline');

		$(".num-pagina").unbind("click");
		$(".num-pagina").click(function () {
			var id = this.id;
			id = id.split("_");
			//buscarReposiciones(id[1]);
			pasa = id[1]

			if(pasa == total)
			{
				$('#siguiente-1, #siguiente-22').attr("style","color:grey !important; pointer-events: none")
				$('#anterior-1, #anterior-22').removeAttr('style')
			}
			else if(pasa == 1)
			{
				$('#anterior-1, #anterior-22').attr("style","color:grey !important; pointer-events: none")
				$('#siguiente-1, #siguiente-22').removeAttr('style')

			}
			else
			{
				$('#anterior-1, #siguiente-1, #anterior-22, #siguiente-22').removeAttr('style')
			}

			$(".isotope-item").show();
			$('#listCompanies').find('.style-companies-item-activa').removeClass('style-companies-item-activa');
			$("span#more-info").fadeOut("fast");
			$(".isotope-item").css('z-index', '2');

			$container.isotope({ filter: filtro + id[1] });
			$(".isotope-hidden").hide();

			$(".num-pagina").css('text-decoration', 'none');
			$("#page_" + id[1]).css('text-decoration', 'underline');

		});

		$("#anterior-1").click(function () {
			if (pasa > 4) {
				ancho = $("#list_pagination").scrollLeft() - 15
				$("#list_pagination").scrollLeft(ancho);
			}
			paginateArrow('after', filtro)
		});
		$("#siguiente-1").click(function () {

			if (pasa > 4) {
				ancho = $("#list_pagination").scrollLeft() + 20
				$("#list_pagination").scrollLeft(ancho);
			}

			paginateArrow('next', filtro)

		}).mouseout(function () {
			clearInterval(scroll_interval);
		});
		$("#anterior-22").unbind("click");
		$("#anterior-22").click(function () {
			pasa = 1;
			$('#anterior-22').attr("style","color:grey !important; pointer-events: none")
			$('#anterior-1').attr("style","color:grey !important; pointer-events: none")
			$("#siguiente-1").removeAttr('style')
			$("#siguiente-22").removeAttr('style')
			//buscarReposiciones(1);
			$(".isotope-item").show();
			$('#listCompanies').find('.style-companies-item-activa').removeClass('style-companies-item-activa');
			$("span#more-info").fadeOut("fast");
			$(".isotope-item").css('z-index', '2');

			$container.isotope({ filter: filtro + 1 });
			$(".isotope-hidden").hide();

			$(".num-pagina").css('text-decoration', 'none');
			$("#page_1").css('text-decoration', 'underline');

			$("#list_pagination").scrollLeft(0);

		});

		$("#siguiente-22").unbind("click");
		$("#siguiente-22").click(function () {
			pasa = total;
			$('#siguiente-22').attr("style","color:grey !important; pointer-events: none")
			$('#siguiente-1').attr("style","color:grey !important; pointer-events: none")
			$("#anterior-1").removeAttr('style')
			$("#anterior-22").removeAttr('style')
			//buscarReposiciones(total);
			$(".isotope-item").show();
			$('#listCompanies').find('.style-companies-item-activa').removeClass('style-companies-item-activa');
			$("span#more-info").fadeOut("fast");
			$(".isotope-item").css('z-index', '2');

			$container.isotope({ filter: filtro + total });
			$(".isotope-hidden").hide();

			$(".num-pagina").css('text-decoration', 'none');
			$("#page_" + total).css('text-decoration', 'underline');

			$("#list_pagination").scrollLeft(250);
		});

	}
	/***********************Paginacion fin***********************/


	function charset(texto, item) {

		if (texto == '') {
			return "No posee " + item;
		}

		if (texto.match(/\?{2}/g)) {
			return texto.replace(/\?{2}/g, 'Ñí');
		} else if ('compa?ia'.match(/\?/g)) {
			return texto.replace(/\?/g, 'Ñ')
		}
	}


}); //--Fin document ready
