<?php

namespace Telenok\Core\Interfaces\Workflow;

class Parameter {

    use \Telenok\Core\Support\PackageLoad;

    protected $key = '';
    protected $languageDirectory = 'workflow-parameter';
    protected $package = '';
    protected $formFieldView = '';

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($param = '')
    {
        $this->key = $param;
        
        return $this;
    }

    public function getName()
    {
        return $this->LL('name');
    }

    public function getFormFieldView()
    {
        return $this->formFieldView ?: "core::workflow-parameter.{$this->getKey()}.field";
    }

    public function setFormFieldView($param = '')
    {
        $this->formFieldView = $param;
        
        return $this;
    }

    public function getFormFieldContent($controller = null, $model = null, $uniqueId = null)
    { 
        return view($this->getFormFieldView(), array(
                'parentController' => $controller,
                'controller' => $this,
                'model' => $model,
                'uniqueId' => $uniqueId,
            ))->render();
    }

    
}