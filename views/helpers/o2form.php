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
		if ($options['label'] !== false) {
			$label = $options['label'];
			if (!is_array($label)) {
				$label = array('text'=>$label);
			}
			$labelText = null;
			if (isset($label['text'])) {
				$labelText = $label['text'];
				//unset($label['text']);
			}
			$labelElement = $this->Form->label($fieldName, $labelText, $label);
		}
		//debug($options);
		return $view->element('paginated_select',array('plugin'=>'o2form','options'=>$options,'label'=>$labelElement)); 
	}
	
	function multiple($fieldName, $options = array() ){
		if(array_key_exists('fields',$options)){
			$options['fields'] = $options;
		}
		$def = array(
			'table'=>array(
				'cellspacing'=>0
				'cellpadding'=>0
			)
		);
		//normalize and count
		$nbColls = 1;
		$options['fields'] = Set::normalize($options['fields']);
		foreach($tmp = $options['fields'] as $key => $field){
			if($field === false){
				unset($options['fields'][$key]);
			}else{
				if(!is_array($field) && !empty($field)){
					$field = array('type'=>$field);
				}
				if(!array_key_exists('label',$field)){
					$field['label'] = $this->defaultLabel($fieldName);
				}
				if(!empty($field['type']) && $field['type'] == 'hidden'){
				}else{
					$nbColls++
				}
			}
		}
		
		$html .= '			<table class="subItems" cellspacing="0" cellpadding="0">'."\n";
		$html .= '				<tr>'."\n";
		$html .= '					<th>'.__('Code',true).'</th>'."\n";
		$html .= '					<th>'.__('Label',true).'</th>'."\n";
		if(count($type['operators'])>1){
			$html .= '					<th>'.__('Operator',true).'</th>'."\n";
		}
		$html .= '					<th>'.__('Price',true).'</th>'."\n";
		$html .= '					<th>'.__('Delete',true).'</th>'."\n";
		$html .= '				</tr>'."\n";
		$html .= '				<tr>'."\n";
		$html .= '					<td>'."\n";
		$html .= '						'.$this->Form->input('SubProduct.'.$key.'.code',array('div'=>false,'label'=>false))."\n";
		$html .= '					</td>'."\n";
		$html .= '					<td>'."\n";
		$html .= '						'.$this->Form->input('SubProduct.'.$key.'.label',array('div'=>false,'label'=>false))."\n";
		$html .= '					</td>'."\n";
		if(count($type['operators'])>1){
			$html .= '					<td>'."\n";
			$html .= '						'.$this->Form->input('SubProduct.'.$key.'.operator',array('options'=>$type['operators'],'div'=>false,'label'=>false))."\n";
			$html .= '					</td>'."\n";
		}
		$html .= '					<td>'."\n";
		$html .= '						'.$this->Form->input('SubProduct.'.$key.'.price',array('div'=>false,'label'=>false))."\n";
		$html .= '					</td>'."\n";
		$html .= '					<td>'."\n";
		if(count($type['operators']==1)){
			$html .= '						'.$this->Form->input('SubProduct.'.$key.'.operator',array('type'=>'hidden','value'=>$type['operators'][0]))."\n";
		}
		$html .= '						<a href="#" class="btDelete">-</a>'."\n";
		$html .= '					</td>'."\n";
		$html .= '				</tr>'."\n";
		$html .= '			</table>'."\n";
		$html .= '		</div>'."\n";
		
		return $html;
	}
	
	function defaultLabel($fieldName){
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
}
?>