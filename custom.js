
$(document).ready(function() {
    
    $("#lunaGrid").lunaDataGrid({
            
            // Archivo de proceso
            file:   'develop/ajax.php',
            
            /* SQL options table (string), where (array), order (string)
             * Conserva la sintaxis de SQL (permite usar Joins y definir Order y tipo de WHERE)
             * Ejem: La tabla es 'first_table'
             *
             * table:  'first_table',
             *
             *  O Usando un inner Join
             * 
             * table:  'first_table INNER JOIN secound_table ON first_table.relid = first_table.relid',
             */
            
            table:  'users INNER JOIN users_profile ON users.username = users_profile.aka',
            
            where:  ["1=1"],
            
            order:  "users.id DESC",
            
            /* Columnas
             * colname array Define el nombre de las columnas para la tabla (HTML)
             *
             * coldata array Define los campos a pedir de la tabla
             * 
            */
            
            colname : ["ID","Usuario","Correo","Grupo","Acciones"],
            
            coldata : ["users.id","users.username","users.mail","users.group_id"],
            
            /* Paginaci—n
             *
             * true     Activa la paginaci—n
             * false    No pagina resultados 
             *
             * itemsPerPage Si la paginaci—n est‡ activa defines el maximo de elementos por p‡gina
             * Si no defines usando est‡ opci—n y paginaci—n est‡ activa el default sera de 10 por p‡gina
             */
            
            pagination: true,
            
            itemsPerPage: 30,
            
            /* Titulo para el caption */
            
            title:  "Lista de usuarios",
            
            /* Activar checkboxs */ 
            
            checkbox : true,
            
            
            editableField : { "username":"input" , "mail":"input" },
            
            relationships : { "group_id":"users.group_id|users_groups" },
            
            setButton: { "button1": 'accion.php?id=%id%' }
            
        });
    
});
