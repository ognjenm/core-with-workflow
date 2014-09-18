<?php

namespace Telenok\Core\Interfaces\Presentation\Simple;

abstract class Controller extends \Telenok\Core\Interfaces\Module\Controller {

    protected $presentation = 'simple';
    protected $presentationView = '';
    protected $presentationContentView = '';
    
    public function getPresentation()
    {
        return $this->presentation;
    } 
    
    public function setPresentation($key)
    {
        $this->presentation = $key;
        
        return $this;
    } 

    public function getPresentationView()
    {
        return $this->presentationView ?: "core::presentation.simple.presentation";
    } 
    
    public function setPresentationView($key)
    {
        $this->presentationView = $key;
        
        return $this;
    } 

    public function getPresentationContentView()
    {
        return $this->presentationContentView ?: "{$this->getPackage()}::module.{$this->getKey()}.content";
    }
    
    public function setPresentationContentView($key)
    {
        $this->presentationContentView = $key;
        
        return $this;
    } 

    public function getActionParam()
    { 
        return json_encode(array(
            'presentation' => $this->getPresentation(),
			'presentationModuleKey' => $this->getPresentationModuleKey(),
            'presentationContent' => $this->getPresentationContent(),
        ));
    }

    public function getPresentationContent()
    {
        return \View::make($this->getPresentationView(), array(
            'controller' => $this,
            'presentation' => $this->getPresentation(),
            'content' => $this->getContent(),
            'key' => $this->getKey(),
            'breadcrumbs' => $this->getBreadcrumbs(),
            'pageHeader' => $this->getPageHeader(),
        ))->render();
    }

    public function getContent()
    {
        return \View::make($this->getPresentationContentView(), array(
            'controller' => $this,
            'uniqueId' => str_random(),                 
            'success' => false, 
        ))->render();
    }
}

?>