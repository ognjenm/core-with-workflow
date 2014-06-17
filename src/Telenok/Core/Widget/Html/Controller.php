<?php

namespace Telenok\Core\Widget\Html;

class Controller extends \Telenok\Core\Interfaces\Widget\Controller {

    protected $key = 'html';
    protected $parent = 'standart';

	public function getContent(\Illuminate\Support\Collection $structure = null)
	{
		$structure = $this->widgetModel ? $this->widgetModel->structure : $structure;
		
		$htmlCode = $structure->get('html_code');
		
		return array_get($htmlCode, \Config::get('app.locale'), \Config::get('app.localeDefault'));
	}

}

?>