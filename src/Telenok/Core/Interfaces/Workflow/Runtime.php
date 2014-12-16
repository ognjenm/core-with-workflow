<?php

namespace Telenok\Core\Interfaces\Workflow;

class Runtime {

	public function processEvent(\Telenok\Core\Interfaces\Workflow\Event $event = null)
	{       
        if ($this->canProcessing())
        {
			$processes = \App\Model\Telenok\Workflow\Process::active()->get();
            $elements = app('telenok.config')->getWorkflowElement();

			if (!$processes->isEmpty())
			{
				foreach($processes as $p)
				{
                    //$this->runExistingThread($p);
                    
					foreach($p->event_object as $permanentId)
					{ 
                        foreach(array_get($p->process->all(), 'diagram.childShapes', []) as $action)
                        { 
                            if ($permanentId == $action['permanentId'])
                            {
                                $a = $elements->get($action['stencil']['id'])
                                                            ->make()
                                                            ->setInput(array_get($p->process->all(), 'stencil.' . $action['permanentId'], []))
                                                            ->setStencil($action);

                                if ($a->isEventForMe($event))
                                {
                                    $this->threadCreateAndRun($p, $event);

                                    break 2;
                                }
                            }
                        }
					}
				}
			}
		}
	}

    public function canProcessing()
    {
        return (bool)\Config::get('app.workflow.enabled');
    }

    public function threadCreateAndRun(\App\Model\Telenok\Workflow\Process $process, \Telenok\Core\Interfaces\Workflow\Event $event = null)
    { 
        if ($this->canProcessing())
        {
            \Telenok\Core\Workflow\Thread::make()->setEvent($event)->setModelProcess($process)->run($this); 
        }

        return $this;
    }

	public static function make() 
	{
		return new static;
	}
}

?>