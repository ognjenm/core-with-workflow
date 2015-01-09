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
                $el = $this->getActionByResourceId($token->getCurrentElementId());

                $el->setToken($token);
                $el->process();

                if ($el->isProcessSleeping())
                {
                    $sleep[] = $token->getCurrentElementId();
                }
                
                if ($this->isProcessingStageFinished())
                {
                    break;
                }
            }

            // all actions sleeping
            $isSleepAll = $activeTokens->reject(function($i) use ($sleep) { return in_array($i->getCurrentElementId(), $sleep); })->isEmpty();

            $activeTokens = $this->getProcessedActiveTokens();
        }

        return $this;
    }

    public function initActions()
    {
        if ($modelThread = $this->getModelThread())
        {
            $elements = app('telenok.config')->getWorkflowElement();
            $this->actions = \Illuminate\Support\Collection::make();
            $shapes = array_get($modelThread->original_process->all(), 'diagram.childShapes', []);

            foreach($shapes as $action)
            {
                $this->actions->put($action['resourceId'], $elements->get($action['stencil']['id'])
                                                            ->make()
                                                            ->setThread($this)
                                                            ->setInput(array_get($modelThread->original_process->all(), 'stencil.' . $action['permanentId'], []))
                                                            ->setStencil($action, $shapes));
            }
        }
        else
        {
            throw new \Exception('Cant init actions');
        }
    }

    public function getParameterByCode($code = '')
    {
        if (($parameterModel = $this->getModelThread()->original_parameter->get($code))
				&& ($controller = app('telenok.config')->getWorkflowParameter()->get($parameterModel->key)))
        {
			return $controller->getValue($this, $parameterModel, $this->getModelThread()->parameter->get($code));

        }
		else
		{
			throw new \Exception('Process hasn\'t parameter with code "' . $code .'"');
		}
    }

	/*
	 * @return \Telenok\Core\Interfaces\Workflow\Element
	 */
    public function getActionByResourceId($resourceId = '')
    {
        return $this->getActions()->get($resourceId);
    }

	/*
	 * @return \Illuminate\Support\Collection
	 */
    public function getActions()
    {
        return $this->actions;
    }

    public function getProcessedActiveTokens()
    {
        $activeTokens = \Illuminate\Support\Collection::make(); 
        $modelThread = $this->getModelThread();

        foreach($this->getActions() as $action)
        {
			if ($modelThread->processing_stage == 'started')
			{
                if ($action instanceof \Telenok\Core\Interfaces\Workflow\Point && $action->isEventForMe($this->getEvent()))
                {
                    $token = $this->createToken($action->getId(), $action->getId());

                    $this->addActiveToken($token);

                    $activeTokens->put($token->getCurrentTokenId(), $token);
                }
			}
			else if ($modelThread->processing_stage == 'processing')
			{
                $aTokens = $modelThread->processing_token_active;
                $tokens = $modelThread->processing_token;

                foreach ($aTokens->all() as $tokenId)
                {
                    $activeTokens->put($tokenId, $this->createTokenFromArray($tokens->get($tokenId)));
                }
			} 
			else if ($modelThread->processing_stage == 'finished')
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

    public function setLog($action, $data = [])
    {
        $modelThread = $this->getModelThread();
        
        $log = $modelThread->processing_stencil_log;

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

        $modelThread->processing_stencil_log = $log;

        $modelThread->save();

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
    
	/**
	 * Get token linked to element
	 *
	 * @param \Telenok\Core\Interfaces\Workflow\Token $token
	 *
	 */
    public function addProcessingToken($token)
    {
        $modelThread = $this->getModelThread();
        
        $list = $modelThread->processing_token;

        $list->put($token->getCurrentTokenId(), $token->toArray());

        $modelThread->processing_token = $list;

        $modelThread->save();

        return $this;
    }

	/**
	 * Get token linked to element
	 *
	 * @param \Telenok\Core\Interfaces\Workflow\Token $token
	 *
	 */
    public function removeProcessingToken11111111111111111111111111111111111111111111111($token)
    {
        $modelThread = $this->getModelThread();
        
        $list = $modelThread->processing_token;
        
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

        $function($list, $token->getCurrentTokenId());

        $modelThread->processing_token = $list;

        $modelThread->save();

        return $this;
    }

	/**
	 * Get token linked to element
	 *
	 * @param \Telenok\Core\Interfaces\Workflow\Token $token
	 *
	 */
    public function addActiveToken($token)
    {
        $modelThread = $this->getModelThread();

        $list = $modelThread->processing_token_active;

        $list->push($token->getCurrentTokenId());

        $modelThread->processing_token_active = $list;

        $modelThread->save();

        return $this;
    }

	/**
	 * Get token linked to element
	 *
	 * @param \Telenok\Core\Interfaces\Workflow\Token $token
	 *
	 */
    public function removeActiveToken($token)
    {
        $modelThread = $this->getModelThread();

        $list = $modelThread->processing_token_active;

        $list = $list->reject(function($item) use ($token) { return $item == $token->getCurrentTokenId(); });

        $modelThread->processing_token_active = $list;

        $modelThread->save();

        return $this;
    }

    public function setProcessingStage($param)
    {
        $modelThread = $this->getModelThread();

        $modelThread->processing_stage = $param;

        $modelThread->save();

        return $this;
    }

    public function setModelThread(\Telenok\Core\Model\Workflow\Thread $param)
    {
        $this->modelThread = $param;

        return $this;
    }

    public function getModelThread()
    {
        if (!$this->modelThread)
        {
            return null;
        }
        else
        {
            $this->modelThread = \App\Model\Telenok\Workflow\Thread::findOrFail($this->modelThread->getKey());
        }
        
        return $this->modelThread;
    }

    /*
     * @return \Illuminate\Support\Collection
     */
    public function getTokens()
    {
        return $this->getModelThread()->processing_token;
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

	/**
	 * Get token linked to element
	 *
	 * @return \Telenok\Core\Interfaces\Workflow\Token
	 *
	 */
    public function createToken($sourceElementId, $currentElementId, $sourceTokenId = '', $currentTokenId = null)
    {
        return \Telenok\Core\Interfaces\Workflow\Token::make()->createToken($sourceElementId, $currentElementId, $sourceTokenId, $currentTokenId);
    }

	/**
	 * Get token linked to element
	 *
	 * @return \Telenok\Core\Interfaces\Workflow\Token
	 *
	 */
    public function createTokenFromArray($param)
    {
        return \Telenok\Core\Interfaces\Workflow\Token::make()->createTokenFromArray($param);
    }
}