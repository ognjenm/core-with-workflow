<?php

namespace Telenok\Core\Interfaces\Workflow;

class Thread {

    protected $id;
    protected $actions = [];
    protected $process;
    protected $modelProcess;
    protected $modelThread;
    protected $result = [];
    protected $event;

    public function initActions()
    {
        if ($this->getModelThread())
        {
            $elements = \App::make('telenok.config')->getWorkflowElement();
            $this->actions = \Illuminate\Support\Collection::make([]);

            foreach($this->getModelThread()->original_process->getDot('diagram.childShapes', []) as $action)
            {
                $this->actions->put($action['resourceId'], $elements->get($action['stencil']['id'])
                                                            ->make()
                                                            ->setThread($this)
                                                            ->setInput($this->getModelThread()->original_process->getDot('stencil.' . $action['permanentId'], []))
                                                            ->setStencil($action));
            }
        }
        else
        {
            throw new \Exception('Cant init actions');
        }
    }
    
    public function getActionByResourceId($resourceId = '')
    {
        return $this->actions->get($resourceId);
    }
    
    public function getActions()
    {
        return $this->actions;
    }
    
    public function getActiveElements()
    {
        $activeElements = \Illuminate\Support\Collection::make([]); 

        foreach($this->getActions() as $action)
        {
			if ($this->getModelThread()->processing_stage == 'started')
			{
                if ($action instanceof \Telenok\Core\Interfaces\Workflow\Point && $action->isEventForMe($this->getEvent()))
                {
                    $activeElements->push($action->getId());
                }
			}
			else if ($this->getModelThread()->processing_stage == 'processing')
			{
                $activeElements = $this->getModelThread()->processing_stencil;
			} 
			else if ($this->getModelThread()->processing_stage == 'finished')
			{
			} 
        }

        return $activeElements;
    }

    public function setProcessingStageFinished()
    {
        $this->setProcessingStage('finished');
    }

    public function isProcessingStageFinished()
    {
        return $this->getModelThread()->processing_stage == 'finished';
    }

    public function run(\Telenok\Core\Interfaces\Workflow\Runtime $runtime)
    { 
        if (!$this->getModelThread() && !$this->modelProcess)
        {
            throw new \Exception('Please, set modelProcess');
        }

        if (!$this->getModelThread() && $this->modelProcess)
        {
            $this->modelThread = (new \Telenok\Workflow\Thread())->storeOrUpdate([
				'title' => $this->modelProcess->title,
				'original_process' => $this->modelProcess->process,
				'active' => 1,
				'thread_workflow_process' => $this->modelProcess->getKey(),
				'processing_stage' => 'started',
			], false, false);
        }

        $this->initActions();

        $activeElements = $this->getActiveElements();

        if (!$activeElements->isEmpty())
        {
            $this->getModelThread()->storeOrUpdate([
                    'processing_stencil' => $activeElements,
                    'processing_stage' => 'processing',
                ], false, false);
        }

        $i = 10;
        $sleepAll = [];
        $diff = ['some-value'];

        while(!$this->isProcessingStageFinished() && !empty($diff) && $i--)
        { 
            foreach($activeElements as $id)
            {
                $el = $this->getActionByResourceId($id);
                
                $el->process();

                if ($el->isProcessSleeping())
                {
                    $sleepAll[] = $id;
                }
            }

            // all actions sleeping
            $diff = array_diff($activeElements->all(), $sleepAll);

            $activeElements = $this->getActiveElements();
        }

        return $this;
    }
    
    public function getEventResource()
    {
        if ($this->getEvent())
        {
            return $this->getEvent()->getResource();
        }
        else
        {
            $firstEventLog = $this->getModelThread()->processing_stencil_log->first();

            if (!empty($firstEventLog))
            {
                return $this->actions->get($firstEventLog['resourceId'])->getResourceFromLog($firstEventLog);
            }
        }
    }

    public function addLog($action, $data = [])
    {
        $log = $this->getModelThread()->processing_stencil_log;

        $logStencil = $log->get($action->getId(), []);

        if (!isset($data['time']))
        {
            $data['time'] = \Carbon\Carbon::now();
        }

        if (!isset($data['key']))
        {
            $data['key'] = $action->getKey();
        }

        if (!isset($data['resourceId']))
        {
            $data['resourceId'] = $action->getId();
        }
        
        $logStencil[] = $data;

        $log->put($action->getId(), $logStencil);

        $this->getModelThread()->processing_stencil_log = $log;

        $this->getModelThread()->save();

        return $this;
    }

    public function addProcessingStencil($resourceId = '')
    {
        $list = $this->getModelThread()->processing_stencil;

        $list->push($resourceId);

        $this->getModelThread()->processing_stencil = $list;

        $this->getModelThread()->save();

        return $this;
    }    

    public function removeProcessingStencil($resourceId = '')
    {
        $list = $this->getModelThread()->processing_stencil->reject(function($item) use ($resourceId) { return $item == $resourceId;});

        $this->getModelThread()->processing_stencil = $list;

        $this->getModelThread()->save();

        return $this;
    }    

    public function setProcessingStage($param)
    {
        $this->getModelThread()->processing_stage = $param;

        $this->getModelThread()->save();

        return $this;
    }
    
    public function setModelThread(\Telenok\Core\Model\Workflow\Thread $param)
    {
        $this->modelThread = $param;
        
        return $this;
    }

    public function getModelThread()
    {
        return $this->modelThread;
    }
	
    public function setModelProcess(\Telenok\Workflow\Process $param)
    {
        $this->modelProcess = $param;
        
        return $this;
    }

    public function getModelProcess()
    {
        return $this->modelProcess;
    }
	
    public function setEvent(\Telenok\Core\Workflow\Event $param)
    {
        $this->event = $param;
        
        return $this;
    }

    public function getEvent()
    {
        return $this->event;
    }  

	public static function make() 
	{
		return new static;
	}
}

?>