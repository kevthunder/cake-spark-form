<?php
class O2formHelper extends AppHelper {
	
	var $helpers = array('Html', 'Form', 'Javascript');
	
	var $custom_types = array('paginated_select');
	 
	 
	 
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
}
?>