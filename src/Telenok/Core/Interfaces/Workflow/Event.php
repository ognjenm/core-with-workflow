<?php

namespace Telenok\Core\Interfaces\Workflow;

class Event {
 
    protected $runtime = null;
    protected $eventCode = null;
    protected $resourceCode = null;
    protected $resource = null;
    protected $param = [];

    public function __construct(\Telenok\Core\Interfaces\Workflow\Runtime $runtime = null)
    {
        $this->runtime = $runtime;
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
    
    public function setResourceCode($param = null)
    {
        $this->resourceCode = $param;
        
        return $this;
    }
    
    public function getResourceCode()
    {
        return $this->resourceCode;
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
    
    public function setParam($param = null)
    {
        $this->param = $param;
        
        return $this;
    }
    
    public function getParam()
    {
        return $this->param;
    }

    public function fire()
    {
        if (\Config::get('app.workflow.enabled'))
        {
            $modelProcess = new \Telenok\Core\Model\Workflow\Process();
            $modelEvent = new \Telenok\Core\Model\Workflow\Event();
            $modelResource = new \Telenok\Core\Model\Security\Resource();
            $modelEventResource = new \Telenok\Core\Model\Workflow\EventResource();
            
            $query = $modelProcess->select($modelProcess->getTable().'.*');

            $query->join('pivot_relation_m2m_event_resource_workflow_process', function($join) use ($modelProcess)
            {
                $join->on($modelProcess->getTable().'.'.$modelProcess->getKeyName(), '=', 'pivot_relation_m2m_event_resource_workflow_process.event_resource_workflow_process');
            });

            $query->join($modelEventResource->getTable(), function($join) use ($modelEventResource)
            {
                $join->on('pivot_relation_m2m_event_resource_workflow_process.event_resource', '=', $modelEventResource->getTable().'.'.$modelEventResource->getKeyName());
            });

            //resource
            $query->join('pivot_relation_m2m_resource_workflow_event_resource', function($join) use ($modelEventResource)
            {
                $join->on($modelEventResource->getTable().'.'.$modelEventResource->getKeyName(), '=', 'pivot_relation_m2m_resource_workflow_event_resource.resource_workflow_event_resource');
            });

            $query->join($modelResource->getTable(), function($join) use ($modelResource)
            {
                $join->on('pivot_relation_m2m_resource_workflow_event_resource.resource', '=', $modelResource->getTable().'.'.$modelResource->getKeyName());
            });

            //event
            $query->join('pivot_relation_m2m_event_workflow_event_resource', function($join) use ($modelEventResource)
            {
                $join->on($modelEventResource->getTable().'.'.$modelEventResource->getKeyName(), '=', 'pivot_relation_m2m_event_workflow_event_resource.event_workflow_event_resource');
            });

            $query->join($modelEvent->getTable(), function($join) use ($modelEvent)
            {
                $join->on('pivot_relation_m2m_event_workflow_event_resource.event', '=', $modelEvent->getTable().'.'.$modelEvent->getKeyName());
            });

            $query->whereNull($modelProcess->getTable().'.deleted_at');
            $query->whereNull($modelResource->getTable().'.deleted_at');
            $query->whereNull($modelEvent->getTable().'.deleted_at');
            $query->whereNull($modelEventResource->getTable().'.deleted_at');
            $query->where($modelEvent->getTable().'.code', $this->getEventCode());
            $query->where($modelResource->getTable().'.code', $this->getResourceCode());

            $processes = $query->get();
            
            foreach($processes as $process)
            {
                $this->runtime->runProcess($process, $this);
            }
        }

        return $this;
    }
}

?>