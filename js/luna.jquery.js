

(function($){
    $.fn.extend({
    
        red: function(){
            /*Recorre todos los elementos encapsulados*/
            this.each(function(){
                /*Aqu’ se cambia el contexto, por lo que 'this' se refiere al elemento
                DOM por el que se est‡ pasando*/
                jQuery(this).css("color","#F00");
            });
        }    
        
    })
})(jQuery);