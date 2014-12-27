<?php

namespace Telenok\Core\Interfaces\Workflow;

class TemplateMarker extends \Illuminate\Routing\Controller implements \Telenok\Core\Interfaces\IRequest {

    use \Telenok\Core\Support\PackageLoad;

    protected $key = '';
    protected $languageDirectory = 'workflow-template-marker';
    protected $view = 'core::module.workflow-process.select-template-marker';
    protected $package = '';
    protected $request; 

    
    
    public function setKey($param = '')
    {
        $this->key = $param;
        
        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setRequest(\Illuminate\Http\Request $param = null)
    {
        $this->request = $param;
        
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }
    
    public function setView($param = null)
    {
        $this->view = $param;
        
        return $this;
    }

    public function getView()
    {
        return $this->view;
    }
    
    public function getBlockContent()
    {
        return view($this->getView(), [
            'item' => $this->getBlockItem(),
            'controller' => $this,
        ])->render();
    }
    
    public function getBlockItem()
    {
        return [];
    }
    
    public function processMaker($maker = '')
    {
    }
}