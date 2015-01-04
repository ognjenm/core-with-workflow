<?php

namespace Telenok\Core\Interfaces\Workflow;

class Thread {

    protected $id;
    protected $actions = [];
    protected $process;
    protected $modelProcess;
    protected $modelThread;
    protected $parameter = [];
    protected $result = [];
    protected $event;

    public function initActions()
    {
        if ($this->getModelThread())
        {
            $elements = app('telenok.config')->getWorkflowElement();
            $this->actions = \Illuminate\Support\Collection::make([]);

            foreach(array_get($this->getModelThread()->original_process->all(), 'diagram.childShapes', []) as $action)
            {
                $this->actions->put($action['resourceId'], $elements->get($action['stencil']['id'])
                                                            ->make()
                                                            ->setThread($this)
                                                            ->setInput(array_get($this->getModelThread()->original_process->all(), 'stencil.' . $action['permanentId'], []))
                                                            ->setStencil($action));
            }
        }
        else
        {
            throw new \Exception('Cant init actions');
        }
    }
    
    public function getParameterByCode($code = '')
    {
        $parameterModel = $this->getModelProcess()->parameter()->get()->keyBy($code)->get($code);
        
        if ($parameterModel)
        {
            $parameterValue = $this->getModelThread()->parameter->get($code);
        }
        
        if ($parameterModel)
        {
            $controller = app('telenok.config')->getWorkflowParameter()->get($parameterModel->key);
            
            if ($controller)
            {
                $controller->getValue($this, $parameterModel, $parameterValue);
            }
        }
    }

    public function getActionByResourceId($resourceId = '')
    {
        return $this->getActions()->get($resourceId);
    }
    
    public function getActions()
    {
        return $this->actions;
    }
    
    public function getProcessedActiveTokens()
    {
         $activeTokens = \Illuminate\Support\Collection::make([]); 

        foreach($this->getActions() as $action)
        {
			if ($this->getModelThread()->processing_stage == 'started')
			{
                if ($action instanceof \Telenok\Core\Interfaces\Workflow\Point && $action->isEventForMe($this->getEvent()))
                {
                    $token = \Illuminate\Support\Collection::make($action->fire());

                    $this->addActiveToken($token->get('tokenId'));

                    $activeTokens->put($token->get('tokenId'), $token->toArray());
                }
			}
			else if ($this->getModelThread()->processing_stage == 'processing')
			{
                $aTokens = $this->getModelThread()->processing_token_active;
                $tokens = $this->getModelThread()->processing_token;

                foreach ($aTokens->all() as $tokenId)
                {
                    $activeTokens->put($tokenId, $tokens->get($tokenId));
                }
			} 
			else if ($this->getModelThread()->processing_stage == 'finished')
			{
			} 
        }

        return $activeTokens;
    }

    public function setProcessingStageFinished()
    {
        $this->setProcessingStage('finished');
    }

    public function isProcessingStageFinished()
    {
        return $this->getModelThread()->processing_stage == 'finished';
    }

    public function run(\Telenok\Core\Interfaces\Workflow\Runtime $runtime)
    {
        if (!$this->getModelThread() && !$this->getModelProcess())
        {
            throw new \Exception('Please, set modelProcess');
        }

        if (!$this->getModelThread() && $this->getModelProcess())
        {
            $this->setModelThread((new \App\Model\Telenok\Workflow\Thread())->storeOrUpdate([
				'title' => $this->getModelProcess()->title,
				'original_process' => $this->getModelProcess()->process,
				'original_parameter' => $this->getModelProcess()->parameter()->get()->lists('code', 'key'),
				'parameter' => $this->getParameter(),
				'active' => 1,
				'thread_workflow_process' => $this->getModelProcess()->getKey(),
				'processing_stage' => 'started',
			], false, false));
        }

        $this->initActions();

        $activeTokens = $this->getProcessedActiveTokens();

        // save at init
        if (!$activeTokens->isEmpty())
        {
            $this->getModelThread()->storeOrUpdate([
                    'processing_token' => $activeTokens,
                    'processing_stage' => 'processing',
                ], false, false);
        }

        $i = 40;
        $sleep = [];
        $isSleepAll = false;

        while(!$this->isProcessingStageFinished() && !$isSleepAll && $i--)
        { 
            foreach($activeTokens->all() as $token)
            {
                $token = \Illuminate\Support\Collection::make($token);

                $el = $this->getActionByResourceId($token->get('currentElementId'));

                $el->setToken($token);
                $el->process();

                if ($el->isProcessSleeping())
                {
                    $sleep[] = $token->get('currentElementId');
                }
                
                if ($this->isProcessingStageFinished())
                {
                    break;
                }
            }

            // all actions sleeping
            $isSleepAll = $activeTokens->reject(function($i) use ($sleep) { return in_array($i['currentElementId'], $sleep); })->isEmpty();

            $activeTokens = $this->getProcessedActiveTokens();
        }

        return $this;
    }

