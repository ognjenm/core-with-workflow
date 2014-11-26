<?php

namespace Telenok\Core\Interfaces\Presentation;

interface IPresentation {
    
    
    public function getPresentation();
    
    public function setPresentation($key);

    public function getPresentationView();
    
    public function setPresentationView($key);

    public function getPresentationContentView();
    
    public function setPresentationContentView($key);

    public function getActionParam();

    public function getPresentationContent();

    public function getContent();
}

?>