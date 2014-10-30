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
        if ($this->modelThread)
        {
            $elements = \App::make('telenok.config')->getWorkflowElement();
            
            $childShapes = 'diagram.childShapes';
            
            foreach ($this->modelThread->original_process->get($childShapes, []) as $key => $action)
            {
                $this->actions[$action['resourceId']] = $elements->get($action['stencil']['id'])
                                                            ->make()
                                                            ->setProcess($this->modelThread->original_process)
                                                            ->setThread($this)
                                                            ->setInput($this->modelThread->original_process->get('stencil.' . $action['resourceId'], []))
                                                            ->setStencil($action); 
            }
        }
        else
        {
            throw new \Exception('Cant init actions');
        }
    }
    
    public function getActionByResourceId($resourceId = '')
    {
        return array_get($this->actions, $resourceId, false);
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
			if ($this->modelThread->processing_stage == 'started')
			{
                if ($action instanceof \Telenok\Core\Interfaces\Workflow\Point && $action->isEventForMe($this->getEvent()))
                {
                    $activeElements->put($action->getId(), $action);
                }
			}
			else if ($this->modelThread->processing_stage == 'processing')
			{
                
			} 
        }
        
        return $activeElements;
    }
    
    public function run(\Telenok\Core\Interfaces\Workflow\Runtime $runtime)
    {
        if (!$this->modelThread && !$this->modelProcess)
        {
            throw new \Exception('Please, set modelProcess');
        }
        
        if (!$this->modelThread && $this->modelProcess)
        {
            $this->modelThread = \Telenok\Workflow\Thread::storeOrUpdate([
				'title' => $this->modelProcess->title,
				'original_process' => $this->modelProcess->process,
				'thread_workflow_process' => $this->modelProcess->getKey(),
				'processing_stage' => 'started',
			]);
        }
        
        $this->initActions();
        
        if ($this->modelThread->processing_stencil->isEmpty())
        {
            $this->modelThread->storeOrUpdate([
                    'processing_stencil' => $this->getActiveElements()->keys(),
                    'processing_stage' => 'processing',
                ]);
        }
            
        $i = 10;
        $sleepAll = [];
        $diff = ['some-value'];
        
        while(($activeElements = $this->getActiveElements()) && !empty($diff) && $i--)
        {
            $diff = array_diff($sleepAll, $activeElements->keys());
            
            foreach($activeElements as $id => $el)
            {
                $el->process();

                if ($el->isProcessSleeping())
                {
                    $sleepAll[] = $id;
                }
                else if ($el->isProcessFinished())
                {
                    $el->setNext();
                }
            }
        }
        
        return $this;

        
        
        
        
        $i = 100000;

        while($elements = $this->process->getNext($elements)) 
        {
            if (--$i == 0) break;
            
            foreach($elements as $element)
            {
                $this->result[$element->getId()] = $element->process();
            }
        }

        return $this;
    } 

    public function addProcessingStencil($resourceId = '')
    {
        $list = $this->modelThread->processing_stencil;
        
        $list->push($resourceId);
        
        $this->modelThread->processing_stencil = $list;
        
        $this->modelThread->save();
        
        return $this;
    }    

    public function removeProcessingStencil($resourceId = '')
    {
        $list = $this->modelThread->processing_stencil->reject(function($item) use ($resourceId) { return $item == $resourceId;});
        
        $this->modelThread->processing_stencil = $list;
        
        $this->modelThread->save();
        
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
    
    public function continuance()
    {
        $elements = $this->getLastExecutedElement();

        $i = 100000;

        while($elements = $this->process->getNext($elements)) 
        {
            if (--$i == 0) break;

            foreach($elements as $element)
            {
                $this->result[$element->getId()] = $element->process();
            }
        }

        return $this;
    }

    public function getLastExecutedElement()
    {
        return $this;
    }
}

?>