    public function getEventResource()
    {
        if ($this->getEvent())
        {
            return $this->getEvent()->getResource();
        }
        else
        {
            $firstEventLog = $this->getModelThread()->processing_stencil_log->first();

            if (!empty($firstEventLog))
            {
                return $this->actions->get($firstEventLog['resourceId'])->getResourceFromLog($firstEventLog);
            }
        }
    }

    public function addLog($action, $data = [])
    {
        $log = $this->getModelThread()->processing_stencil_log;

        $logStencil = $log->get($action->getId(), []);

        if (!isset($data['time']))
        {
            $data['time'] = \Carbon\Carbon::now();
        }

        if (!isset($data['key']))
        {
            $data['key'] = $action->getKey();
        }

        if (!isset($data['resourceId']))
        {
            $data['resourceId'] = $action->getId();
        }
        
        $logStencil[] = $data;

        $log->put($action->getId(), $logStencil);

        $this->getModelThread()->processing_stencil_log = $log;

        $this->getModelThread()->save();

        return $this;
    }

    public function getLogResourceId($resourceId = '')
    {
        $log = $this->getModelThread()->processing_stencil_log;

        if (strlen($resourceId))
        {
            return \Illuminate\Support\Collection::make($log->get($resourceId));
        }
        else
        {
            throw new Exception('Error! Wrong "resourceId" parameter');
        }
    }

    public function getLog()
    {
        return $this->getModelThread()->processing_stencil_log;
    }

    public function getLogResourceId($resourceId = '')
    {
        $log = $this->getModelThread()->processing_stencil_log;

        if (strlen($resourceId))
        {
            return \Illuminate\Support\Collection::make($log->get($resourceId));
        }
        else
        {
            return $log;
        }
    }
    
	/**
	 * Get token linked to element
	 *
	 * @return \Telenok\Core\Interfaces\Workflow\Token
	 *
	 */
    public function addProcessingToken($token)
    {
        $list = $this->getModelThread()->processing_token;

        $list->put($token->getto, $token->toArray());

        $this->getModelThread()->processing_token = $list;

        $this->getModelThread()->save();

        return $this;
    }    

    public function removeProcessingToken($tokenId = '')
    {
        $list = $this->getModelThread()->processing_token;
        
        /*
         * Recursive remove token and all its child in depth
         * 
         */
        $function = function(&$list, $id) use (&$function)
        {
            $list = $list->reject(function($item) use ($id) { return $item['tokenId'] == $id; });
           
            $newList = $list->filter(function($item) use ($id) { return $item['parentTokenId'] == $id; });
           
            foreach($newList->all() as $item)
            {
                $function($list, $item['tokenId']);
            }
        };

        $function($list, $tokenId);

        $this->getModelThread()->processing_token = $list;

        $this->getModelThread()->save();

        return $this;
    }

    public function addActiveToken($tokenId)
    {
        $list = $this->getModelThread()->processing_token_active;

        $list->push($tokenId);

        $this->getModelThread()->processing_token_active = $list;

        $this->getModelThread()->save();

        return $this;
    }    

    public function removeActiveToken($tokenId = '')
    {
        $list = $this->getModelThread()->processing_token_active;

        $list = $list->reject(function($item) use ($tokenId) { return $item == $tokenId; });

        $this->getModelThread()->processing_token_active = $list;

        $this->getModelThread()->save();

        return $this;
    }

    public function setProcessingStage($param)
    {
        $this->getModelThread()->processing_stage = $param;

        $this->getModelThread()->save();

        return $this;
    }

    public function setModelThread(\Telenok\Core\Model\Workflow\Thread $param)
    {
        $this->modelThread = $param;

        return $this;
    }

    public function getModelThread()
    {
        return $this->modelThread;
    }
	
    public function setModelProcess(\App\Model\Telenok\Workflow\Process $param)
    {
        $this->modelProcess = $param;
        
        return $this;
    }

    public function getModelProcess()
    {
        return $this->modelProcess;
    }
    
	
    public function setParameter($param = [])
    {
        $this->parameter = $param;
        
        return $this;
    }

    public function getParameter()
    {
        return $this->parameter;
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

	public static function make() 
	{
		return new static;
	}

    public function createToken($sourceElementId, $currentElementId, $parentTokenId = '', $tokenOrder = 1, $totalToken = 1, $tokenId = null)
    {
        return \Telenok\Core\Interfaces\Workflow\Token::make()->createToken($sourceElementId, $currentElementId, $parentTokenId, $tokenOrder, $totalToken, $tokenId);
    }

    public function createTokenFromArray($param)
    {
        return \Telenok\Core\Interfaces\Workflow\Token::make()->createTokenFromArray($param);
    }
}