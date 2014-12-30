<?php

namespace Telenok\Core\Interfaces\Workflow;

class Process {

    protected $id;
    protected $model;
    protected $thread;
    protected $event = null;
 
    public function setEvent(\Telenok\Core\Workflow\Event $param)
    {
        $this->event = $param;
        
        return $this;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getProcess()
    {
        return $this->process;
    }   
}