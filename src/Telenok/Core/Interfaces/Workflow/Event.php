<?php

namespace Telenok\Core\Interfaces\Workflow;

class Event {
 
    protected $runtime = null;
    protected $eventCode = null;
    protected $resource = null;
    protected $input = null;

    public function __construct(\Telenok\Core\Interfaces\Workflow\Runtime $runtime = null)
    {
        $this->runtime = $runtime;
        $this->input = \Illuminate\Support\Collection::make([]);
    }

    public function setRuntime($param = null)
    {
        $this->runtime = $param;
        
        return $this;
    }
    
    public function getRuntime()
    {
        return $this->runtime;
    }
    
    public function setEventCode($param = null)
    {
        $this->eventCode = $param;
        
        return $this;
    }
    
    public function getEventCode()
    {
        return $this->eventCode;
    }
    
    public function setResource($param = null)
    {
        $this->resource = $param;
        
        return $this;
    }
    
    public function getResource()
    {
        return $this->resource;
    }
    
    public function setInput(\Illuminate\Support\Collection $param = null)
    {
        $this->input = $param;
        
        return $this;
    }
    
    public function getInput()
    {
        return $this->input;
    } 
}

