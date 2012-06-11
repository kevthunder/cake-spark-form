<?php
class O2formHelper extends AppHelper {
	
	var $helpers = array('Html', 'Form', 'Javascript');
	
	var $customTypes = array('paginated_select','multiple','country','region','datepicker');
	
	var $preprocessors = array('null_checkbox');
	 
	 
	 
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
		$preprocessors = Configure::read('O2form.preprocessors');
		$preprocessors = array_merge($this->preprocessors, (array)$preprocessors);
		foreach($preprocessors as $objName => $processors){
			$obj =& $this->_getHelper($objName);
			if($obj && is_object($obj)){
				if(!is_array($processors)){
					$processors = array($processors);
				}
				foreach($processors as $processor){
					if(method_exists($obj,$processor)){
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
			$out .= $this->Form->input($fieldName, $options);
		}
		return $out;
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
	
	function null_checkbox($fieldName, $options = array()){
		if(isset($options['null_checkbox']) && $options['null_checkbox']!==false){
			$this->setEntity($fieldName);
			//$this->Javascript->link('jquery-1.3.2.min', false); 
			$this->Javascript->link('/o2form/js/o2form', null, array('inline'=>false)); 
			
			if(!isset($options['before'])){
				$options['before'] = '';
			}
			$options['before'] = $this->Form->hidden($fieldName,array('value'=>''))."\n".$options['before'];
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
		return $options;
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
					$("#'.$this->Form->domId($fieldName).'").datepicker('.json_encode($pickerOpt).');
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
		$html .= $this->Form->input($fieldName, $options);
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
		return $this->Form->input($fieldName, $opt);
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
				$cregions = array_intersect_key($selected,$selected);
			}
			if(!empty($cregions)){
				$regions[]= array(
					'label' => Geography::getLocalTerm('region',$key),
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
		}
		
		
		if(empty($opt['countrySelect'])){
			$opt['options'] = $allOpt;
			$opt['type'] = null;
			if($opt['other']){
				$opt['after'] = $this->Form->input($fieldName.'_other', $opt['other']);
			}
			$out = $this->Form->input($fieldName, $opt);
		}else{
			if(preg_match('/[_a-z0-9]/i',$opt['countrySelect'])){
				$opt['countrySelect'] = '#'.$this->Form->domId($opt['countrySelect']);
				$this->setEntity($fieldName);
			}
			$loadScript = true;
			$out = '';
			if($opt['empty']){
				$opt['empty'] = array_merge($defOpt['empty'],(array)$opt['empty']);
				$opt['empty']['options'] = $allOpt;
				$out .= $this->Form->input($fieldName, $opt['empty']);
			}
			foreach($regions as $region){
				$caseOpt = array_merge($opt['cases'],$region);
				$caseOpt['div']['rel'] = $region['rel'];
				unset($caseOpt['rel']);
				$out .= $this->Form->input($fieldName, $caseOpt);
			}
			if($opt['more']){
				$opt['more'] = array_merge($defOpt['more'],(array)$opt['more']);
				$out .= $this->Form->input($fieldName, $opt['more']);
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