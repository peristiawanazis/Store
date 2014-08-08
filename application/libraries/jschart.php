<?php
/* 
 * Title    : Jschart
 * version  : 0.1 alfa
 * 
 */
class Jschart{
    private $Chartype;
    private $Jsondata=NULL;
    private $Jsonaxislabels=NULL;
    private $Jsonlegend=NULL;
    private $Jsonoption=NULL;
    private $Charttitle=NULL;
    private $Appendto=NULL;

    function JSchart($Args=array()){
        $this->obj =& get_instance();
        if(count($Args)<2) { trigger_error('JSchart need 2 parameter', E_USER_ERROR); } else   {
            $this->Chartype=$Args['charttype'];
            $this->Appendto=$Args['target'];
        }
    }
    function load_data($Loadmodel=FALSE,$Data=array()){
        if(!$Loadmodel){
            $this->Jsondata=json_encode($Data);
        }   else    {
           // print_r($Data);
            if(!array_key_exists('model',$Data)||!array_key_exists('function', $Data)) trigger_error('Array argument/parameter in load_data() error, array keys for this argument must be [\'model\'] & [\'function\']', E_USER_ERROR);
            $this->obj->load->model($Data['model']);
           // echo 'dskfdks';
            $this->Jsondata=json_encode($this->obj->$Data['model']->$Data['function']());
            //echo $this->Jsondata;
        }
        return $this;
    }
    function axis_labels($Loadmodel=FALSE,$Data=array()){
        if(!$Loadmodel){
            $this->Jsonaxislabels=json_encode($Data);
            //echo $this->Jsonaxislabels;
        }   else    {
            if(!array_key_exists('model', $Data)||!array_key_exists('function', $Data)) trigger_error('Array argument/parameter in axis_labels() error,  array keys for this argument must be [\'model\'] & [\'function\']', E_USER_ERROR);
            $this->obj->load->model($Data['model']);
            $this->Jsonaxislabels=json_encode($this->obj->$Data['model']->$Data['function']());
        }
        //if($Axisstep) $this->Jsonoption['axis_step'];
        return $this;
    }
    function load_legend($Loadmodel=FALSE, $Data=array()){
        if(!$Loadmodel){
            $this->Jsonlegend=json_encode($Data);
        }   else {
            if(!array_key_exists('model', $Data)||!array_key_exists('function', $Data)) trigger_error('Array argument/parameter in load_legend() error,  array keys for this argument must be [\'model\'] & [\'function\']', E_USER_ERROR);
            $this->obj->load->model($Data['model']);
            $this->Jsonlegend=json_encode($this->obj->$Data['model']->$Data['function']());
        }
        return $this;
    }
    function set_option($Options){
        $Optionlist=array('canvas_width'=>'width',
            'canvas_height'=>'height',
            'chart_colors'=>'colors',
            //'text_colors'=>'textColors',
            'parse_direction'=>'parseDirection',
            'pie_margin'=>'pieMargin',
            'pie_label_position'=>'pieLabelPos',
            'line_weight'=>'lineweight',
            'bar_group_margin'=>'barGroupMargin',
            'bar_margin'=>'barMargin',
            'y_interval'=>'yLabelInterval'
        );
        $Opt='';
        foreach($Options as $Key=>$Values){
            if(array_key_exists($Key,$Optionlist)){
                $Opt.=', '.$Optionlist[$Key].':'.$Values;
            }
        }
        $this->Jsonoption=$Opt;
        return $this;
    }

    function set_title($Title=NULL){
        $this->Charttitle=$Title;
        return $this;
    }
    function render(){
        $Js= '<script type="text/javascript">';
        $Js.='$(function(){';
        $Js.='var data={}; ';
        $Js.='data.db='.$this->Jsondata.'; ';
        $Js.='data.axis='.$this->Jsonaxislabels.'; ';
        $Js.='data.legend='.$this->Jsonlegend.'; ';
        $Js.='$(\''.$this->Appendto.'\').visualize({type:\''.$this->Chartype.'\''.$this->Jsonoption.', rowdata:data, title:\''.$this->Charttitle.'\'})';
        $Js.='});';
        $Js.='</script>';
        return $Js;
    }
}

?>
