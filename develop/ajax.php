<?php

require_once 'lunaDataGrid.class.php';

$grid = new lunaDataGrid( $_POST['sql'] );

$grid->ajax = true;

$grid->title = $_POST['title'];

$grid->colName = $_POST['colname'];

$grid->colData = $_POST['coldata'];

$grid->where = $_POST['where'];

$grid->order = $_POST['order'];

$grid->pagination = $_POST['pagination'];

$grid->max = $_POST['itemsPerPage'];

$grid->checkbox = $_POST['checkbox'];

$grid->editableField = $_POST['editableField'];

$grid->colRelationship = $_POST['relationships'];

if( $_POST['setButton'] ){
    
    while( list($k,$v) = each($_POST['setButton']) ){
        
        $grid->setButton( $k , $v );
        
    }    
    
}


$table = $grid->build();


echo $table;