<?php

namespace src\Core\Controller;

class Controller {

	protected $viewPath;
	protected $template;

	public function render($view,$params=[]){
		ob_start();
		$entities = $params;
		require($this->viewPath . $view . '.php');
		$content = ob_get_clean();
		require($this->viewPath . '/' . 'templates/' . $this->template . '.php');
	}

}

