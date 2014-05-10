<?php

require_once 'lunaDataGrid.class.php';

$grid = new lunaDataGrid("user_auth INNER JOIN user_profile ON user_auth.id = user_profile.parent_id");

$grid->title = "List";

$grid->colName = array("ID","User","Mail","First Name","Last Name","Country","Buttons");

$grid->colData = array('user_auth.id', 'user_auth.username', 'user_auth.email', 'user_profile.first_name', 'user_profile.last_name', 'user_profile.country');

$grid->order = "id ASC";

$grid->pagination = true;
    
$grid->max = 10;

//$grid->checkbox = true;

$grid->editableField = array( "username" => "input" ,"email" => "textarea" );

//$grid->colRelationship = array( "users.group_id|users_groups" );

$grid->setButton("Edit","edit.php?id=%id%");

$grid->setButton("Delete","del.php?id=%id%");


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




