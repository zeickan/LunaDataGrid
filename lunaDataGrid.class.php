<?php

include_once 'config.php';
include_once 'mysql.func.php';


/*
 * class moonDataGrid
 * @author Andros Romo
 * @copyright Copyright (c) 2013, Andros Romo
 * 
 */

class lunaDataGrid {
    
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
    
    /*
     * @var array relationship
     */
    
    var $colRelationship;
    
    
    
    function __construct($table, $where = null , $order = null, $colData=array('*'), $colName=array('*'), $pagination = false, $html=true, $max = 10, $colRelationship = null , $editableField = null) {
        
        $this->html = $html;
        
        $this->table = $table;
        
        $this->where = $where;
        
        $this->order = $order;
        
        $this->colData = $colData;
        
        $this->colName = $colName;
        
        $this->pagination = $pagination;
        
        $this->max = $max;
        
        $this->buttons = array();
        
        $this->relTable = array();
        
        $this->selectValue = "id";
        
        $this->selectText = "description";
        
        $this->colRelationship = $colRelationship;
        
        $this->editableField = $editableField;
        
    }
    
    /*
     * function setButton
     * @param $array
     */
    
    function setButton($name , $action ) {
        
        $this->buttons[] = array( 'name' => $name, 'action' => $action );
        
    }
    
    
    /*
     * function setTable
     * @param $array
     */
    
    function setTable( $name , $value ) {
        
        $this->relTable[$name] = (string) "$value";
        
    }
        
    
    
    /*
     * function data
     */
    
    protected function data() {
        
        $db = new db_pdo;
        
        if( !is_null($this->where) ){
            $where = " WHERE ".join(" AND ",$this->where);
        }
        
        if( !is_null($this->order) ){
            $order = " ORDER BY ".$this->order;            
        }
        
        if( $this->pagination ){
            
            $db->add_consult("SELECT ".join(',',$this->colData)." FROM ".$this->table." ".$where);
            
            $num = $db->numRows();
            
            $total = $num[0][0];
            
            $pages = ceil($total/$this->max);
            
            $pag = (int) ($_GET['pag'])?($_GET['pag']-1):0;
            
            $def = (int) ($pag >= 1)?($pag*$this->max):'0';
            
            $db->unset_consult();
            
            $limit = "LIMIT {$def},{$this->max}";
            
            $this->pages = $pages;
            
            $this->pag  = (int) (isset($_GET['pag']))?$_GET['pag']:'1';
            
        }
        
        
        if( $this->colRelationship ){
            
            $i = 0;
            
            foreach($this->colRelationship as $row){
                
                $rel = explode("|",$row);
                
                $attr = str_replace(".","",strstr($rel[0],"."));
                
                $db->add_consult("SELECT * FROM {$rel[1]}");
                
                $this->setTable( $attr ,$i );
                
                $i++;
                
            }
            
            $this->sql = $db->query();
            
            $db->unset_consult();
            
        }
        
        $db->add_consult("SELECT ".join(',',$this->colData)." FROM ".$this->table." {$where} {$order} {$limit}");
        
        $sql = $db->query();
        
        if( $sql[0] ){
            
            return $sql[0];
        
        } else {
            
            // Si la consulta no tiene resultados
            // Proximo manejo de errores personalizados
            
            return array();
            
        }
        
        
    }
    
    /*
     * function build
     */
    
