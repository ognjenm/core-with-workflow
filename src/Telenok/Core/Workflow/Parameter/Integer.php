<?php

namespace Telenok\Core\Workflow\Parameter;

class Integer extends \Telenok\Core\Interfaces\Workflow\Parameter {
    
    protected $key = 'integer';
    

    public function processInitDefault($controller = null, $model = null)
    {
        return intval($controller->processMarkerString($model->default_value));
    }
    
    public function processInitValue($controller = null, $model = null, $value = null)
    {
        return $value;
    }
}