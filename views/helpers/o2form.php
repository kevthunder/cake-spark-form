<?php
class O2formHelper extends AppHelper {
	
	var $helpers = array('Html', 'Form', 'Javascript');
	
	var $custom_types = array('paginated_select','multiple');
	 
	 
	 
/**
 * 
 *
 * exemple option null_checkbox :
 * view :
 *   echo $this->O2form->input('end',array('null_checkbox'=>__('Never',true)));
 * controller :
 *   if(!empty($this->data['Job']['end_null'])){
 *      $this->data['Job']['end'] = null;
 *   }
 *
 */
	function input($fieldName, $options = array() ){
		$this->setEntity($fieldName);
		$out = '';
		if(isset($options['null_checkbox']) && $options['null_checkbox']!==false){
			$this->setEntity($fieldName);
			//$this->Javascript->link('jquery-1.3.2.min', false); 
			$this->Javascript->link('/o2form/js/o2form', null, array('inline'=>false)); 
			
			$out .= $this->Form->hidden($fieldName,array('value'=>''))."\n";
			if($options['null_checkbox']!==true){
				$label = $options['null_checkbox'];
			}else{
				$label = __('Null',true);
			}
			if(!isset($options['after'])){
				$options['after'] = '';
			}
			$checked = false;
			if(!empty($this->data[$this->model()]) && array_key_exists($this->field(),$this->data[$this->model()])){
				if(is_null($this->data[$this->model()][$this->field()])){
					$checked = true;
				}else if(empty($this->data[$this->model()][$this->field()])){
					$checked = true;
				}
			}
			$options['after'] .= $this->Form->input($fieldName.'_null',array('type'=>'checkbox','label'=>$label,'div'=>array('class'=>'disable_checkbox_div clearfix'),'class'=>'disable_checkbox','checked'=>$checked));
		}
		if(!empty($options['type']) && in_array($options['type'],$this->custom_types)){
			$out .= $this->{$options['type']}($fieldName, $options);
		}else{
			$out .= $this->Form->input($fieldName, $options);
		}
		return $out;
	}
	
	function paginated_select($fieldName, $options = array() ){
		$view =& ClassRegistry::getObject('view');
		if (!isset($options['options'])) {
			$view =& ClassRegistry::getObject('view');
			$varName = Inflector::variable(
				Inflector::pluralize(preg_replace('/_id$/', '', $this->field()))
			);
			$varOptions = $view->getVar($varName);
			if (is_array($varOptions)) {
				$options['options'] = $varOptions;
			}
		}
		if (!isset($options['model'])) {
			if(!empty($options['options'][0]) && is_array($options['options'][0])){
				reset($options['options'][0]);
				$gessModel = key($options['options'][0]);
				if(is_array($options['options'][0][$gessModel]) && !in_array($this->model(),$options['options'][0])){
					$options['model'] = $gessModel;
				}
			}
		}
		if (!isset($options['selected'])) {
			if (isset($options['value'])) {
				$options['selected'] = $options['value'];
			}else{
				if($this->model() == $this->field()){
					$options['selected'] = $this->data[$this->model()];
				}else{
					$options['selected'] = $this->data[$this->model()][$this->field()];
				}
			}
		}
		$default_option = array(
			'options' => array(),
			'label' => null,
			'model' => $this->model(),
			'field' => $this->field(),
			'provider' => 'default',
			'fieldName' => $fieldName
		);
		$options = array_merge($default_option,$options);
		$selected_ids = array();
		$options = $this->Form->__name($options,$fieldName);
		if($options['provider'] == 'default'){
			$options['provider'] = array(
					'plugin'=>'o2form',
					'controller'=>'paginate_select',
					'model'=>$options['model'],
					'action'=>'page'
				);
		}
		if(!empty($options['selected'])){
			foreach($options['selected'] as $select){
				$selected_ids[] = $select['id'];
			}
		}
		$labelElement = $this->labelFor($fieldName, $options);
		//debug($options);
		return $view->element('paginated_select',array('plugin'=>'o2form','options'=>$options,'label'=>$labelElement)); 
	}
	
