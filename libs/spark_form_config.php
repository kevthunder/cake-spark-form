<?php
class SparkFormConfig extends Object {
	/*
		App::import('Lib', 'SparkForm.SparkFormConfig');
		SparkFormConfig::load();
	*/
	
	var $loaded = false;
	var $defaultConfig = array(
		'loadJs'=>array(
			'jquery-ui' => '/spark_form/js/jquery-ui-1.8.20.custom.min',
		),
		'loadCss'=>array(
			'jquery-ui' => '/spark_form/css/jquery-ui/jquery-ui-1.8.20.custom',
		)
	);
	
	//$_this =& SparkFormConfig::getInstance();
	function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] =& new SparkFormConfig();
		}
		return $instance[0];
	}
	
	function load($path = true){
	
	
		$_this =& SparkFormConfig::getInstance();
		if(!$_this->loaded){
			config('plugins/spark_form');
			$config = Configure::read('SparkForm');
			$config = Set::merge($_this->defaultConfig,$config);
			Configure::write('SparkForm',$config);
			$_this->loaded = true;
			
			if( in_array('Filter',App::Objects('plugin')) ) {
				Configure::Write('Filter.FormHelper','SparkForm.SparkForm');
			}
		}
		if(!empty($path)){
			return Configure::read('SparkForm'.($path!==true?'.'.$path:''));
		}
	}
	
}
?>