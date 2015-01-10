<?php

namespace Telenok\Core\Workflow\Parameter;

class Integer extends \Telenok\Core\Interfaces\Workflow\Parameter {
    
    protected $key = 'integer';
	
    public function getValue($model = null, $value = null, $thread = null)
    {
        return intval(parent::getValue($model, $value, $thread));
    }
}