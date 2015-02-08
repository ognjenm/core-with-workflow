<?php

namespace Telenok\Core\Workflow\Parameter;

class Text extends \Telenok\Core\Interfaces\Workflow\Parameter {
    
    protected $key = 'text';
	
    public function getValue($model = null, $value = null, $thread = null)
    {
        return strval(parent::getValue($model, $value, $thread));
    }
}