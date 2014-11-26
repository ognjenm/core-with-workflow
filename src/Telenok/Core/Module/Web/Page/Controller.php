<?php

namespace Telenok\Core\Module\Web\Page;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTabObject\Controller {

	protected $key = 'web-page';
	protected $parent = 'web';
	protected $presentation = 'tree-tab-object';
    protected $typeList = 'page';

    public function getGridId($key = 'gridId')
    {
        return "{$this->getPresentation()}-{$this->getTabKey()}-{$this->typeList}";
    } 
}

?>