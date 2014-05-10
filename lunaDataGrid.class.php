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
     * @var bool $ajax print table
     */
    var $ajax;
    
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
     * @var string checkbox print table
     */
    var $checkbox;
    
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
    
    /*
     * @var string private key
     */
    
    var $privateKey;
    
    function __construct($table, $where = null , $order = null, $colData=array('*'), $colName=array('*'), $ajax = false, $pagination = false, $html=true, $max = 10, $colRelationship = null , $editableField = null, $checkbox = null,$privateKey='id') {
        
        $this->html = $html;
        
        $this->table = $table;
        
        $this->where = $where;
        
        $this->order = $order;
        
        $this->colData = $colData;
        
        $this->colName = $colName;
        
        $this->ajax = $ajax;
        
        $this->pagination = $pagination;
        
        $this->max = $max;
        
        $this->buttons = array();
        
        $this->relTable = array();
        
        $this->selectValue = "id";
        
        $this->selectText = "description";
        
        $this->colRelationship = $colRelationship;
        
        $this->editableField = $editableField;
        
        $this->checkbox = $checkbox;
        
        $this->file = "index.php?";
        
        $this->pk = $privateKey;
        
        
    }
    
    /*
     * function checkEmpty
     */
    
    private function checkEmpty(){
        
        $this->title = ($this->title)?$this->title:null;
        
        $this->colName = ($this->colName)?$this->colName:null;
        
        $this->colData = ($this->colData)?$this->colData:array("*");
        
        $this->where = ($this->where)?$this->where:null;
        
        $this->order = ($this->order)?$this->order:null;
        
        $this->pagination = ($this->pagination == "true")?true:false;
        
        $this->max = ($this->max)?$this->max:false;
        
        $this->checkbox = ($this->checkbox == "true")?true:false;
        
        $this->editableField = ($this->editableField)?$this->editableField:null;
        
        $this->colRelationship = ($this->colRelationship)?$this->colRelationship:null;

        
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
        
        if( $this->ajax ):
        
            $this->checkEmpty();
            
        endif;
        
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
            
            if( $_POST['pag'] >= 1 ){
                
                $pag = (int) ($_POST['pag'])?($_POST['pag']-1):0;
                
                $this->pag  = (int) (isset($_POST['pag']))?$_POST['pag']:'1';
                
            } else {
                
                $pag = (int) ($_GET['pag'])?($_GET['pag']-1):0;
                
                $this->pag  = (int) (isset($_GET['pag']))?$_GET['pag']:'1';
                
            }
            
            $def = (int) ($pag >= 1)?($pag*$this->max):'0';
            
            $db->unset_consult();
            
            $limit = "LIMIT {$def},{$this->max}";
            
            $this->pages = $pages;
            
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
        
        $consult = "SELECT ".join(',',$this->colData)." FROM ".$this->table." {$where} {$order} {$limit}"; 
        
        $db->add_consult($consult);
        
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
    
    public function build(){
        
        $this->data = $this->data();
        
        $html = '<table class="lunaGrid">';
        
        if( !$this->ajax ):
            
            $form = '<form action="?" method="post" enctype="form-data/multipart">';
            $form.= '<input type="text" class="lunaFinder">';
            $form.= '</form>';
        
        else:
        
            $form = '<input type="text" class="lunaFinder">';
        
        endif;
        
        $html.= ($this->title)?'<caption>'.$form.''.$this->title.'</caption>':'';
        
        /* THEAD */
        
        $html.= '<thead><tr>';
        
            if($this->checkbox){
                    
                $html.= '<th></th>';
                    
            }
        
        if( $this->colName ):
        
            foreach($this->colName as $row){
                
                $html.= "<th>$row</th>";
                
            }
        
        endif;
        
        $html.= '</tr></thead>';
        
        /* /THEAD */
        
        /* TBODY */
        
        $hmtl.= '<tbody>';
        
            while( list($k,$v) = each($this->data) ):
            
                $html.= '<tr>';
                
                if($this->checkbox){
                    
                    $html.= '<td><input type="checkbox" id="checkbox-'.$k.'-'.$v[$this->pk].'" name="id['.$v[$this->pk].']" value="1" class="regular-checkbox"><label for="checkbox-'.$k.'-'.$v[$this->pk].'"></label></td>';
                    
                }
            
                foreach($v as $raw => $row){
                    
                    if( is_string($this->relTable[$raw]) ):
                    
                        $html.= "<td>".$this->tableSelect($this->relTable[$raw],$v[$this->pk])."</td>";
                    
                    elseif( $this->editableField[$raw] ):
                        
                        switch($this->editableField[$raw]){
                            
                            case "input":
                                
                                $html.= '<td><input name="'.$raw.'['.$v[$this->pk].']" type="text" value="'.$row.'" ></td>';
                                
                            break;
                        
                            case "textarea":
                                
                                $html.= '<td><textarea name="'.$raw.'['.$v[$this->pk].']">'.$row.'</textarea></td>';
                                
                            break;
                            
                        }

                    else:
                    
                        $html.= "<td>$row</td>";
                    
                    endif;
                    
                }
                
                        
                if( count($this->buttons) >= 1){
                
                    $html.='<td class="buttons">';
                    
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
            
            $i = ($this->checkbox)?1:0;
            
            $html.= '<tfoot>';
            
            $html.= '<tr><td class="pagination" colspan="'.(count($this->colName)+$i).'">'.$this->pagination().'</td></tr>';
            
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
    
    private function pagination() {
        
        if( $this->pages > 1){
            
            if( $this->ajax ){
                
                $file = "#";
                
            } else {
                
                $file = $this->file;
            }
            
                    
            $pagination = array();
    
            if( $this->pag > 1 ){
                
                $pagination[] = '<a href="'.$file.'" data-pag="0">&laquo; First</a> ';
                
                if( $pag != 2):
                
                    $pagination[] = '<a href="'.$file.'pag='.(($this->pag)-1).'" data-pag="'.(($this->pag)-1).'">&laquo; Back</a>';
                    
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
                        
                        $pagination[] = '<a href="'.$file.'pag='.$i.'" data-pag="'.$i.'" class="number current">'.$i.'</a>';
    
                    } else {
                        
                        $pagination[] = '<a href="'.$file.'pag='.$i.'" data-pag="'.$i.'" class="number">'.$i.'</a>';
    
                    }
                    
                }
                    
            endfor;
        
            if( $this->pag < $this->pages ){
                
                $pagination[] = '<a href="'.$file.'pag='.($this->pag+1).'" data-pag="'.($this->pag+1).'">Next &raquo;</a>';
                
                $pagination[] = '<a href="'.$file.'pag='.$this->pages.'"  data-pag="'.$this->pages.'">Last &raquo;</a>';
                
            }
            
            return join(" ",$pagination);

        } 
        
    }
    
    
    protected function tableSelect($id,$parent = null){
        
        $sql = $this->sql[$id];
        
        $rel = array_search($id,$this->relTable);
        
        if( $sql ):
        
            $r = array();
            
            $r[] = "<label class=\"sel\"><select name=\"{$rel}[{$parent}]\">";
        
            while(list($k,$v)=each($sql)){
            
                $r[] = "<option value=\"{$v[$this->selectValue]}\">".utf8_encode($v[$this->selectText])."</option>";  
                
            }
            
            $r[] = '</select></label>';
            
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
