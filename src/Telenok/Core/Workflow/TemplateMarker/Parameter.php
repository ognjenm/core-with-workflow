<?php namespace Telenok\Core\Workflow\TemplateMarker;

class Parameter extends \Telenok\Core\Interfaces\Workflow\TemplateMarker {

    protected $key = 'parameter';

    public function getBlockItem()
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
    
	/*
	 * @param string
	 * @param \Telenok\Core\Workflow\Thread
	 */
    public function processMarkerString($string = '', $thread = null)
    {
		if (!$thread)
		{
			return $string;
		}

		$parameters = $thread->getModelThread()->original_parameter->keyBy('code');
		$collectionParameters = app('telenok.config')->getWorkflowParameter();

		foreach ($thread->getParameter() as $code => $value) 
		{
			$param = $parameters->get($code, false);

			$string = str_replace('{=' . strtoupper($this->getKey() . ':' . $code) . '}', '"' . $collectionParameters->get($param['key'])->toString($value) . '"', $string);
        } 

        return $string;
    }
}