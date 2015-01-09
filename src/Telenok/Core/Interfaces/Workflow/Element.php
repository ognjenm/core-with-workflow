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
        $this->input = \Illuminate\Support\Collection::make();
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
        return \Illuminate\Support\Collection::make();
    }

    public function getStencilData($data = [])
    {
		$sessionProcessId = array_get($data, 'sessionProcessId');
		$stencilId = array_get($data, 'stencilId');
		$processId = array_get($data, 'processId');

		$stencilData = \Session::get('diagram.' . $sessionProcessId . '.stenciltemporary.' . $stencilId, []);

		if (empty($stencilData))
		{
			$stencilData = \Session::get('diagram.' . $sessionProcessId . '.stencil.' . $stencilId, []);
		}

		if (empty($stencilData))
		{
            $model = \App\Model\Telenok\Workflow\Process::find($processId);

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
		if (!($sessionProcessId = $this->getRequest()->input('sessionProcessId')) || !($stencilId = $this->getRequest()->input('stencilId')))
		{
			throw new \Exception('Please, define "sessionProcessId" and "stencilId" _GET parameters');
		}

        $element = app('telenok.config')->getWorkflowElement()->get($this->getRequest()->input('key'));

        $processId = $this->getRequest()->input('processId');
        
		return ['tabContent' => view($element->getPropertyView(), [
				'controller' => $element,
				'uniqueId' => str_random(),
				'sessionProcessId' => $sessionProcessId,
				'stencilId' => $stencilId,
				'processId' => $processId,
                'model' => \App\Model\Telenok\Workflow\Process::find($processId),
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
		if (!($sessionProcessId = trim($this->getRequest()->input('sessionProcessId'))) || !($stencilId = trim($this->getRequest()->input('stencilId'))))
		{
			throw new \Exception('Please, define "sessionProcessId" and "stencilId" _GET parameters');
		}

		$stencilData = $this->getRequest()->input('stencil', []);

		\Session::put('diagram.' . $sessionProcessId . '.stenciltemporary.' . $stencilId, $stencilData);

		return $stencilData;
	} 

    public function setStencil($param = [], $shapes = [])
    {
        $this->action = $param;  

        $this->setId($param['resourceId'])->setLinkOut(\Illuminate\Support\Collection::make(array_get($param, 'outgoing'))->flatten());

        $in = \Illuminate\Support\Collection::make($shapes)
                ->filter(function($i) { return $this->getId() == array_get($i, 'target.resourceId'); })
                ->transform(function($i) { return array_get($i, 'target.resourceId'); })
                ->values();
              
        $this->setLinkIn($in);
        
        return $this;
    }

    public function setThread(\Telenok\Core\Interfaces\Workflow\Thread $param)
    {
        $this->thread = $param;

        return $this;
    }
    
	/**
	 * Get thread instance
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
        $this->setLog($log);
        $this->setNext();

        return $this;
    }

    protected function setNext()
    {
        $currentToken = $this->getToken();

        if ($this->getLinkOut()->count() == 1)
        {
            $nextId = $this->getLinkOut()->first();

            $newToken = $this->getThread()->createToken($currentToken->getSourceElementId(), $nextId, $currentToken->getCurrentTokenId());

            $this->getThread()->addProcessingToken($newToken)->addActiveToken($newToken); 
        }
        else
        {
            foreach($this->getLinkOut() as $nextId)
            {
                $newToken = $this->getThread()->createToken($this->getId(), $nextId, $currentToken->getCurrentTokenId());

                $this->getThread()->addProcessingToken($newToken)->addActiveToken($newToken);
            }
        }

        $this->getThread()->removeActiveToken($currentToken);
    }

    public function setLog($data = [])
    {
        var_dump($this->getKey());
        
        $data['data'] = array_get($data, 'data', []); 
        $data['result'] = array_get($data, 'result', 'done'); 
        $data['log'] = array_get($data, 'log', 'success'); 
        $data['token'] = $this->getToken()->toArray(); 
        
        $this->getThread()->setLog($this, $data);

        return $this;
    }

    public function isProcessSleeping()
    {
        return false;
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

    /*
     * @return \Illuminate\Support\Collection
     */
    public function getLinkOut()
    {
        return $this->linkOut;
    }

    public function setLinkOut($link)
    {
        $this->linkOut = $link;

        return $this;
    }

    /*
     * @return \Illuminate\Support\Collection
     */
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
        
        if (!$this instanceof \Telenok\Core\Interfaces\Workflow\Flow)
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

    /*
     * @return \Illuminate\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

	/**
	 * Get token linked to element
	 *
	 * @param \Telenok\Core\Interfaces\Workflow\Token $token
	 *
	 */
    public function setToken($token)
    {
        $this->token = $token;
        
        return $this;
    }

	/**
	 * Get token linked to element
	 *
	 * @return \Telenok\Core\Interfaces\Workflow\Token
	 *
	 */
    public function getToken()
    {
        return $this->token;
    } 
}