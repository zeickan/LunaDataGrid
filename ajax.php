<?php

//echo"<pre>".print_r($_POST['pag'],1)."</pre>";

require_once 'lunaDataGrid.class.php';

$grid = new lunaDataGrid( $_POST['sql'] );

$grid->ajax = true;

$grid->title = $_POST['title'];

$grid->colName = ($_POST['colname'])?$_POST['colname']:null;

$grid->colData = ($_POST['coldata'])?$_POST['coldata']:array("*");

$grid->order = ($_POST['order'])?$_POST['order']:false;

$grid->pagination = ($_POST['pagination'] == "true")?true:false;

$grid->max = ($_POST['itemsPerPage'])?$_POST['itemsPerPage']:false;

$grid->checkbox = ($_POST['checkbox'] == "true")?true:false;

$grid->editableField = ($_POST['editableField'])?$_POST['editableField']:null;

$grid->colRelationship = ($_POST['relationships'])?$_POST['relationships']:null;

if( $_POST['setButton'] ){
    
    while( list($k,$v) = each($_POST['setButton']) ){
        
        $grid->setButton( $k , $v );
        
    }    
    
}


$table = $grid->build();


echo $table;