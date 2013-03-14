<?php

require_once 'lunaDataGrid.class.php';

$grid = new lunaDataGrid("users INNER JOIN users_profile ON users.username = users_profile.aka");

$grid->title = "Lista de usuarios";

$grid->colName = array("ID","Usuario","Contraseña","Email","Token","Nombre","Apellido");

$grid->colData = array('users.id', 'users.username', 'users.passwd', 'users.mail', 'users.token', 'users_profile.`name`', 'users_profile.last_name');

$grid->pagination = true;

$grid->order = "id ASC";

$grid->max = 1;

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

<div><?=$grid->build(); ?></div>

</body>
</html>




