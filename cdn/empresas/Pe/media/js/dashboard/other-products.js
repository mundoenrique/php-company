  $(function() {

  	$('h3').css('text-shadow','inherit');

    $( ".acordion-program" ).accordion({
      
      event: "mouseover",
      active: false,
      icons: false,
      header: "h3",
      collapsible: true,
      heightStyle: "content",
      create: function(ev, ui){ 
      	$('.ui-accordion-content').css('padding','10px');
      	$('.ui-state-default').css('border','0');
      	$('.ui-accordion-header').css('padding','10px 5px');
      }
    
    });


  });

  
