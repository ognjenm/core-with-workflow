<?php

namespace Telenok\Core\Module\Web\Domain;

class Controller extends \Telenok\Core\Interfaces\Module\Objects\Controller {

	protected $key = 'web-domain';
	protected $parent = 'web';
	protected $presentation = 'tree-tab-object';
    protected $typeList = 'domain';
}

?>