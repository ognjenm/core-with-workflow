<?php

namespace Telenok\Core\Interfaces\Workflow;

class Flow extends \Telenok\Core\Interfaces\Workflow\Element {
    
    protected $code = 'flow';
    
    public function canGoNext()
    {
        return true;
    }
}