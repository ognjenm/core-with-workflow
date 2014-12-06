<?php

namespace Telenok\Core\Widget\Html;

class Controller extends \Telenok\Core\Interfaces\Widget\Controller {

    protected $key = 'html';
    protected $parent = 'standart';

	public function getContent($structure = null)
	{
        $content = '';

        if ($structure !== null && $structure->has('cache_time'))
        {
            $this->setCacheTime($structure->get('cache_time'));
        }
        
        if (($content = $this->getCachedContent()) !== false)
        {
            return $content;
        }

        if ($structure !== null && ($view = $structure->get('view')))
        {
            $content = view($view, ['controller' => $this])->render();
        }
        else if ($m = $this->getWidgetModel())
        {
            $content = view('widget.' . $m->getKey(), ['controller' => $this, 'frontendController' => $this->getFrontendController()])->render();
        }

        $this->setCachedContent($content);

        return $content;
	}

}

?>