<?php namespace Telenok\Core\Workflow\TemplateMarker;

class Parameter extends \Telenok\Core\Interfaces\Workflow\TemplateMarker {

    protected $key = 'parameter';

    public function getBlockItem($thread = null)
    {
		$return = [];
		
		if ($this->getProcess())
		{
			foreach($this->getProcess()->parameter->all() as $p)
			{
				$return[strtoupper($p->code)] = $p->translate('title');
			}
		}

        return $return;
    }

    public function processMarkerString($string = '', $thread = null)
    {
        $datetime = \Carbon\Carbon::now();

        foreach(array_keys($this->getBlockItem($thread)) as $key)
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