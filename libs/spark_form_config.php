<?php
class O2formConfig extends Object {
	/*
		App::import('Lib', 'O2form.O2formConfig');
		O2formConfig::load();
	*/
	
	var $loaded = false;
	var $defaultConfig = array(
		'loadJs'=>array(
			'jquery-ui' => '/O2form/js/jquery-ui-1.8.20.custom.min',
		),
		'loadCss'=>array(
			'jquery-ui' => '/O2form/css/jquery-ui/jquery-ui-1.8.20.custom',
		)
	);
	
	//$_this =& O2formConfig::getInstance();
	function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] =& new O2formConfig();
		}
		return $instance[0];
	}
	
	function load($path = true){
	
	
		$_this =& O2formConfig::getInstance();
		if(!$_this->loaded){
			config('plugins/o2form');
			$config = Configure::read('O2form');
			$config = Set::merge($_this->defaultConfig,$config);
			Configure::write('O2form',$config);
			$_this->loaded = true;
			
			if( in_array('Filter',App::Objects('plugin')) ) {
				Configure::Write('Filter.FormHelper','O2form.O2form');
			}
		}
		if(!empty($path)){
			return Configure::read('O2form'.($path!==true?'.'.$path:''));
		}
	}
	
}
?>