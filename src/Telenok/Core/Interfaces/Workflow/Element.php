<?php

namespace Telenok\Core\Interfaces\Workflow;

class Element extends \Illuminate\Routing\Controller {

    use \Telenok\Core\Support\PackageLoad; 

    protected $linkOut = [];
    protected $linkIn = [];

    protected $minIn = 0;
    protected $minOut = 0;

    protected $maxIn = 0;
    protected $maxOut = 0;

    protected $total = 2000000000;

    protected $id = '';
    protected $key = '';
    protected $package = '';
    protected $languageDirectory = 'workflow-element';

    protected $thread;
    protected $action;
    protected $input = [];

    protected $stencilConfig = [];
    protected $stencilContainmentRules = [];
    protected $stencilCardinalityRules = [];
    protected $stencilConnectionRules = [];
    protected $stencilMorphingRules = [];
    protected $stencilLayoutRules = [];
    protected $propertyView = '';
    protected $routerPropertyContent = '';
    protected $routerStoreProperty = 'cmf.workflow.store-property';

    public function __construct()
    {
        $this->input = \Illuminate\Support\Collection::make([]);
    }

    public function make()
    {
        return new static;
    }

    public function getStencilContainmentRules()
    {
        return $this->stencilContainmentRules;
    }

    public function getStencilCardinalityRules()
    {
        return $this->stencilCardinalityRules;
    }

    public function getStencilConnectionRules()
    {
        return $this->stencilConnectionRules;
    }

    public function getStencilMorphingRules()
    {
        return $this->stencilMorphingRules;
    }

    public function getStencilLayoutRules()
    {
        return $this->stencilLayoutRules;
    }

    public function getStencilConfig()
    {
        return $this->stencilConfig;
    }

    public function setStencilSetConfig($param = [])
    {
        $this->stencilConfig = array_merge($this->stencilConfig, $param);

        return $this;
    }

    public function getPropertyView()
    {
        return $this->propertyView;
    }

    public function setPropertyView($param = '')
    {
        $this->propertyView = $param;

        return $this;
    } 

    public function getResourceFromLog($logData = [])
    {
        return \Illuminate\Support\Collection::make([]);
    }

    public function getStencilData($data = [])
    {
		$sessionDiagramId = array_get($data, 'sessionDiagramId');
		$stencilId = array_get($data, 'stencilId');
		$diagramId = array_get($data, 'diagramId');

		$stencilData = \Session::get('diagram.' . $sessionDiagramId . '.stenciltemporary.' . $stencilId, []);

		if (empty($stencilData))
		{
			$stencilData = \Session::get('diagram.' . $sessionDiagramId . '.stencil.' . $stencilId, []);
		}

		if (empty($stencilData))
		{
            $model = \App\Model\Telenok\Workflow\Process::find($diagramId);
            
            if ($model)
            {
                $stencil = $model->process->get('stencil');
                
                $stencilData = array_get($stencil, $stencilId, []);
            }
		}
        
        return \Illuminate\Support\Collection::make($stencilData);
    }

    public function getPropertyValue($data = [])
    { 
        $stencilData = $this->getStencilData($data);

		return \Illuminate\Support\Collection::make([
			'title' => $stencilData->get('title', $this->LL('title')),
			'description' => $stencilData->get('description'),
			'bgcolor' => $stencilData->get('bgcolor', '#ffffff'),
			'bordercolor' => $stencilData->get('bordercolor', '#000000'),
		]);
	}

    public function getPropertyContent()
    {
		if (!($sessionDiagramId = \Input::get('sessionDiagramId')) || !($stencilId = \Input::get('stencilId')))
		{
			throw new \Exception('Please, define "sessionDiagramId" and "stencilId" _GET parameters');
		}

		return ['tabContent' => view($this->getPropertyView(), [
				'controller' => $this,
				'uniqueId' => str_random(),
				'sessionDiagramId' => $sessionDiagramId,
				'stencilId' => $stencilId,
				'property' => $this->getPropertyValue(\Input::all()),
			])->render()];
	}

