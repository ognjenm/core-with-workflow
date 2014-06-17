<?php

namespace Telenok\Core\Interfaces\Workflow;

class Thread {

    protected $id;
    protected $actions;
    protected $modelProcess;
    protected $modelThread;
    protected $result = [];
    protected $event = null;

    public function __construct(\Telenok\Core\Model\Workflow\Process $process = null, \Telenok\Core\Workflow\Event $event = null)
    {
        $this->modelProcess = $process;
        
        $this->modelThread = \Telenok\Core\Model\Workflow\Thread::create([
            'title' => $process->title,
            'original_process' => $process->process,
            'thread_workflow_process' => $this->modelProcess->getKey(),
        ]);
        
        $this->event = $event;
        $this->process = new \Telenok\Core\Workflow\Process($process, $event);
    }

    public function run(\Telenok\Core\Interfaces\Workflow\Runtime $runtime)
    {
        $elements = [$this->process->getStartPoint()];

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
}

?>