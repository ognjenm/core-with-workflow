<?php

namespace Telenok\Core\Interfaces\Workflow;

class Token implements \Illuminate\Contracts\Support\Arrayable, \Illuminate\Contracts\Support\Jsonable, \JsonSerializable {

    protected $sourceElementId;
    protected $currentElementId;
    protected $currentTokenId;
    protected $sourceTokenId;
    protected $tokenOrder;
    protected $totalToken;

    public function createToken($sourceElementId, $currentElementId, $sourceTokenId = '', $currentTokenId = null)
    {
        $this->setSourceElementId($sourceElementId)
                ->setSourceTokenId($sourceTokenId)
                ->setCurrentElementId($currentElementId)
                ->setCurrentTokenId($currentTokenId ?: str_random(32));

        return $this;
    }

    public function createTokenFromArray($param)
    {
        $sourceElementId = array_get($param, 'sourceElementId');
        $sourceTokenId = array_get($param, 'sourceTokenId');
        $currentElementId = array_get($param, 'currentElementId');
        $currentTokenId = array_get($param, 'currentTokenId', str_random(32));

        $this->setSourceElementId($sourceElementId)
                ->setSourceTokenId($sourceTokenId)
                ->setCurrentElementId($currentElementId)
                ->setCurrentTokenId($currentTokenId ?: str_random(32));

        return $this;
    }

    public function toArray()
    {
        return [
            'sourceElementId' => $this->getSourceElementId(),
            'sourceTokenId' => $this->getSourceTokenId(),
            'currentElementId' => $this->getCurrentElementId(),
            'currentTokenId' => $this->getCurrentTokenId(),
        ];
    }

	/**
	 * Get the collection of items as JSON.
	 *
	 * @param  int  $options
	 * @return string
	 */
	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}

	/**
	 * Convert the collection to its string representation.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toJson();
	}

	/**
	 * Convert the object into something JSON serializable.
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
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

    public function getCurrentTokenId()
    {
        return $this->currentTokenId;
    }

    public function setCurrentTokenId($param)
    {
        $this->currentTokenId = $param;
        
        return $this;
    }

    public function getSourceTokenId()
    {
        return $this->sourceTokenId;
    }

    public function setSourceTokenId($param)
    {
        $this->sourceTokenId = $param;
        
        return $this;
    }
    
    public static function make()
    {
        return new static;
    }
}