    public function build() {        
        
        $this->data = $this->data();
        
        $html = '<table class="lunaGrid">';
        
        $html.= "<caption>{$this->title}</caption>";
        
        /* THEAD */
        
        $html.= '<thead><tr>';
        
            foreach($this->colName as $row){
                
                $html.= "<th>$row</th>";
                
            }
        
        $html.= '</tr></thead>';
        
        /* /THEAD */
        
        /* TBODY */
        
        $hmtl.= '<tbody>';
        
            while( list($k,$v) = each($this->data) ):
            
                $html.= '<tr>';
            
                foreach($v as $raw => $row){
                    
                    if( is_string($this->relTable[$raw]) ):
                    
                        $html.= "<td>".$this->tableSelect($this->relTable[$raw])."</td>";
                    
                    elseif( $this->editableField[$raw] ):
                        
                        switch($this->editableField[$raw]){
                            
                            case "input":
                                
                                $html.= '<td><input name="'.$raw.'['.$v["id"].']" type="text" value="'.$row.'" ></td>';
                                
                            break;
                        
                            case "textarea":
                                
                                $html.= '<td><textarea name="'.$raw.'['.$v["id"].']">'.$row.'</textarea></td>';
                                
                            break;
                            
                        }

                    else:
                    
                        $html.= "<td>$row</td>";
                    
                    endif;
                    
                }
                
                        
                if( count($this->buttons) >= 1){
                
                    $html.='<td>';
                    
                        foreach( $this->buttons as $acc ):
                        
                            $accion = preg_replace("@\%([a-z0-9]+)\%@e", '$v["$1"]', $acc["action"]);
                        
                            $html.='<a href="'.$accion.'">'.$acc["name"].'</a>';
                        
                        endforeach;
                    
                    $html.='</td>';
                    
                }
                
                $html.= '</tr>';
            
            endwhile;
        
        $hmtl.= '</tbody>';
        
        /* /TBODY */
        
        
        /* TFOOT */
        
        if( $this->pagination ){
            
            $html.= '<tfoot>';
            
            $html.= '<tr><td class="pagination" colspan="'.count($this->colName).'">'.$this->pagination().'</td></tr>';
            
            $html.= '</tfoot>';
            
        }
        
        /* /TFOOT */
        
        
        $html.= '</table>';
        
        return $html;
        
    }
    
    /*
     * function pagination
     * @param $doc
     * @access private
     */
    
    private function pagination($doc = '') {
        
        if( $this->pages > 1){
                    
            $pagination = array();
    
            if( $this->pag > 1 ){
                
                $pagination[] = '<a href="index.php">&laquo; Primera página</a> ';
                
                if( $pag != 2):
                
                    $pagination[] = '<a href="?pag='.(($this->pag)-1).'">&laquo; Anterior</a>';
                    
                endif;
            }
    
    
            if( $this->pag > 4 ):
            
                $start = ($this->pag-3);
            
                $PAGES = ($this->pag+3);
                
                if( $PAGES > $this->pages){
                    
                    $PAGES = $this->pages;
                    
                };
            
            else:
                
                $start = 1;
                
                $PAGES = $pag+4;
            
            endif;                 
        
            $max_pages = $this->pages;
    
            for( $i = $start; $i <= $PAGES ;$i++ ):
    
                if( $i <= $max_pages){
                    
                    if( $this->pag == $i ){
                        
                        $pagination[] = '<a href="?pag='.$i.'" class="number current">'.$i.'</a>';
    
                    } else {
                        
                        $pagination[] = '<a href="?pag='.$i.'" class="number">'.$i.'</a>';
    
                    }
                    
                }
                    
            endfor;
        
            if( $this->pag < $this->pages ){
                
                $pagination[] = '<a href="?pag='.($this->pag+1).'">Siguiente &raquo;</a>';
                
                $pagination[] = '<a href="?pag='.$this->pages.'">Ultima página &raquo;</a>';
                
            }
            
            return join(" ",$pagination);

        } 
        
    }
    
    
    protected function tableSelect($id){
        
        $sql = $this->sql[$id];
        
        if( $sql ):
        
            $r = array();
            
            $r[] = '<select name="">';
        
            while(list($k,$v)=each($sql)){
            
                $r[] = "<option value=\"{$v[$this->selectValue]}\">".utf8_encode($v[$this->selectText])."</option>";  
                
            }
            
            $r[] = '</select>';
            
        endif;
        
        return join(" ",$r);
        
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
