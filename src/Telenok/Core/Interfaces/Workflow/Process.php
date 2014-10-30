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
    
    public static function validate($process = [])
    {
        return false;
        
        $elements = \App::make('telenok.config')->getWorkflowElement();
        $processCollection = \Illuminate\Support\Collection::make($process);
        
        $childShapes = 'childShapes';
        $i = 20;

        while( ($childShapesData = $processCollection->get($childShapes)) && $i--)
        {
            foreach ($childShapesData as $key => $action)
            {
                $action = $elements->get($action['stencil']['id'])->make()->setAction($action); 
            } 

            $action = $elements->get($config['key'])->make()->setAction($config); 

            foreach ($action->getLinkIn() as $linkIn)
            {
                if ($linkIn == $action->getId())
                {
                    throw new \Exception('Element KEY:"'.$action->getKey().'" ID:"'.$action->getId().'" fixated on himself. Its ID and one of ingoing connection has same ID."');
                }
            }

            foreach ($action->getLinkOut() as $linkOut)
            {
                if ($linkOut == $action->getId())
                {
                    throw new \Exception('Element KEY:"'.$action->getKey().'" ID:"'.$action->getId().'" fixated on himself. Its ID and one of outgoing connection has same ID."');
                }
            }

            if (count($action->getLinkOut()) > $action->getMaxOut())
            {
                throw new \Exception('Element KEY:"'.$action->getKey().'" ID:"'.$action->getId().'" has more than "'.$action->getMaxOut().'" outgoing connectors.');
            }

            if (count($action->getLinkOut()) < $action->getMinOut())
            {
                throw new \Exception('Element KEY:"'.$action->getKey().'" ID:"'.$action->getId().'" has less than "'.$action->getMinOut().'" outgoing connectors.');
            }

            if (count($action->getLinkIn()) > $action->getMaxIn())
            {
                throw new \Exception('Element KEY:"'.$action->getKey().'" ID:"'.$action->getId().'" has more than "'.$action->getMaxIn().'" ingoing connectors.');
            }

            if (count($action->getLinkIn()) < $action->getMinIn())
            {
                throw new \Exception('Element KEY:"'.$action->getKey().'" ID:"'.$action->getId().'" has less than "'.$action->getMinIn().'" ingoing connectors.');
            } 
            
            $childShapes .= '.'.$childShapes;
        }
        
        return true;
    }
}

?>