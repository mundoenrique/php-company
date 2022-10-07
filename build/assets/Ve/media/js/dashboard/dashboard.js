$(function () {

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




	$("#listCompanies").on('mouseover', '.style-companies-item', function () {
		//$(this).addClass('style-companies-item-activa');
		$(this).parents('li').css('z-index', '3');

		$(this).next("span#more-info").fadeIn("fast");
		/*
		  if( dash_var.p && dash_var.pp == dash_var.pgActual ){
		    dash_var.pp-=1;
		  }


		  if( dash_var.p && (dash_var.pp<dash_var.pgActual || dash_var.pgActual==dash_var.pgTotal) ){

		    dash_var.p=false;
		    $container.height($container.height()+140);

		  }
		*/
		itemsFiltro = $('.space-companies').not('.isotope-hidden').length;
		filas = Math.floor(itemsFiltro / 3);
		(itemsFiltro / 3) == filas ? filas = filas : filas = filas + 1;
		$container.height((filas * $('.isotope-item').height()) + 160);

	});

	$("#listCompanies").on('mouseleave', '.style-companies-item', function () {
		//$(this).parents('#listCompanies').find('.style-companies-item-activa').removeClass('style-companies-item-activa');
		$("span#more-info").parents('li').not(this).css('z-index', '2');

		$("span#more-info").not(this).fadeOut("fast");
	});



	$("#more-info").on("click", function (event) {
		event.stopPropagation();
	});

	// --Fin seleccionar Item



	// Busqueda alfabetica

	var $container = $('#listCompanies');

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


	var $optionSets = $('#options'), // contenedor de filtros
		$optionLinks = $optionSets.find('a');

	//Evento que filtra
	$optionLinks.click(function () {
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

			$container.isotope({
				filter: value
			});
			noResults();

			itemsFiltro = $('.space-companies').not('.isotope-hidden').length;
			itemsFiltroD = Math.floor(itemsFiltro / dash_var.cantEmp);
			if (itemsFiltro > 0) {

				(itemsFiltro / dash_var.cantEmp) == itemsFiltroD ? paginas = itemsFiltroD : paginas = itemsFiltroD + 1;


				$container.isotope({
					filter: '.' + $seletor.attr('value') + 1
				});

				//paginado(paginas, '.'+$seletor.attr('value'));
				paginacion(paginas, '.' + $seletor.attr('value'));
				$(".isotope-hidden").hide();

			}
		}

	}); //--Fin evento que filtra


	//-- Fin busqueda alfabetica


	// Busqueda campo de texto


	$('#search-filter').bind('keyup', function () {
		isotopeSearch($(this).val().toLowerCase());
	});

	function isotopeSearch(kwd) {

		var matches = []; // arreglo que contiene las coincidencias
		var item = 1,
			pgitem = 1;

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
						item = 1;
						pgitem += 1;
					}
					$('#' + dash_var.items[i].id).addClass('b' + pgitem);
					item += 1;
				}
			}
			$container.isotope({
				filter: $(matches)
			});
			// $(".isotope-hidden").hide();

			itemsFiltro = $('.space-companies').not('.isotope-hidden').length;
			itemsFiltroD = Math.floor(itemsFiltro / dash_var.cantEmp);
			if (itemsFiltro > 0) {

				(itemsFiltro / dash_var.cantEmp) == itemsFiltroD ? paginas = itemsFiltroD : paginas = itemsFiltroD + 1;

				$container.isotope({
					filter: '.b1'
				});

				//paginado(paginas, '.b');
				paginacion(paginas, '.b');

			}

		} else if ((kwd.length == 0) && $('.space-companies').length > 0) {
			$container.isotope({
				filter: '.space-companies'
			});
			//paginado(dash_var.pgTotal,'.');
			paginacion(dash_var.pgTotal, '.');

			$container.isotope({
				filter: '.1'
			});
			// $(".isotope-hidden").hide();
		}
		$(".isotope-hidden").hide();
		noResults();

	}

	//-- Fin busqueda campo de texto

	// Mostrar/ocultar Text sin resultados
	function noResults() {

		if (!$container.data('isotope').$filteredAtoms.length && $('.space-companies').length > 0) {
			//  if(!$("#more").is(":visible")){
			$('.resultSet').show();
			//$('#paginado-dash').empty();
			$('#contend-pagination-p').hide();
			//}

		} else {
			$('.resultSet').hide();
			//$('#paginado-dash').show();
			$('#contend-pagination-p').show();
		}

		/* if( dash_var.paginar && dash_var.pgActual < dash_var.pgTotal ){
		            $('#more').show();
		          }else{
		            $('#more').hide();
		          }
		          */
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



	// PAGINACION
	/*
 $('#more').on('click', function(){
    if( dash_var.pgActual < dash_var.pgTotal ){
       dash_var.pgActual+=1;
       paginar();
    }

 });
*/
	//-- FIN PAGINACION


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
		$('form#empresas').append('<input type="hidden" name="ceo_name" value="'+ ceo_cook +'"/>');
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
		var dataRequest = CryptoJS.AES.encrypt(dataRequest, ceo_cook, {format: CryptoJSAesJson}).toString();
		$.post(baseURL + api + isoPais + "/empresas/lista",{request: dataRequest, ceo_name: ceo_cook, plot: btoa(ceo_cook)},
			function (response) {
				data = JSON.parse(CryptoJS.AES.decrypt(response.code, response.plot, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8))
				if (!data.ERROR) {
					var item = 1,
						pg = 1,
						cat, pgfa = 1,
						pgfd = 1,
						pgfh = 1,
						pgfl = 1,
						pgfp = 1,
						pgft = 1,
						pgfx = 1,
						pgf;
					var itemfa = 1,
						itemfd = 1,
						itemfh = 1,
						itemfl = 1,
						itemfp = 1,
						itemft = 1,
						itemfx = 1;
					$('.resultSet2').hide();
					$('#loading').hide();

					dash_var.pgActual = parseInt(data.listadoEmpresas.paginaActual, 10);
					dash_var.pgTotal = parseInt(data.listadoEmpresas.totalPaginas, 10);

					$('#totalEmpresas').text("Total Empresas: " + data.listadoEmpresas.totalRegistros);

					if (data.listadoEmpresas.lista.length > 0) {

						$.each(data.listadoEmpresas.lista, function (k, v) {

							if (item > dash_var.cantEmp) {
								item = 1;
								pg += 1;
							}

							cat = v.acnomcia.substr(0, 1);
							if ('ABC'.indexOf(cat) != -1) {
								if (itemfa > dash_var.cantEmp) {
									itemfa = 1;
									pgfa += 1;
								}
								pgf = 'A-C' + pgfa;
								itemfa += 1;
							} else if ('DEFG'.indexOf(cat) != -1) {
								if (itemfd > dash_var.cantEmp) {
									itemfd = 1;
									pgfd += 1;
								}
								pgf = 'D-G' + pgfd;
								itemfd += 1;
							} else if ('HIJK'.indexOf(cat) != -1) {
								if (itemfh > dash_var.cantEmp) {
									itemfh = 1;
									pgfh += 1;
								}
								pgf = 'H-K' + pgfh;
								itemfh += 1;
							} else if ('LMNO'.indexOf(cat) != -1) {
								if (itemfl > dash_var.cantEmp) {
									itemfl = 1;
									pgfl += 1;
								}
								pgf = 'L-O' + pgfl;
								itemfl += 1;
							} else if ('PQRS'.indexOf(cat) != -1) {
								if (itemfp > dash_var.cantEmp) {
									itemfp = 1;
									pgfp += 1;
								}
								pgf = 'P-S' + pgfp;
								itemfp += 1;
							} else if ('TUVW'.indexOf(cat) != -1) {
								if (itemft > dash_var.cantEmp) {
									itemft = 1;
									pgft += 1;
								}
								pgf = 'T-W' + pgft;
								itemft += 1;
							} else if ('XYZ'.indexOf(cat) != -1) {
								if (itemfx > dash_var.cantEmp) {
									itemfx = 1;
									pgfx += 1;
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


							var canvas = '<li class="space-companies ' + cat + ' ' + pg + ' ' + pgf + '" id=' + v.accodcia + ' data-category=' + cat + '>';
							canvas += '<a class=style-companies-item data-accodcia="' + v.accodcia + '" data-acrif="' + v.acrif + '" data-acnomcia="' + v.acnomcia + '" data-acrazonsocial="' + v.acrazonsocial + '" data-acdesc="' + v.acdesc + '" data-accodgrupoe="' + v.accodgrupoe + '"><span aria-hidden=true class=icon data-icon=' + dataIcon + '></span>';
							canvas += '<p id=text-companies-T>' + charset(v.acnomcia, 'empresa asociada') + '</p> ';
							canvas += '<p id=text-companies>' + v.acrazonsocial.substr(0, 52).replace(/[\,]+\s|[\,]/, ', ').toLowerCase().replace(/(^| )(\w)/g, function (x) {
								return x.toUpperCase();
							}) + '</p>';
							canvas += '<p id=text-companies>' + $("#estandar").attr("data-fiscal") + ': ' + v.acrif + '</p> ';
							canvas += '<p id=text-companies>' + v.acdesc + '</p></a>';
							canvas += '<span id=more-info><a><p><b><span aria-hidden=true class=icon data-icon=&#xe027;></span>' + v.resumenProductos + '</b>Productos</p>';
							canvas += '<p><b><span aria-hidden=true class=icon data-icon=&#xe013;></span>' + v.resumenSucursal + '</b>Sucursales</p>';
							canvas += '<p><b class="contacto-dash"><span aria-hidden=true class="icon icon-contact" data-icon=&#xe070;></span></b>' + charset(v.acpercontac, 'contacto asociado').toLowerCase().replace(/(^| )(\w)/g, function (x) {
								return x.toUpperCase();
							}) + '</p>'; //resumenTarjetaHabiente Tarjetahabientes
							canvas += '</a></span></li>';

							var $newitem = $(canvas);

							$container.isotope('insert', $newitem);

							var tmp = {};
							tmp.id = v.accodcia;
							tmp.name = $("#" + v.accodcia).text().toLowerCase();

							dash_var.items.push(tmp);

							item += 1;

						}); //Fin each

						$container.isotope({
							filter: '.1'
						});
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

		}); //Fin post


	} //Fin paginar

	/***********************Paginacion inicio***********************/
	function paginacion(total, filtro) {
		var texHtml = "";
		$("#list_pagination").html("");
		for (var i = 1; i <= total; ++i) {
			texHtml += '<span class="cajonNum"><a href="javascript:" id="page_' + i + '" class="num-pagina">' + i + '</a></span>';
		}
		$("#list_pagination").html(texHtml);

		$("#list_pagination").scrollLeft(0);

		ancho = $("#page_" + dash_var.pgActual).position().left - 4;

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
			$(".isotope-item").show();
			$('#listCompanies').find('.style-companies-item-activa').removeClass('style-companies-item-activa');
			$("span#more-info").fadeOut("fast");
			$(".isotope-item").css('z-index', '2');

			$container.isotope({
				filter: filtro + id[1]
			});
			$(".isotope-hidden").hide();

			$(".num-pagina").css('text-decoration', 'none');
			$("#page_" + id[1]).css('text-decoration', 'underline');

		});

		$("#anterior-1").unbind("mouseover");
		$("#anterior-1").unbind("mouseout");
		$("#anterior-1").mouseover(function () {
			scroll_interval = setInterval(
				function () {
					if ($("#list_pagination").scrollLeft() > 0) {
						ancho = $("#list_pagination").scrollLeft() - 1
						$("#list_pagination").scrollLeft(ancho);
					}
				}, 20);
		}).mouseout(function () {
			clearInterval(scroll_interval);
		});
		$("#anterior-2").unbind("mouseover");
		$("#anterior-2").unbind("mouseout");
		$("#anterior-2").mouseover(function () {
			scroll_interval = setInterval(
				function () {
					if ($("#list_pagination").scrollLeft() > 0) {
						ancho = $("#list_pagination").scrollLeft() - 1
						$("#list_pagination").scrollLeft(ancho);
					}
				}, 1);
		}).mouseout(function () {
			clearInterval(scroll_interval);
		});
		$("#siguiente-1").unbind("mouseover");
		$("#siguiente-1").unbind("mouseout");
		$("#siguiente-1").mouseover(function () {
			scroll_interval = setInterval(
				function () {
					ancho = $("#list_pagination").scrollLeft() + 1
					$("#list_pagination").scrollLeft(ancho);
				},
				20
			);
		}).mouseout(function () {
			clearInterval(scroll_interval);
		});
		$("#siguiente-2").unbind("mouseover");
		$("#siguiente-2").unbind("mouseout");
		$("#siguiente-2").mouseover(function () {
			scroll_interval = setInterval(
				function () {
					ancho = $("#list_pagination").scrollLeft() + 1
					$("#list_pagination").scrollLeft(ancho);
				},
				1
			);
		}).mouseout(function () {
			clearInterval(scroll_interval);
		});

		$("#anterior-22").unbind("click");
		$("#anterior-22, #anterior-2").click(function () {
			//buscarReposiciones(1);
			$(".isotope-item").show();
			$('#listCompanies').find('.style-companies-item-activa').removeClass('style-companies-item-activa');
			$("span#more-info").fadeOut("fast");
			$(".isotope-item").css('z-index', '2');

			$container.isotope({
				filter: filtro + 1
			});
			$(".isotope-hidden").hide();

			$(".num-pagina").css('text-decoration', 'none');
			$("#page_1").css('text-decoration', 'underline');

		});

		$("#siguiente-22").unbind("click");
		$("#siguiente-22, #siguiente-2").click(function () {
			//buscarReposiciones(total);
			$(".isotope-item").show();
			$('#listCompanies').find('.style-companies-item-activa').removeClass('style-companies-item-activa');
			$("span#more-info").fadeOut("fast");
			$(".isotope-item").css('z-index', '2');

			$container.isotope({
				filter: filtro + total
			});
			$(".isotope-hidden").hide();

			$(".num-pagina").css('text-decoration', 'none');
			$("#page_" + total).css('text-decoration', 'underline');

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

	var notice = sessionStorage.getItem('notice');

	if ($("body").attr("data-country") === 'Ve' && notice === null) {
		$("#dialog-monetary-reconversion").dialog({
			title: 'Notificación',
			modal: "true",
			width: "450px",
			draggable: false,
			resizable: false,
			closeOnEscape: false,
			focus: false,
			open: function (event, ui) {
				$(".ui-dialog-titlebar-close", ui.dialog).hide();
			}
		});
		$("#dialog-monetary").click(function () {
			sessionStorage.setItem('notice', true);
			$("#dialog-monetary-reconversion").dialog("destroy");
		});
	}

}); //--Fin document ready
