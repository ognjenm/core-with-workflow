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

    public function processMarkerString($string = '')
    {
        $datetime = \Carbon\Carbon::now();
        
        foreach(array_keys($this->getBlockItem()) as $key)
        {
            switch($key)
            {
                case 'DATETIME':

                    $string = str_replace('{=' . strtoupper($this->getKey() . ':' . $key) . '}', '"' . $datetime->toDateTimeString() . '"', $string);

                    break;
                
                case 'DATE':

                    $string = str_replace('{=' . strtoupper($this->getKey() . ':' . $key) . '}', '"' . $datetime->toDateString() . '"', $string);

                    break;
                
                case 'TIME':
                    
                    $string = str_replace('{=' . strtoupper($this->getKey() . ':' . $key) . '}', '"' . $datetime->toTimeString() . '"', $string);

                    break;
            }
        } 

        return $string;
    }
}