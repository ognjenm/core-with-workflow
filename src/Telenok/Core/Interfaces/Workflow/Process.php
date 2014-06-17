<?php

namespace Telenok\Core\Interfaces\Workflow;

class Process {

    protected $id;
    protected $actions;
    protected $modelProcess;
    protected $event = null;

    public function __construct(\Telenok\Core\Model\Workflow\Process $model = null, \Telenok\Core\Workflow\Event $event = null)
    {
        if ($model)
        {
            $this->id = $model->getKey();
            $this->modelProcess = $model;

            $elements = \App::make('telenok.config')->getWorkflowElement();
 /*
            $model->process = json_decode('{
    "resourceId": "oryx-526fdcd90be02",
    "properties": {},
    "stencil": {
        "id": "BPMNDiagram"
    },
    "childShapes": [
        {
            "resourceId": "oryx_AD68CD55-603B-49EF-9F1D-CA72905D928A",
            "properties": {},
            "stencil": {
                "id": "point-start"
            },
            "childShapes": [],
            "outgoing": [
                {
                    "resourceId": "oryx_283D47BA-BFAD-4E52-9717-B56CB3052C8B"
                }
            ],
            "bounds": {
                "lowerRight": {
                    "x": 216,
                    "y": 147
                },
                "upperLeft": {
                    "x": 186,
                    "y": 117
                }
            },
            "dockers": [
                {
                    "x": 201,
                    "y": 132
                }
            ]
        },
        {
            "resourceId": "oryx_432A0967-592F-4A70-9E18-E7696D2666DE",
            "properties": {},
            "stencil": {
                "id": "point-end"
            },
            "childShapes": [],
            "outgoing": [],
            "bounds": {
                "lowerRight": {
                    "x": 750,
                    "y": 273
                },
                "upperLeft": {
                    "x": 722,
                    "y": 245
                }
            },
            "dockers": []
        },
        {
            "resourceId": "oryx_A608F7E9-CE24-4D4E-97AE-85895B1DF347",
            "properties": {
                "name": "Validate Status"
            },
            "stencil": {
                "id": "action-validate-status"
            },
            "childShapes": [],
            "outgoing": [
                {
                    "resourceId": "oryx_3FE2ADB5-7E34-48E3-960E-11C235D567CC"
                }
            ],
            "bounds": {
                "lowerRight": {
                    "x": 473,
                    "y": 222
                },
                "upperLeft": {
                    "x": 373,
                    "y": 142
                }
            },
            "dockers": []
        },
        {
            "resourceId": "oryx_283D47BA-BFAD-4E52-9717-B56CB3052C8B",
            "properties": {},
            "stencil": {
                "id": "sequence-flow"
            },
            "childShapes": [],
            "outgoing": [
                {
                    "resourceId": "oryx_A608F7E9-CE24-4D4E-97AE-85895B1DF347"
                }
            ],
            "bounds": {
                "lowerRight": {
                    "x": 372.5703125,
                    "y": 182
                },
                "upperLeft": {
                    "x": 201,
                    "y": 147.0625
                }
            },
            "dockers": [
                {
                    "x": 15,
                    "y": 15
                },
                {
                    "x": 201,
                    "y": 182
                },
                {
                    "x": 50,
                    "y": 40
                }
            ],
            "target": {
                "resourceId": "oryx_A608F7E9-CE24-4D4E-97AE-85895B1DF347"
            }
        },
        {
            "resourceId": "oryx_3FE2ADB5-7E34-48E3-960E-11C235D567CC",
            "properties": {},
            "stencil": {
                "id": "sequence-flow"
            },
            "childShapes": [],
            "outgoing": [
                {
                    "resourceId": "oryx_432A0967-592F-4A70-9E18-E7696D2666DE"
                }
            ],
            "bounds": {
                "lowerRight": {
                    "x": 736,
                    "y": 244.765625
                },
                "upperLeft": {
                    "x": 473.517578125,
                    "y": 182
                }
            },
            "dockers": [
                {
                    "x": 50,
                    "y": 40
                },
                {
                    "x": 736,
                    "y": 182
                },
                {
                    "x": 14,
                    "y": 14
                }
            ],
            "target": {
                "resourceId": "oryx_432A0967-592F-4A70-9E18-E7696D2666DE"
            }
        }
    ],
    "bounds": {
        "lowerRight": {
            "x": 1985,
            "y": 1050
        },
        "upperLeft": {
            "x": 0,
            "y": 0
        }
    },
    "stencilset": {
        "url": "http://laravelnew.ru/cmf/module/workflow-process/diagram/stensilset",
        "namespace": "http://b3mn.org/stencilset/bpmn2.0#"
    },
    "ssextensions": []
}', true);*/
            
            //$model->save();
            
            $childShapes = 'childShapes';
            $i = 20;
            
            while( ($childShapesData = $model->process->get($childShapes)) && $i--)
            {
                foreach ($childShapesData as $key => $action)
                {
                    $this->actions[$action['resourceId']] = $elements->get($action['stencil']['id'])->make()->setProcess($this)->setAction($action); 
                }

                $childShapes .= '.'.$childShapes;
            }
        }
        
        if ($event)
        {
            $this->setEvent($event);
        }
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
    
    public function actions()
    {
        return $this->actions;
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
    
    public function getModel()
    {
        return $this->modelProcess;
    }

    protected function getStartPoint()
    {
        foreach($this->actions as $action)
        {
            if ($action instanceof \Telenok\Core\Workflow\Point\Start && $action->active($this->event))
            {
                return $action;
            }
        }
    }

    protected function getNext($elements)
    {
        $next = [];
        
        foreach($this->actions as $action)
        {
            foreach($elements as $element)
            {
                foreach($element->getLinkOut() as $out)
                {
                    if ($out === $action->getId())
                    {
                        $next[] = $action;
                    }
                }
            }
        }

        return $next;
    } 
}

?>