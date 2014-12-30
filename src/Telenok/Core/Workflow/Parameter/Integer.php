<?php

namespace Telenok\Core\Workflow\Parameter;

class Integer extends \Telenok\Core\Interfaces\Workflow\Parameter {
    
    protected $key = 'integer';
    

    public function processDefault($controller = null, $model = null)
    {
        return intval($controller->processMarkerString($model->default_value));
    }
    
    public function processValue($controller = null, $model = null, $value = null)
    {
    }
}