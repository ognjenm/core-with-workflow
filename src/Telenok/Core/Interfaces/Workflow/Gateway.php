<?php

namespace Telenok\Core\Interfaces\Workflow;

class Gateway extends \Telenok\Core\Interfaces\Workflow\Element {

	/*
	 * Wait for excecuting all incoming sequence flow
	 */
    public function process($log = [])
    {
        if ($this->getLinkIn()->count() == 1)
        {
            return parent::process($log);
        }
        else if ($this->getLinkIn()->count() > 1)
        {
            $token = $this->getToken();

            $logLast = $this->getThread()->getLogResourceId($this->getId())->last();

            // first time here or after erasing
            if (!$logLast || array_get($logLast, 'data.erased'))
            {
                $log['data']['processedIds'] = [$token->getSourceElementId()];
                $log['data']['erased'] = 0;
            }
            else
            {
                $processedIds = $log['data']['processedIds'];

                if (!in_array($token->getSourceElementId(), $processedIds))
                {
                    $log['data']['processedIds'][] = $token->getSourceElementId();
                }

                if (count($log['data']['processedIds']) == $this->getLinkIn()->count())
                {
                    $log['data']['erased'] = 1;

                    return parent::process($log);
                }
                else
                {
                    $this->setLog($log);
                }
            }
        }

        return $this;
    }

}