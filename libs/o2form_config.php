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
			config('plugins/newsletter');
			$config = Configure::read('Newsletter');
			$config = Set::merge($_this->defaultConfig,$config);
			Configure::write('Newsletter',$config);
			$_this->loaded = true;
		}
		if(!empty($path)){
			return Configure::read('Newsletter'.($path!==true?'.'.$path:''));
		}
	}
	
}
?>