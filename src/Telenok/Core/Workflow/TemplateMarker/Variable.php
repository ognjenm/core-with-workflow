<?php namespace Telenok\Core\Workflow\TemplateMarker;

class Variable extends \Telenok\Core\Interfaces\Workflow\TemplateMarker {

    protected $key = 'variable';
    protected $availableAtStart = false;

    public function getBlockItem()
    {
		$return = [];

		if ($this->getProcess())
		{
			foreach($this->getProcess()->variable()->active()->get() as $p)
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

		$parameters = $thread->getModelThread()->original_variable->keyBy('code');
		$collectionVariables = app('telenok.config')->getWorkflowVariable();

		foreach ($thread->getVariable() as $code => $value) 
		{
			$param = $parameters->get($code, false);

			$string = str_replace('{=' . strtoupper($this->getKey() . ':' . $code) . '}', '"' . $collectionVariables->get($param['key'])->toString($param, $value) . '"', $string);
        } 

        return $string;
    }
}