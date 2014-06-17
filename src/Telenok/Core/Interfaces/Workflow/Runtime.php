<?php

namespace Telenok\Core\Interfaces\Workflow;

class Runtime {

    public function event()
    { 
        return new \Telenok\Core\Workflow\Event($this);
    }    

    public function setEvent($event)
    { 
        return $event->setRuntime($this);
    }    

    public function runProcess(\Telenok\Core\Model\Workflow\Process $process, \Telenok\Core\Interfaces\Workflow\Event $event = null)
    { 
        (new \Telenok\Core\Workflow\Thread($process, $event))->run($this); 
        
        return $this;
    }
}

?>