	function multiple($fieldName, $options = array() ){
		//debug($options);
		$this->Html->script('/o2form/js/multiple',array('inline'=>false));
		$this->Html->css('/o2form/css/multiple',null,array('inline'=>false));
		if(!array_key_exists('fields',$options)){
			$options['fields'] = $options;
		}
		$defOpt = array(
			'mode' => 'table',
			'elem' => null,
			'elemVars' => array('labels'),
			'toAttributes' => array('div'),
			'independantLabels' => false,
			'div'=>array(
				'class'=>array('MultipleInput'),
			),
			'model'=>array(
				'class'=>array('modelLine'),
				'fields'=>array('disabled'=>true),
			),
			'deleteField'=>array(
				'spc' => 'deleteInput',
				'type' => 'hidden',
			)
		);
		$modeOpt = array(
			'table' => array(
				'elem' => 'multiple_table',
				'elemVars' => array('labels'),
				'toAttributes' => array('table','td','tdAction','trAction','div'),
				'independantLabels' => true,
				'table'=>array(
					'class'=>array('MultipleTable'),
					'cellspacing'=>0,
					'cellpadding'=>0,
				),
				'tr'=>array(
					'class'=>array('line'),
				),
				'trAction'=>array(
					'class'=>array('actionLine'),
				),
				'td'=>array(
				),
				'tdAction'=>array(
					'class'=>array('actionCell'),
				)
			)
		);
		//normalize and count
		$options['fields'] = Set::normalize($options['fields']);
		$opt = array_merge($defOpt,$options);
		if(!empty($opt['mode']) && !empty($modeOpt[$opt['mode']])){
			$opt = array_merge($defOpt,$modeOpt[$opt['mode']],$options);
		}
		$nbColls = 1;
		
		$values = current($this->value());
		if(empty($values)){
			$values = array();
		}
		foreach($tmp = $opt['fields'] as $key => $field){
			if($field === false){
				unset($opt['fields'][$key]);
			}else{
				if(empty($field)){
					$field = array();
				}
				if(!is_array($field)){
					$field = array('type'=>$field);
				}
				$def = array(
					'div'=>false
				);
				$field = array_merge($def,$field);
				if(empty($field['type']) && $key == 'id'){
					$field['type'] = 'hidden';
				}
				if($opt['independantLabels'] && !array_key_exists('label',$field)){
					$field['label'] = $this->defaultLabelText($key);
				}
				if(!empty($field['type']) && $field['type'] == 'hidden'){
				}else{
					$nbColls++;
				}
				$opt['fields'][$key] = $field;
			}
		}
		$hiddens = array();
		if($opt['independantLabels']){
			$labels = array();
			foreach($tmp = $opt['fields'] as $key => $field){
				if(empty($field['type']) || $field['type'] != 'hidden'){
					$labels[] = $field['label'];
					$opt['fields'][$key]['label'] = false;
				}
			}
		}
		$lines = array();
		for ($i = -1; $i < count($values); $i++) {
			$line = array();
			$model = ($i == -1);
			$trOpt = $opt['tr'];
			if($model){
				$trOpt = Set::merge($trOpt,$opt['model']);
			}
			$line['tr'] = $this->_parseAttributes($trOpt,array('fields'));
			$index = $i;
			if($model){
				$index = "---i---";
			}
			foreach($tmp = $opt['fields'] as $key => $field){
				if(!empty($trOpt['fields'])){
					$field = array_merge($trOpt['fields'],$field);
				}
				if(empty($field['type']) || $field['type'] != 'hidden'){
					$line['inputs'][$fieldName.'.'.$index.'.'.$key] = $field;
				}else{
					$hiddens[$key] = $field;
				}
			}
			foreach($hiddens as $key => $field){
				if(!empty($trOpt['fields'])){
					$field = array_merge($trOpt['fields'],$field);
				}
				if($key == 'id'){
					$optDelete = $opt['deleteField'];
					if(!empty($trOpt['fields'])){
						$optDelete = array_merge($trOpt['fields'],$optDelete);
					}
					$line['hidden'][$fieldName.'.'.$index.'.delete'] = $optDelete;
					$field['spc'] = 'keyInput';
				}
				$line['hidden'][$fieldName.'.'.$index.'.'.$key] = $field;
			}
			$lines[] = $line;
		}
		
		$elemsAttr = array();
		foreach(Set::normalize($opt['toAttributes']) as $key => $val){
			if(array_key_exists($key,$opt) && $opt[$key] !== false){
				$elemsAttr[$key] = $this->_parseAttributes($opt[$key]);
			}else{
				$elemsAttr[$key] = false;
			}
		}
		
		$view =& ClassRegistry::getObject('view');
		$elemOpt = array('plugin'=>'o2form','fieldName'=>$fieldName,'lines'=>$lines,'elemsAttr'=>$elemsAttr,'options'=>$opt);
		$elemOpt = array_merge($elemOpt,compact($opt['elemVars']));
		$html = $view->element($opt['elem'],$elemOpt);
		
		return $html;
	}
	
	function defaultLabelText($fieldName, $options = null){
		if(!empty($options) && array_key_exists('label',$options) && $options['label'] !== true){
			return $options['label'];
		}
		if (strpos($fieldName, '.') !== false) {
			$text = array_pop(explode('.', $fieldName));
		} else {
			$text = $fieldName;
		}
		if (substr($text, -3) == '_id') {
			$text = substr($text, 0, strlen($text) - 3);
		}
		$text = __(Inflector::humanize(Inflector::underscore($text)), true);
		
				
		return $text;
	}
	
	
	
	function labelFor($fieldName, $options){
		if (empty($options['label']) || $options['label'] !== false) {
			$label = array();
			if (!empty($options['label'])){
				$label = $options['label'];
			}
			if (!is_array($label)) {
				$label = array('text'=>$label);
			}
			$labelText = null;
			if (isset($label['text'])) {
				$labelText = $label['text'];
				//unset($label['text']);
			}
			return $this->Form->label($fieldName, $labelText, $label);
		}
		return null;
	}
	
	function normalizeAttributesOpt($options, $exclude = null){
		if(array_key_exists('class',$options) && is_array($options['class'])){
			$options['class'] = implode(' ',$options['class']);
		}
		if(!empty($exclude)){
			$options = array_diff_key($options,array_flip($exclude));
		}
		return $options;
	}
	
	function _parseAttributes($options, $exclude = null, $insertBefore = ' ', $insertAfter = null){
		$options = $this->normalizeAttributesOpt($options);
		return parent::_parseAttributes($options, $exclude, $insertBefore, $insertAfter);
	}
}
?>