    public function getRouterPropertyContent($param = [])
    {
		if ($this->routerPropertyContent)
		{
			return \URL::route($this->routerPropertyContent, $param);
		}
	}

    public function getRouterStoreProperty($param = [])
    {
		if ($this->routerStoreProperty)
		{
			return \URL::route($this->routerStoreProperty, $param);
		}
	}

    public function storeProperty()
    {
		if (!($sessionDiagramId = \Input::get('sessionDiagramId')) || !($stencilId = \Input::get('stencilId')))
		{
			throw new \Exception('Please, define "sessionDiagramId" and "stencilId" _GET parameters');
		}

		$stencilData = \Input::get('stencil', []);

		\Session::put('diagram.' . $sessionDiagramId . '.stenciltemporary.' . $stencilId, $stencilData);

		return $stencilData;
	} 

    public function setStencil($param = [])
    {
        $this->action = $param;  
         
        $this->setId($param['resourceId'])->setLinkOut(\Illuminate\Support\Collection::make(array_get($param, 'outgoing'))->flatten());

        return $this;
    }

    public function setThread(\Telenok\Core\Interfaces\Workflow\Thread $param)
    {
        $this->thread = $param;

        return $this;
    }

    public function getThread()
    {
        return $this->thread;
    }

    public function setInput($param = [])
    {
        $this->input = $param instanceof \Illuminate\Support\Collection ? $param : \Illuminate\Support\Collection::make($param);

        return $this;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function process($log = [])
    {
        $this->log($log);
        $this->setNext();

        return $this;
    }

    protected function setNext()
    {
        foreach($this->getLinkOut() as $out)
        {
            // through flows aka connectors to activities --->
            foreach($this->getThread()->getActionByResourceId($out)->getLinkOut() as $f)
            {
                $this->getThread()->addProcessingStencil($f);
            } 
        }

        $this->getThread()->removeProcessingStencil($this->getId());
    }

    public function log($data = [])
    {
        $data['data'] = array_get($data, 'data', []); 
        $data['result'] = array_get($data, 'result', 'done'); 
        $data['log'] = array_get($data, 'log', 'success'); 
        
        $this->getThread()->addLog($this, $data);

        return $this;
    }

    public function isProcessSleeping()
    {
        return false;
    }
    
    public function isProcessFinished()
    {
        return true;
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    
    public function getKey()
    {
        return $this->key;
    }
    
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    public function getLinkOut()
    {
        return $this->linkOut;
    }

    public function setLinkOut($link)
    {
        $this->linkOut = $link;

        return $this;
    }

    public function getLinkIn()
    {
        return $this->linkIn;
    }

    public function setLinkIn($link)
    {
        $this->linkIn = $link;

        return $this;
    }

    public function getMinIn()
    {
        return $this->minIn;
    }

    public function setMinIn($min)
    {
        $this->minIn = $min;

        return $this;
    }

    public function getMinOut()
    {
        return $this->minOut;
    }

    public function setMinOut($min)
    {
        $this->minOut = $min;

        return $this;
    }

    public function getMaxIn()
    {
        return $this->maxIn;
    }

    public function setMaxIn($min)
    {
        $this->maxIn = $max;

        return $this;
    }

    public function getMaxOut()
    {
        return $this->maxOut;
    }

    public function setMaxOut($max)
    {
        $this->maxOut = $max;

        return $this;
    }

    public function validate($process, $actions, $diagramData = [], $stencilData = [])
    {
        if ($this->getLinkIn()->isEmpty() && $this->getLinkOut()->isEmpty())
        {
            throw new \Exception('Element with key "' . $this->getKey() . '" and id "' . $this->getId() . '" havnt any connections.');
        }
        
        if (!$this instanceof \Telenok\Core\Interfaces\Workflow\Edge)
        {
            foreach($this->getLinkOut()->all() as $out)
            {
                if ($actions->get($out)->getLinkOut()->contains($this->getId()))
                {
                    throw new \Exception('Element with key "' . $this->getKey() . '" and id "' . $this->getId() . '" fixated on himself.');
                }
            }
        }

        return true;
    }

}

?>