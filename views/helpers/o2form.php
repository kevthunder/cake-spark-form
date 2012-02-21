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
			'table'=>array(
				'class'=>array('MultipleTable'),
				'cellspacing'=>0,
				'cellpadding'=>0,
			),
			'div'=>array(
				'class'=>array('MultipleInput'),
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
		//normalize and count
		$options['fields'] = Set::normalize($options['fields']);
		$opt = array_merge($defOpt,$options);
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
				if(!array_key_exists('label',$field)){
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
		$html = '';
		$html .= $this->labelFor($fieldName, $options);
		$html .= '<table'.$this->_parseAttributes($opt['table']).'>'."\n";
		$html .= '	<tr>'."\n";
		foreach($tmp = $opt['fields'] as $key => $field){
			if(empty($field['type']) || $field['type'] != 'hidden'){
				$html .= '		<th>'.$field['label'].'</th>'."\n";
				$opt['fields'][$key]['label'] = false;
			}
		}
		$html .= '		<th>'.__('Delete',true).'</th>'."\n";
		$html .= '	</tr>'."\n";
		for ($i = -1; $i < count($values); $i++) {
			$model = ($i == -1);
			$trOpt = $opt['tr'];
			if($model){
				$trOpt = Set::merge($trOpt,$opt['model']);
			}
			$html .= '	<tr'.$this->_parseAttributes($trOpt,array('fields')).'>'."\n";
			$index = $i;
			if($model){
				$index = "---i---";
			}
			foreach($tmp = $opt['fields'] as $key => $field){
				if(!empty($trOpt['fields'])){
					$field = array_merge($trOpt['fields'],$field);
				}
				if(empty($field['type']) || $field['type'] != 'hidden'){
					$html .= '		<td'.$this->_parseAttributes($opt['td']).'>'."\n";
					$html .= '			'.$this->input($fieldName.'.'.$index.'.'.$key,$field)."\n";
					$html .= '		</td>'."\n";
				}else{
					$hiddens[$key] = $field;
				}
			}
			$html .= '		<td'.$this->_parseAttributes($opt['tdAction']).'>'."\n";
			foreach($hiddens as $key => $field){
				if(!empty($trOpt['fields'])){
					$field = array_merge($trOpt['fields'],$field);
				}
				if($key == 'id'){
					$optDelete = $opt['deleteField'];
					if(!empty($trOpt['fields'])){
						$optDelete = array_merge($trOpt['fields'],$optDelete);
					}
					$html .= '			'.$this->input($fieldName.'.'.$index.'.delete',$optDelete)."\n";
					$field['spc'] = 'keyInput';
				}
				$html .= '			'.$this->input($fieldName.'.'.$index.'.'.$key,$field)."\n";
			}
			$html .= '			<a href="#" class="btDelete">-</a>'."\n";
			$html .= '		</td>'."\n";
			$html .= '	</tr>'."\n";
		}
		$html .= '	<tr'.$this->_parseAttributes($opt['trAction']).'>'."\n";
		$html .= '		<td colspan="'.$nbColls.'"><a href="#" class="btAdd">+</a></td>'."\n";
		$html .= '	</tr>'."\n";
		$html .= '</table>'."\n";
		
		if($opt['div'] !== false){
			$html = '<div'.$this->_parseAttributes($opt['div']).'>'.$html.'</div>';
		}
		
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