<?php

include_once 'config.php';
include_once 'mysql.func.php';


/*
 * class moonDataGrid
 * @author Andros Romo
 * @copyright Copyright (c) 2013, Andros Romo
 * 
 */

class moonDataGrid {
    
    /*
     * @var bool $html print table
     */
    var $html;
    
    /*
     * @var string $table print table
     */
    var $table;
    
    /*
     * @var string $table print table
     */
    var $where;
    
    /*
     * @var string $order print table
     */
    var $order;
    
    /*
     * @var string $pagination print table
     */
    var $pagination;
    
    /*
     * @var string $max print table
     */
    var $max;
    
    
    /*
     * @var array $colData print table
     */
    var $colData;
    
    /*
     * @var array $colName print table
     */
    var $colName;
    
    
    
    function __construct($table, $where = null , $order = null, $colData=array('*'), $colName=array('*'), $pagination = false, $html=true, $max = 10 ) {
        
        $this->html = $html;
        
        $this->table = $table;
        
        $this->where = $where;
        
        $this->order = $order;
        
        $this->colData = $colData;
        
        $this->colName = $colName;
        
        $this->pagination = $pagination;
        
        $this->max = $max;
        
    }
    
    
    
    /*
     * function build
     */
    
    public function build() {
        
        $db = new db_pdo;
        
        if( !is_null($this->where) ){
            $where = " WHERE ".join(" AND ",$this->where);
        }
        
        if( !is_null($this->order) ){
            $order = " ORDER BY ".$this->order;            
        }
        
        if( $this->pagination ){
            
            
            
            $pag = (int) ($_GET['pag'])?$_GET['pag']:1;
            
        }
        
        $db->add_consult("SELECT ".join(',',$this->colData)." FROM ".$this->table." ".$where." ".$order);
        
        $sql = $db->query();
        
        
        echo"<pre>".print_r($sql[0],1)."</pre>";
        
    }
    
    /*
     * function print_html
     * Imprime la tabla
     * @access public
     */
    
    function print_html() {
        
        if( $this->html ){
            
            echo "HTML";
            
        } else {
            
            echo "Array";
                
        } 
        
    }
}
