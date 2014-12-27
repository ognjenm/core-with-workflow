<?php namespace Telenok\Core\Workflow\TemplateMarker;

class System extends \Telenok\Core\Interfaces\Workflow\TemplateMarker {
    
    protected $key = 'system';

    public function getBlockItem()
    {
        return [
            'DATETIME' => 'Date and time', 
            'DATE' => 'Date only',
            'TIME' => 'Time only'
        ];
    }
    
}