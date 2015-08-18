<?php
class PaginateSelectController extends SparkFormAppController {

	var $name = 'PaginateSelect';
	var $helpers = array('SparkForm.SparkForm');
	var $uses = array();
	var $components = array('RequestHandler');
	
	function admin_page(){
		if($this->RequestHandler->isAjax() || !empty($this->params['named']['ajax'])){
           $this->layout = 'ajax';
           $this->set('ajax',true);
		}
		$options = array();
		if(!empty($this->params['named']['model'])){
			$options['model'] = $this->params['named']['model'];
		}else{
			$this->cakeError('error404', array('message' => 'Model is not specified'));
		}
		
		$Model = ClassRegistry::init($options['model']);
		$Model->recursive = -1;
		
		$q = null;
		if(isset($this->params['named']['q']) && strlen(trim($this->params['named']['q'])) > 0) {
			$q = $this->params['named']['q'];
		} elseif(!empty($_POST['q'])) {
			$q = $_POST['q'];
			$this->params['named']['q'] = $q;
		}
					
		if($q !== null) {
			$search_fields = array(
				'title',
				'title_fre',
				'title_eng',
				'desc',
				'desc_fre',
				'desc_eng',
				'longdesc',
				'longdesc_fre',
				'longdesc_eng',
				'shortdesc',
				'shortdesc_fre',
				'shortdesc_eng',
				'text',
				'text_fre',
				'text_eng'
			);
			$search_cond = array();
			foreach($search_fields as $field){
				if($Model->hasField($field)){
					$search_cond[$field.' LIKE'] = '%'.$q.'%';
				}
			}
			$this->paginate['conditions']['OR'] = $search_cond;
		}
		
		$this->paginate['limit'] = 16;
		$options['options'] = $this->paginate($Model);
		
		$this->set('options', $options);
	}
	
}

?>