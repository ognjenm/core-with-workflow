<?php

namespace Telenok\Core\Interfaces\Controller\Backend;

abstract class Controller extends \Illuminate\Routing\Controller implements \Telenok\Core\Interfaces\IRequest {

    use \Telenok\Core\Support\PackageLoad;
    
    protected $key = '';
    protected $package = '';
    protected $languageDirectory = 'controller';
    protected $request; 

    public function getName()
    {
        return $this->LL('name');
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getContent()
    {
        return '';
    }
    
    public function setRequest(\Illuminate\Http\Request $request = null)
    {
        $this->request = $request;
        
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }
}