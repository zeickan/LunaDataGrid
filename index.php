<?php

require_once 'lunaDataGrid.class.php';

$grid = new lunaDataGrid("users INNER JOIN users_profile ON users.username = users_profile.aka");

$grid->title = "Lista de usuarios";

$grid->colName = array("ID","Usuario","Email","Group","Token","Nombre","Apellido","Acciones");

$grid->colData = array('users.id', 'users.username', 'users.mail', 'users.group_id', 'users.token', 'users_profile.`name`', 'users_profile.last_name');

$grid->order = "id ASC";

$grid->pagination = true;

$grid->max = 3;

$grid->checkbox = true;

$grid->editableField = array( "username" => "input" ,"token" => "textarea" );

$grid->colRelationship = array( "users.group_id|users_groups" );

$grid->setButton("Editar","edit.php?id=%id%");

$grid->setButton("Eliminar","del.php?id=%id%");

$table = $grid->build();


?>
<!DOCTYPE html>

<html lang="en">
<head>  
    <meta charset="UTF-8">
    <title>Luna DataGrid jQuery</title>
    
    <link type="text/css" rel="stylesheet" href="css/stylesheet.css">
        
    <script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="js/luna.jquery.js" type="text/javascript"></script>
    

</head>

<body>

<div><?=$table ?></div>

</body>
</html>




