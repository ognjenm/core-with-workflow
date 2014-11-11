<?php

namespace Telenok\Core\Interfaces\Workflow;

class Runtime {

	public function processEvent(\Telenok\Core\Interfaces\Workflow\Event $event = null)
	{       
        if ($this->canProcessing())
        {
			$processes = \Telenok\Workflow\Process::active()->get();
            $elements = \App::make('telenok.config')->getWorkflowElement();

			if (!$processes->isEmpty())
			{
				foreach($processes as $p)
				{
                    //$this->runExistingThread($p);
                    
					foreach($p->event_object as $permanentId)
					{ 
                        foreach($p->process->getDot('diagram.childShapes', []) as $action)
                        { 
                            if ($permanentId == $action['permanentId'])
                            {
                                $a = $elements->get($action['stencil']['id'])
                                                            ->make()
                                                            ->setInput($p->process->getDot('stencil.' . $action['permanentId'], []))
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

    public function threadCreateAndRun(\Telenok\Workflow\Process $process, \Telenok\Core\Interfaces\Workflow\Event $event = null)
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