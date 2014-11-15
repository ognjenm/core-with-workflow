<?php

namespace Telenok\Core\Widget\Html;

class Controller extends \Telenok\Core\Interfaces\Widget\Controller {

    protected $key = 'html';
    protected $parent = 'standart';

	public function getContent(\Illuminate\Support\Collection $structure = null)
	{
        if ($structure->has('cache_time'))
        {
            $this->setCacheTime($structure->get('cache_time'));
        }
        
        if ($structure !== null && ($template = $structure->get('template')))
        {
            return \View::make($template, ['controller' => $this]);
        }
        else if ($m = $this->getWidgetModel())
        {
            return \View::make('widget.' . $m->getKey(), ['controller' => $this, 'frontEndController' => $this->getFrontEndController()]);
        }
	}

}

?>