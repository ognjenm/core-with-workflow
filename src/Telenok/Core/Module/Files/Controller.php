<?php

namespace Telenok\Core\Module\Files;

class Controller extends \Telenok\Core\Interfaces\Module\Controller {

    protected $key = 'files';
    protected $parent = false;
    protected $group = 'content';
    protected $icon = 'fa fa-file';
    
    public function getActionParam()
    {
        return '{}';
    }
    
    public function getTree()
    {
        return false;
    }
    
}

?>