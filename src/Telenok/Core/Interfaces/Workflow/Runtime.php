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
                    
                    if ($this->isEventForProcess($p, $event, $elements))
                    {
                        $this->threadCreateAndRun($p, $event);
                        
                        break;
                    }
				}
			}
		}
	}

    public function isEventForProcess(\Telenok\Core\Model\Workflow\Process $process = null, 
                                    \Telenok\Core\Interfaces\Workflow\Event $event = null, 
                                    \Illuminate\Support\Collection $elements = null)
    {
        foreach($process->event_object as $permanentId)
        { 
            foreach(array_get($process->process->all(), 'diagram.childShapes', []) as $action)
            { 
                if ($permanentId == $action['permanentId'])
                {
                    $a = $elements->get($action['stencil']['id'])
                                                ->make()
                                                ->setInput(array_get($process->process->all(), 'stencil.' . $action['permanentId'], []))
                                                ->setStencil($action);

                    if ($a->isEventForMe($event))
                    {
                        return true;
                    }
                }
            }
        }
    }
    
    public function canProcessing()
    {
        return (bool)\Config::get('app.workflow.enabled');
    }

    public function threadCreateAndRun(\App\Model\Telenok\Workflow\Process $process, \Telenok\Core\Interfaces\Workflow\Event $event = null, $parameter = [])
    { 
        if ($this->canProcessing())
        {
            \Telenok\Core\Workflow\Thread::make()->setEvent($event)->setModelProcess($process)->setParameter($parameter)->run($this); 
        }

        return $this;
    }

	public static function make() 
	{
		return new static;
	}
}

