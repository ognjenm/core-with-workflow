<?php

namespace Telenok\Core\Interfaces\Workflow;

class TemplateMarker extends \Illuminate\Routing\Controller implements \Telenok\Core\Interfaces\IRequest {

    use \Telenok\Core\Support\PackageLoad;

    protected $key = '';
    protected $languageDirectory = 'workflow-template-marker';
    protected $view = 'core::module/workflow-template-marker.select-marker';
    protected $process;
    protected $thread;
    protected $package = '';
    protected $request; 
    protected $availableAtStart = true; 
    
    public function getAvailableAtStart()
    {
        return $this->availableAtStart;
    }
    
    public function setAvailableAtStart($param = '')
    {
        $this->availableAtStart = $param;
        
        return $this;
    }
	
    public function setKey($param = '')
    {
        $this->key = $param;
        
        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }
    
    public function setProcess($param = '')
    {
        $this->process = $param;
        
        return $this;
    }

    public function getProcess()
    {
        return $this->process;
    }
    
    public function setThread($param = '')
    {
        $this->thread = $param;
        
        return $this;
    }

    public function getThread()
    {
        return $this->thread;
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

	/*
	 * @param string
	 * @param \Telenok\Core\Workflow\Thread
	 */
    public function processMarkerString($string = '', $thread = null)
    {
        return $string;
    }
}