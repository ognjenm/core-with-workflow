<?php

namespace Telenok\Core\Widget\Html;

class Controller extends \Telenok\Core\Interfaces\Widget\Controller {

    protected $key = 'html';
    protected $parent = 'standart';

	public function getContent(\Illuminate\Support\Collection $structure = null)
	{
        if ($structure !== null && ($template = $structure->get('template')))
        {
            //$this->frontendView = $template;
            return \View::make($template, ['controller' => $this]);
        }
        else if ($m = $this->getWidgetModel())
        {
            //$this->frontendView = $m->template;
            return \View::make($m->template, ['controller' => $this]);
        }
	}

}

?>