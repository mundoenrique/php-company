
    $(function(){
      
      // Busqueda alfabetica

      var $container = $('#container');

      $container.isotope({
        itemSelector : '.space-companies',
        animationEngine :'jQuery',
        animationOptions: {
      duration: 400,
      easing: 'linear',
      queue: false,
      layoutMode : 'fitRows'
      }
      });
      
      
      var $optionSets = $('#options'),
          $optionLinks = $optionSets.find('a, input');

      $optionLinks.click(function(){
        var $this = $(this);
       
        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');
  
        
        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');
 
        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
       
          changeLayoutMode( $this, options )
        } else {
      
          $container.isotope( options );
        }
        
        return false;
      });


//-- Fin busqueda alfabetica

// Busqueda campo de texto

var items = []; 
$('li.space-companies').each(function(){
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
  // reset results arrays
  var matches = [];
  var misses = [];

  
  console.log(kwd);

  if ( (kwd != '') && (kwd.length >= 2) ) { // min 2 chars to execute query:
    
    // loop through brands array    
    _.each(items, function(item){
      console.log(item.name);
      console.log(item.name.indexOf(kwd));

      if ( item.name.indexOf(kwd) !== -1 ) { 
        console.log(item.id);
        matches.push( $('#'+item.id)[0] );
      } else {
        misses.push( $('#'+item.id)[0] );
      }
    });

    $container.isotope({ filter: $(matches) }); 
  
  } else {
    
    $container.isotope({ filter: '.space-companies' });
  }

} 

//-- Fin busqueda campo de texto


// Mostrar/ocultar campo de texto

      $("#buscar").click( 
        function () { 
                
          $("#search-filter").toggle("slide", { direction: "right" }, 100);                 
            
        } 
      );
//-- Fin mostrar/ocultar campo de texto


// Scroll fixed para menu de busqueda
 
      $('.filter').scrollToFixed( {marginTop: $('#content').outerHeight(), limit: $('#content') } );

//-- Fin scroll fixed para menu de busqueda

});
