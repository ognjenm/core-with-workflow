<?php

namespace Telenok\Core\Interfaces\Workflow;

class Token implements \Illuminate\Contracts\Support\Arrayable {

    protected $sourceElementId;
    protected $currentElementId;
    protected $tokenId;
    protected $parentTokenId;
    protected $tokenOrder;
    protected $totalToken;

    public function createToken($sourceElementId, $currentElementId, $parentTokenId = '', $tokenOrder = 1, $totalToken = 1, $tokenId = null)
    {
        $this->setSourceElementId($sourceElementId)
                ->setCurrentElementId($currentElementId)
                ->setParentTokenId($parentTokenId)
                ->setTokenOrder($tokenOrder)
                ->setTotalToken($totalToken)
                ->setTokenId($tokenId ?: str_random(32));

        return $this;
    }

    public function createTokenFromArray($param)
    {
        $sourceElementId = array_get($param, 'sourceElementId');
        $currentElementId = array_get($param, 'currentElementId');
        $parentTokenId = array_get($param, 'parentTokenId');
        $tokenOrder = array_get($param, 'tokenOrder', 1);
        $totalToken = array_get($param, 'totalToken', 1);
        $tokenId = array_get($param, 'tokenId', str_random(32));

        if (!$sourceElementId)
        {
            throw new \Exception('Please, define "sourceElementId" value');
        }

        if (!$currentElementId)
        {
            throw new \Exception('Please, define "currentElementId" value');
        }

        $this->setSourceElementId($sourceElementId)
                ->setCurrentElementId($currentElementId)
                ->setParentTokenId($parentTokenId)
                ->setTokenOrder($tokenOrder)
                ->setTotalToken($totalToken)
                ->setTokenId($tokenId);

        return $this;
    }

    public function toArray()
    {
        return [
            'sourceElementId' => $this->getSourceElementId(),
            'currentElementId' => $this->getCurrentElementId(),
            'tokenId' => $this->getTokenId(),
            'parentTokenId' => $this->getParentTokenId(),
            'tokenOrder' => $this->getTokenOrder(),
            'totalToken' => $this->getTotalToken(),
        ];
    }
    
    public function getSourceElementId()
    {
        return $this->sourceElementId;
    }
    
    public function setSourceElementId($param)
    {
        $this->sourceElementId = $param;

        return $this;
    }

    public function getCurrentElementId()
    {
        return $this->currentElementId;
    }

    public function setCurrentElementId($param)
    {
        $this->currentElementId = $param;

        return $this;
    }

    public function getTokenId()
    {
        return $this->tokenId;
    }

    public function setTokenId($param)
    {
        $this->tokenId = $param;
        
        return $this;
    }

    public function getParentTokenId()
    {
        return $this->parentTokenId;
    }

    public function setParentTokenId($param)
    {
        $this->parentTokenId = $param;
        
        return $this;
    }

    public function getTokenOrder()
    {
        return $this->tokenOrder;
    }

    public function setTokenOrder($param)
    {
        $this->tokenOrder = $param;
        
        return $this;
    }
    
    public function getTotalToken()
    {
        return $this->totalToken;
    }
    
    public function setTotalToken($param)
    {
        $this->totalToken = $param;
        
        return $this;
    }
    
    public static function make()
    {
        return new static;
    }
}