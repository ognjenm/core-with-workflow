<?php

namespace Telenok\Core\Interfaces\Workflow;

class Element extends \Illuminate\Routing\Controller implements \Telenok\Core\Interfaces\IRequest {

    use \Telenok\Core\Support\PackageLoad; 

    protected $linkOut = [];
    protected $linkIn = [];

    protected $id = '';
    protected $key = '';
    protected $token;
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
        return $this->propertyView?:'core::workflow.' . $this->getKey() . '.property';
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

    public function propertyContent()
    {
		if (!($sessionDiagramId = $this->getRequest()->input('sessionDiagramId')) || !($stencilId = $this->getRequest()->input('stencilId')))
		{
			throw new \Exception('Please, define "sessionDiagramId" and "stencilId" _GET parameters');
		}

        $element = app('telenok.config')->getWorkflowElement()->get($this->getRequest()->input('key'));
        
		return ['tabContent' => view($element->getPropertyView(), [
				'controller' => $element,
				'uniqueId' => str_random(),
				'sessionDiagramId' => $sessionDiagramId,
				'stencilId' => $stencilId,
				'property' => $element->getPropertyValue($this->getRequest()->all()),
			])->render()];
	}

    public function getRouterPropertyContent($param = [])
    {
        try
        {
            if ($this->routerPropertyContent === false)
            {
                return;
            }
            
            return \URL::route($this->routerPropertyContent, $param);
        } 
        catch (\Exception $e) 
        {
            $param['key'] = array_get($param, 'key', $this->getKey());

            return \URL::route('cmf.workflow.show-property', $param);
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
		if (!($sessionDiagramId = trim($this->getRequest()->input('sessionDiagramId'))) || !($stencilId = trim($this->getRequest()->input('stencilId'))))
		{
			throw new \Exception('Please, define "sessionDiagramId" and "stencilId" _GET parameters');
		}

		$stencilData = $this->getRequest()->input('stencil', []);

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
    
	/**
	 * Get thread instance.
	 *
	 * @return \Telenok\Core\Workflow\Thread
	 *
	 */
    public function getThread()
    {
        return $this->thread;
    }

    public function setInput($param = [])
    {
        $this->input = \Illuminate\Support\Collection::make($param);

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
        $currentToken = $this->getToken();

        if ($this->getLinkOut()->count() == 1)
        {
            $link = $this->getThread()->getActionByResourceId($this->getLinkOut()->first())->getLinkOut()->first(); 

            $newToken = $this->getThread()->generateToken($currentToken['sourceElementId'], $link);

            $this->getThread()->addProcessingToken($newToken)->addActiveToken($newToken['tokenId']);
        }
        else
        {
            foreach($this->getLinkOut() as $order => $out)
            {
                foreach($this->getThread()->getActionByResourceId($out)->getLinkOut() as $link)
                {
                    $newToken = $this->getThread()->generateToken($this->getId(), $link, $currentToken['tokenId'], $order, $this->getLinkOut()->count());

                    $this->getThread()->addProcessingToken($newToken)->addActiveToken($newToken['tokenId']);
                }
            }
        }

        $this->getThread()
                ->removeProcessingToken($currentToken['tokenId'])
                ->removeActiveToken($currentToken['tokenId']);
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
    
    public function setRequest(\Illuminate\Http\Request $request = null)
    {
        $this->request = $request;
        
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    } 
    
    public function setToken($param = [])
    {
        $this->token = \Illuminate\Support\Collection::make($param);
        
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    } 
}