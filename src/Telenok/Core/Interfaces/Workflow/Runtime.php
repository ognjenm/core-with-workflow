<?php

namespace Telenok\Core\Interfaces\Workflow;

class Runtime {

	public function processEvent(\Telenok\Core\Interfaces\Workflow\Event $event = null)
	{
        if ($this->canProcessing())
        {
			$processes = \Telenok\Workflow\Process::active()->get();

			if (!$processes->isEmpty())
			{
				foreach($processes as $p)
				{
                    //$this->runExistingThread($p);
                    
					foreach($p->event_object as $eventCode => $points)
					{
						if (array_get($points, $event->getEventCode()))
						{
							$this->threadCreateAndRun($p, $event);

							break;
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