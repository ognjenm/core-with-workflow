<?php

namespace Telenok\Core\Workflow\Variable;

class Integer extends \Telenok\Core\Interfaces\Workflow\Variable {
    
    protected $key = 'integer';
	
    public function getValue($model = null, $value = null, $thread = null)
    {
        return intval(parent::getValue($model, $value, $thread));
    }
	
	
    public function setValue($model = null, $value = null, $thread = null)
    {
        return parent::setValue($model, intval($value), $thread);
    }
}