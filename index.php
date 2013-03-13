<?php

require_once 'moonDataGrid.class.php';

$grid = new moonDataGrid("users INNER JOIN users_profile ON users.username = users_profile.aka");

$grid->colName = array("ID","Usuario","Contraseña","Email","Token","Nombre","Apellido");

$grid->colData = array('users.id', 'users.username', 'users.passwd', 'users.mail', 'users.token', 'users_profile.`name`', 'users_profile.last_name');

$grid->build();

