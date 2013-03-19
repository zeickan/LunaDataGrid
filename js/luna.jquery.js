(function( $ ){

    $.fn.lunaDataGrid = function( options ) {  

        // Seteamos las opciones por default
        var settings = $.extend( {
            'file'      : 'ajax.php',
            'table'     : null,
            'where'		: null,
            'order'     : null,
            'title'     : null,
            'colname'   : null,
            'coldata'   : null,
            
            'checkbox'      : null,
            
            'pag' : null,
            'pagination'    : null,
            'itemsPerPage'  : null,
            
            'editableField' : null,
            'relationships' : null,
            
            'setButton'     : null
            
            
        }, options);
    
        return this.each(function() {        
    
            // Petición al archivo que genera las opciones.
            
            var current = this;
            
            $.ajax({
                type: "POST",
                url: settings["file"],
                data: {
                        sql:    settings['table'],
                        title:  settings['title'],
                        where:	settings['where'],
                        order:  settings['order'],
                        
                        colname:   settings['colname'],
                        coldata:   settings['coldata'],
                        
                        checkbox: settings['checkbox'],
                        
                        pag: settings['pag'],
                        pagination: settings['pagination'],
                        itemsPerPage: settings['itemsPerPage'],
                        
                        editableField : settings['editableField'],
                        relationships : settings['relationships'],
                        
                        setButton : settings['setButton']
                        
                        
                    }
            }).done(function( msg ) {
                
                // Mostramos en el div indicado el resultado éxitoso de la petición
                
                jQuery(current).html(msg);//.append("<br><br> // DEBUG: <br><br>"+dump(settings));
                
                if ( settings['pagination'] == true ) {
                    
                    paginateLunaDataGrid( current , settings );
                    
                }
                
                
                
            });
            
            
    
        });

    };
    
    function paginateLunaDataGrid(box,options){
        
        $(".pagination a").click(function(){
            
            var pag = $(this).data("pag");
            
            var pags = {"pag":pag};
            
            set = merge(options,pags);
            
            $(box).lunaDataGrid(set);
            
            return false;
            
        });
        
    }
    
    
    
})( jQuery );



function merge(set1, set2){
  for (var key in set2){
    if (set2.hasOwnProperty(key))
      set1[key] = set2[key]
  }
  return set1
}

function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}
