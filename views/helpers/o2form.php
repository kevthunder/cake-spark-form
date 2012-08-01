<?php
App::import('Helper','Form');

class O2formHelper extends FormHelper {
	
	var $helpers = array('Html', 'Form', 'Javascript');
	
	var $customTypes = array('paginated_select','multiple','country','region','datepicker', 'radio');
	
	var $preprocessors = array('null_checkbox','label_aposition');
	
	var $preprocConflicts = array('region'=>array('label_aposition'));
	 
	 
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
		$this->_checkValidationErrors();
		$this->setEntity($fieldName);
		$out = '';
		$preprocessors = Configure::read('O2form.preprocessors');
		$preprocessors = array_merge($this->preprocessors, (array)$preprocessors);
		foreach($preprocessors as $objName => $processors){
			$obj =& $this->_getHelper($objName);
			if($obj && is_object($obj)){
				if(!is_array($processors)){
					$processors = array($processors);
				}
				foreach($processors as $processor){
					if(method_exists($obj,$processor) && 
						(empty($options['type']) || empty($this->preprocConflicts[$options['type']]) || !in_array($processor,$this->preprocConflicts[$options['type']]))
					){
						$res = $obj->{$processor}($fieldName, $options);
						if(is_array($res)){
							$options = $res;
						}elseif($res === false){
							return null;
						}
					}
				}
			}
		}
		$customTypes = Configure::read('O2form.customTypes');
		$customTypes = array_merge($this->customTypes, (array)$customTypes);
		$customTypes = array_flip(Set::flatten($customTypes,'>'));
		if(!empty($options['type']) && isset($customTypes[$options['type']])){
			$objName = $customTypes[$options['type']];
			$objName = explode('>', $objName, 2);
			$objName = $objName[0];
			$obj =& $this->_getHelper($objName);
			if($obj && is_object($obj) && method_exists($obj,$options['type'])){
				$out .= $obj->{$options['type']}($fieldName, $options);
			}
		}else{
			$out .= parent::input($fieldName, $options);
		}
		return $out;
	}
	
	////////////////////////// Preprocessors //////////////////////////
	
	function null_checkbox($fieldName, $options = array()){
		if(isset($options['null_checkbox']) && $options['null_checkbox']!==false){
			$defOpt = array(
				'type'=>'checkbox',
				'label'=>'Null',
				'div'=>array('class'=>'disable_checkbox_div clearfix'),
				'class'=>'disable_checkbox',
				'pos'=>'after',
				'hide'=>false,
				'stealLabel'=>false,
				'revert'=>false,
			);
			$presets = array(
				'before' => array(
					'label'=>false,
					'pos'=>'before',
					'class'=>'disable_checkbox before',
					'div'=>false,
					'hide'=>true,
					'stealLabel'=>true,
					'revert'=>true,
				)
			);
			$local = array('pos','preset','stealLabel');
			$nullOpt = array();
			if($options['null_checkbox']!==true){
				if(is_array($options['null_checkbox'])){
					$nullOpt = $options['null_checkbox'];
				}elseif(in_array($options['null_checkbox'],array_keys($presets))){
					$nullOpt['preset'] = $options['null_checkbox'];
				}else{
					$nullOpt['label'] = $options['null_checkbox'];
				}
			}
			if(!empty($nullOpt['preset']) && !empty($presets[$nullOpt['preset']]) ){
				$nullOpt = array_merge($presets[$nullOpt['preset']],$nullOpt);
			}
			$nullOpt = array_merge($defOpt,$nullOpt);
			
			
			$this->setEntity($fieldName);
			//$this->Javascript->link('jquery-1.3.2.min', false); 
			$this->Javascript->link('/o2form/js/o2form', null, array('inline'=>false)); 
			
			if(!isset($options['before'])){
				$options['before'] = '';
			}
			$options['before'] = $this->hidden($fieldName,array('value'=>''))."\n".$options['before'];
			if(!isset($options['after'])){
				$options['after'] = '';
			}
			$checked = false;
			if(!empty($this->data[$this->model()]) && array_key_exists($this->field(),$this->data[$this->model()])){
				if( (is_null($this->data[$this->model()][$this->field()]) || empty($this->data[$this->model()][$this->field()])) == !$nullOpt['revert']){
					$checked = true;
				}
			}
			$nullOpt['checked'] = $checked;
			if($nullOpt['revert']){
				$nullOpt['before'] = $this->hidden($fieldName.'_null',array('value'=>'1','id'=>$this->domId($fieldName.'_null').'_'));
				$nullOpt['value'] = '[[ZERO]]';
				$nullOpt['hiddenField'] = false;
			}
			$options[$nullOpt['pos']] .= str_replace('[[ZERO]]','0',$this->input($fieldName.'_null',array_diff_key($nullOpt,array_flip($local))));
			if($nullOpt['stealLabel'] && empty($nullOpt['label'])){
				if(!empty($options['label']) && !is_array($options['label'])){
					$options['label'] = array('text'=>$options['label']);
				}
				$options['label']['for'] = $this->domId($fieldName.'_null');
			}
		}
		return $options;
	}
	
	function label_aposition($fieldName, $options = array()){
		if(!empty($options['label']) && is_array($options['label'])){
			if(!isset($options['label']['text'])){
				$options['label']['text'] = $this->defaultLabelText($fieldName, $options);
			}
			if(!empty($options['label']['text'])){
				if(!empty($options['label']['prefix'])){
					$options['label']['text'] = $options['label']['prefix'].$options['label']['text'];
				}
				if(!empty($options['label']['suffix'])){
					$options['label']['text'] .= $options['label']['suffix'];
				}
			}
			unset($options['label']['suffix']);
			unset($options['label']['prefix']);
		}
		return $options;
	}
	
	////////////////////////// Custom Types //////////////////////////
	
	function radio($fieldName, $options = array() ){
		$extend = false;
		if(!empty($options['options'])){
			$extendedOpt = array_filter($options['options'],'is_array');
			if(count($extendedOpt)){
				$extend = true;
			}
		}
		if(!$extend){
			return $this->Form->input($fieldName, $options);
		}
		
		
		$opt = $this->_initInputField($fieldName, $options);
		unset($opt['div']);
		$legend = false;

		if (isset($opt['legend'])) {
			$legend = $opt['legend'];
			unset($opt['legend']);
		} elseif (count($options['options']) > 1) {
			$legend = __(Inflector::humanize($this->field()), true);
		}
		$label = true;

		if (isset($opt['label'])) {
			$label = $opt['label'];
			unset($opt['label']);
		}
		$inbetween = null;

		if (isset($opt['separator'])) {
			$inbetween = $opt['separator'];
			unset($opt['separator']);
		}

		if (isset($opt['value'])) {
			$value = $opt['value'];
		} else {
			$value =  $this->value($fieldName);
		}
		$out = array();

		$hiddenField = isset($opt['hiddenField']) ? $opt['hiddenField'] : true;
		unset($opt['hiddenField']);

		foreach ($options['options'] as $optValue => $optConf) {
			$optionsHere = array('value' => $optValue);
			if(!is_array($optConf)){
				$optConf = array('label'=>$optConf);
			}
			if(!is_array($optConf['label'])){
				$optConf['label'] = array('text'=>$optConf['label']);
			}
			
			if (isset($value) && $optValue == $value) {
				$optionsHere['checked'] = 'checked';
			}
			$parsedOptions = $this->_parseAttributes(
				array_merge($opt,$optConf,$optionsHere),
				array('name', 'type', 'id', 'label'), '', ' '
			);
			if(empty($optConf['id'])){
				$tagName = Inflector::camelize(
					$opt['id'] . '_' . Inflector::underscore($optValue)
				);
			}else{
				$tagName = $optConf['id'];
			}

			$optTitle = $optConf['label']['text'];
			if ($label) {
				$titleAttr = $this->_parseAttributes($optConf['label'],array('text','for'));
				$optTitle =  sprintf($this->Html->tags['label'], $tagName, $titleAttr, $optConf['label']['text']);
			}
			$out[] =  sprintf(
				$this->Html->tags['radio'], $opt['name'],
				$tagName, $parsedOptions, $optTitle
			);
		}
		$hidden = null;

		if ($hiddenField) {
			if (!isset($value) || $value === '') {
				$hidden = $this->hidden($fieldName, array(
					'id' => $opt['id'] . '_', 'value' => '', 'name' => $opt['name']
				));
			}
		}
		$out = $hidden . implode($inbetween, $out);

		if ($legend) {
			$out = sprintf(
				$this->Html->tags['fieldset'], '',
				sprintf($this->Html->tags['legend'], $legend) . $out
			);
		}
		
		
		$out = $this->_divWrapper($fieldName, $out ,$options);
		
		return $out;
	}
	
	function select($fieldName, $options = array(), $selected = null, $attributes = array()) {
		if (isset($attributes) && array_key_exists('multiple', $attributes) && $attributes['multiple'] === false) {
			unset($attributes['multiple']);
		}
		return parent::select($fieldName, $options, $selected, $attributes);
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
		$options = $this->__name($options,$fieldName);
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
			if(empty($options['type']) || $options['type'] != 'multiple'){
				$options['fields'] = $options;
			}else{
				$options['fields'] = array('val'=>array());
			}
			
		}
		$defOpt = array(
			'mode' => 'table',
			'id'=>$this->domId($fieldName),
			'elem' => null,
			'elemVars' => array('labels'),
			'toAttributes' => array('div'),
			'independantLabels' => false,
			'colClass' => true,
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
			),
			'min' => 0, 
			'add' => array(
				'label' => '+',
			),
			'delete' => array(
				'label' => '-',
				'colLabel' => __('Delete',true),
			),
			'mainContainer' => null,
			'toMainContainer' => array('id','min','max'),
		);
		$modeOpt = array(
			'table' => array(
				'elem' => 'multiple_table',
				'elemVars' => array('labels'),
				'toAttributes' => array('table','td','tdAction','trAction','div'),
				'independantLabels' => true,
				'mainContainer' => 'table',
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
		
		$optionsAlias = array(
			'minRows' => 'min',
			'addLabel' => 'add.label',
			'deleteLabel' => 'delete.label',
			'deleteColLabel' => 'delete.colLabel',
		);
		$options = $this->_parseOptAliases($options ,$optionsAlias);
		
		$opt = array_merge($defOpt,$options);
		if(!empty($opt['mode']) && !empty($modeOpt[$opt['mode']])){
			$opt = array_merge($defOpt,$modeOpt[$opt['mode']],$options);
		}
		$nbColls = 1;
		
		$this->setEntity($fieldName);
		
		$fullFieldName = $fieldName;
		if($fullFieldName != ucfirst($fullFieldName) && substr($fullFieldName,0,strlen($this->model())+1) != $this->model().'.'){
			$fullFieldName = $this->model().'.'.$fullFieldName;
		}
		
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
				
				if(!empty($field['class'])){
					$field['class'] = (array)$field['class'];
				}
				if($opt['colClass']){
					$field['class'][] = $this->domId($fullFieldName.'.'.$key);
				}
				$field['class'] = implode(' ',$field['class']);
				
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
					if(is_array($field['label'])){
						$field = $this->label_aposition($key,$field);
						$labels[] = $field['label']['text'];
					}else{
						$labels[] = $field['label'];
					}
					$opt['fields'][$key]['label'] = false;
				}
			}
		}
		$lines = array();
		$nbRow = count($values);
		$nbRow = max($nbRow,$opt['min']);
		for ($i = -1; $i < $nbRow; $i++) {
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
					if($key != '__val__'){
						$subFieldName = $fullFieldName.'.'.$index.'.'.$key;
					}else{
						$subFieldName = $fullFieldName.'.'.$index;
					}
					$line['inputs'][$subFieldName] = $field;
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
		
		
		
		if(!empty($opt['mainContainer']) && !empty($opt['toMainContainer'])){
			$opt[$opt['mainContainer']] = array_merge($opt[$opt['mainContainer']],array_intersect_key($opt,array_flip($opt['toMainContainer'])));
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
	
	function datepicker($fieldName, $options = array()){
		$html = '';
		$html .= $this->loadAsset('jquery-ui','css');
		$html .= $this->loadAsset('jquery-ui','js');
		$html .= $this->loadAsset('datepickerDef');
		$pickerOpt = array(
			'changeMonth' => true,
			'changeYear' => true,
			'dateFormat' => 'yy-mm-dd',
		);
		$toPicker = array('changeMonth','changeYear','defaultDate','firstDay','maxDate','minDate','yearRange','dateFormat');
		$pickerOpt = array_merge($pickerOpt,array_intersect_key($options,array_flip($toPicker)));
		$options = array_diff_key($options,array_flip($toPicker));
		if(!empty($options['jsOpt'])){
			$pickerOpt = array_merge($pickerOpt,$options['jsOpt']);
			unset($options['jsOpt']);
		}
		$this->Html->scriptBlock('
			(function( $ ) {
				$(function(){
					$("#'.$this->domId($fieldName).'").datepicker('.json_encode($pickerOpt).');
				})
			})( jQuery );
			
		',array('inline'=>false));
		$options['type'] = 'text';
		$val = $this->value($options);
		$val = $val['value'];
		if(!empty($val)){
			$time = strtotime($val);
			if($time){
				$options['value'] = date('Y-m-d',$time);
			}else{
				$options['value'] = '';
			}
		}
		if(!array_key_exists('div',$options) || ($options['div'] !== false && !array_key_exists('class',$options['div']))){
			$options['div']['class'] = 'input text datepicker';
		}
		$html .= $this->input($fieldName, $options);
		return $html;
	}
	
	function country($fieldName, $options = array()){
		$defOpt = array(
			'options' => true,
		);
		$opt = array_merge($defOpt,$options);
		App::import('Lib', 'O2form.Geography');
		$countries = Geography::getCountries();
		if($opt['options'] === true){
			$opt['options'] = $countries;
		}else{
			$selected = Set::normalize($opt['options']);
			$countries = array_intersect_key($countries,$selected);
			$countries = array_merge($countries, array_filter($selected));
			$opt['options'] = $countries;
		}
		$opt['type'] = null;
		return $this->input($fieldName, $opt);
	}
	function region($fieldName, $options = array()){
		$defOpt = array(
			'countrySelect' => null,
			'options' => true,
			'other' => array(
				'label'=> __('Other',true),
				'optLabel'=> true,
				'div'=>array('class'=>'input otherInput text')
			),
			'div'=>array('class' => 'input extendedSelect'),
		);
		if(!empty($options['countrySelect'])){
			$defOpt = array_merge($defOpt,array(
				'empty'=>array(
					'div'=>array('class' => 'input extendedSelectCase extendedSelectEmpty select'),
					'other'=>true,
				),
				'cases'=>array(
					'div'=>array('class' => 'input extendedSelectCase select','style'=>'display:none'),
					'disabled' => 'disabled',
				),
				'more'=>array(
					'div'=>array('class' => 'input extendedSelectCase extendedSelectMore text','style'=>'display:none'),
					'disabled' => 'disabled',
				),
			));
		}else{
			$defOpt = array_merge($defOpt,array(
				'div'=>array('class' => 'input extendedSelect select'),
			));
		}
		$opt = array_merge($defOpt,$options);
		$loadScript = false;
		$out = null;
		App::import('Lib', 'O2form.Geography');
		$regions = array();
		$needMore = false;
		if($opt['options'] === true){
			$selected = Geography::getCountries(array_keys($regions));
		}else{
			$selected = Set::normalize($opt['options']);
		}
		$opt['options'] = array();
		foreach($selected as $key => $country){
			if(!empty($country) && is_string($country)){
				$label = $country;
			}elseif(is_array($country) && !empty($country['label'])){
				$label = $country['label'];
			}else{
				$label = Geography::getCountry($key);
			}
			$cregions = Geography::getRegions($key);
			if(is_array($country) && !empty($country['regions']) && !is_array($country['regions'])){
				$selected = Set::normalize($country['regions']);
				$cregions = array_intersect_key($cregions,$selected);
			}
			if(!empty($cregions)){
				$regions[]= array(
					'label' => array('text'=>Geography::getLocalTerm('region',$key)),
					'rel' => $key,
					'options' => $cregions,
				);
				$opt['options'][$label] = $cregions;
			}else{
				$needMore = true;
			}
		}
		if(!$needMore){
			$opt['more'] = false;
		}
		$allOpt = $opt['options'];
		if($opt['other']){
			$loadScript = true;
			if($opt['other'] === true){
				$opt['other'] = array();
			}elseif(!is_array($opt['other'])){
				$opt['other'] = array('label'=>$opt['other']);
			}
			$opt['other'] = array_merge($defOpt['other'],$opt['other']);
			if($opt['other']['optLabel'] === true){
				$opt['other']['optLabel'] = $opt['other']['label'];
			}
			$allOpt['other'] = $opt['other']['optLabel'];
			unset($opt['other']['optLabel']);
		}
		$localOpt = array('countrySelect', 'options', 'other', 'empty', 'cases', 'more', 'type');
		$fowardOpt = array_diff_key($options,array_flip($localOpt));
		
		if(empty($opt['countrySelect'])){
			$opt['options'] = $allOpt;
			$opt['type'] = null;
			if($opt['other']){
				$opt['after'] = $this->input($fieldName.'_other', $opt['other']);
			}
			$out = $this->input($fieldName, $opt);
		}else{
			if(preg_match('/[_a-z0-9]/i',$opt['countrySelect'])){
				$opt['countrySelect'] = '#'.$this->domId($opt['countrySelect']);
				$this->setEntity($fieldName);
			}
			$loadScript = true;
			$out = '';
			if($opt['empty']){
				$opt['empty'] = array_merge($defOpt['empty'],(array)$opt['empty'],$fowardOpt);
				$opt['empty']['options'] = $allOpt;
				if($opt['empty']['other']){
					$opt['empty']['after'] = $this->input($fieldName.'_other', $opt['other']);
				}
				$out .= $this->input($fieldName, $opt['empty']);
			}
			foreach($regions as $region){
				$caseOpt = Set::merge($opt['cases'],$region,$fowardOpt);
				$caseOpt['div']['rel'] = $region['rel'];
				unset($caseOpt['rel']);
				$out .= $this->input($fieldName, $caseOpt);
			}
			if($opt['more']){
				$opt['more'] = array_merge($defOpt['more'],(array)$opt['more']);
				$out .= $this->input($fieldName, $opt['more']);
			}
			if($opt['div']){
				$opt['div']['linked'] = $opt['countrySelect'];
				$out = '<div'.$this->_parseAttributes($opt['div']).'>'.$out.'</div>';
			}
		}
		
		
		if($loadScript){
			$this->Html->script('/o2form/js/region_select',array('inline'=>false));
		}
		return $out;
	}
	
	
	////////////////////////// Other functions //////////////////////////
	
	
	function defaultLabelText($fieldName, $options = null){
		if(!empty($options) && array_key_exists('label',$options) && $options['label'] !== true){
			if(!is_array($options['label'])){
				return $options['label'];
			}elseif(array_key_exists('text',$options['label'])){
				return $options['label']['text'];
			}
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
		if (!isset($options['label']) || $options['label'] !== false) {
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
			return $this->label($fieldName, $labelText, $label);
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
	
	function _parseOptAliases($opt,$aliases){
		foreach($aliases as $a => $path){
			if(Set::check($opt,$a)){
				Set::insert($opt,$path,Set::extract($a,$opt));
				Set::remove($opt,$a);
			}
		}
		return $opt;
	}
	
	function _checkValidationErrors(){
		if(empty($this->validationErrorsChecked)){
			if(empty($this->validationErrors)){
				$this->validationErrors = $this->Form->validationErrors;
			}
			if(!empty($this->validationErrors)){
				foreach($this->validationErrors as $model => $errors){
					$checked = array();
					foreach($errors as $field => $err){
						if(strpos($field,'.')!==false){
							$checked = Set::insert($checked,$field,$err);
						}else{
							$checked[$field] = $err;
						}
					}
					$this->validationErrors[$model] = $checked;
				}
			}
			//debug($this->validationErrors);
			$this->Form->validationErrors = $this->validationErrors;
		}
		$this->validationErrorsChecked = true;
	}
	
	function _getHelper($objName){
		$obj = null;
		if(is_numeric($objName)){
			$obj =& $this;
		}else{
			$objFullName = $objName;
			$plugin = false;
			$split = explode($objName,'.',2);
			if(count($split)>1){
				$plugin = $split[0];
				$objName = $split[1];
			}
			$view =& ClassRegistry::getObject('view');
			if(!empty($view->{$objName})){
				$obj =& $view->{$objName};
			}
		}
		return $obj;
	}
	
	function _divWrapper($fieldName, $output ,$options = array() ){
		$divOptions = array();
		$div = $this->_extractOption('div', $options, true);
		$modelKey = $this->model();
		$fieldKey = $this->field();
		if (!empty($div)) {
			$divOptions['class'] = 'input';
			$divOptions = $this->addClass($divOptions, $options['type']);
			if (is_string($div)) {
				$divOptions['class'] = $div;
			} elseif (is_array($div)) {
				$divOptions = array_merge($divOptions, $div);
			}
			if (
				isset($this->fieldset[$modelKey]) &&
				in_array($fieldKey, $this->fieldset[$modelKey]['validates'])
			) {
				$divOptions = $this->addClass($divOptions, 'required');
			}
			if (!isset($divOptions['tag'])) {
				$divOptions['tag'] = 'div';
			}
		}
		
		
		if (!empty($divOptions['tag'])) {
			$tag = $divOptions['tag'];
			unset($divOptions['tag']);
			$output = $this->Html->tag($tag, $output, $divOptions);
		}
		return $output;
	}
	function _parseAttributes($options, $exclude = null, $insertBefore = ' ', $insertAfter = null){
		$options = $this->normalizeAttributesOpt($options);
		return parent::_parseAttributes($options, $exclude, $insertBefore, $insertAfter);
	}
	
	function loadAsset($alias,$type='js',$inline = false,$default=null){
		static $loaded = array();
		if(empty($loaded[$type][$alias])){
			if(method_exists($this,'_'.$alias.'Asset')){
				$loaded[$type][$alias] = 1;
				$res = $this->{'_'.$alias.'Asset'}($inline);
				return $res;
			}
			$url = $default;
			$res = '';
			App::import('Lib', 'O2form.O2formConfig');
			$conf = O2formConfig::load('load'.ucfirst($type).'.'.$alias);
			if($conf){
				$url = $conf;
			}
			if($url){
				$loaded[$type][$alias] = 1;
				if(method_exists($this,'_'.$type.'Asset')){
					$res = $this->{'_'.$type.'Asset'}($url,$inline);
				}
				return $res;
			}
		}
		return '';
	}
	
	function _jsAsset($url,$inline){
		$res = $this->Html->script($url,array('inline'=>$inline));
		if($inline){
			return $res;
		}
	}
	function _cssAsset($url,$inline){
		$res = $this->Html->css($url,null,array('inline'=>$inline));
		if($inline){
			return $res;
		}
	}
	function _datepickerDefAsset($inline){
		$pickerOpt = array(
			'currentText' => __('Today',true),
			'closeText' => __('Done',true),
			'dayNames' => array(__("Sunday",true), __("Monday",true), __("Tuesday",true), __("Wednesday",true), __("Thursday",true), __("Friday",true), __("Saturday",true)),
			'dayNamesMin' => array(__("Su",true), __("Mo",true), __("Tu",true), __("We",true), __("Th",true), __("Fr",true), __("Sa",true)),
			'dayNamesShort' => array(__("Sun",true), __("Mon",true), __("Tue",true), __("Wed",true), __("Thu",true), __("Fri",true), __("Sat",true)),
			'monthNames' => array(__("January",true), __("February",true), __("March",true), __("April",true), __("May",true), __("June",true), __("July",true), __("August",true), __("September",true), __("October",true), __("November",true), __("December",true)),
			'monthNamesShort' => array(__("Jan",true), __("Feb",true), __("Mar",true), __("Apr",true), __("May",true), __("Jun",true), __("Jul",true), __("Aug",true), __("Sep",true), __("Oct",true), __("Nov",true), __("Dec",true)),
			'nextText' => __('Next',true),
			'prevText' => __('Prev',true),
		);
		$res = $this->Html->scriptBlock('
			(function( $ ) {
				$.datepicker.setDefaults( '.json_encode($pickerOpt).' );
			})( jQuery );
		',array('inline'=>$inline));
		
		if($inline){
			return $res;
		}
	}
	
}
?>