<?php

namespace Telenok\Core\Interfaces\Workflow;

class Parameter {

    use \Telenok\Core\Support\PackageLoad;

    protected $key = '';
    protected $languageDirectory = 'workflow-parameter';
    protected $view = '';

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($param = '')
    {
        $this->key = $param;
        
        return $this;
    }

    public function getView()
    {
        return $this->view ?: "core::workflow-parameter.{$this->getKey()}";
    }

    public function setView($param = '')
    {
        $this->view = $param;
        
        return $this;
    }
    
    
    
}