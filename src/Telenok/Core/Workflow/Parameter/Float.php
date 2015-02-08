<?php

namespace Telenok\Core\Workflow\Parameter;

class Float extends \Telenok\Core\Interfaces\Workflow\Parameter {
    
    protected $key = 'float';
	
    public function getValue($model = null, $value = null, $thread = null)
    {
        return floatval(parent::getValue($model, $value, $thread));
    }
}