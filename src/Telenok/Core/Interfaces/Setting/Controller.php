<?php

namespace Telenok\Core\Interfaces\Setting;

abstract class Controller {

    protected $key = '';
    protected $package = '';
    protected $ruleList = [];
    protected $formSettingContentView = '';

    public function getKey()
    {
        return $this->key;
    } 

	public function getFormSettingContent($field, $model, $uniqueId)
	{
		return \View::make($this->getFormSettingContentView(), [
				'controller' => $this,
				'field' => $field,
				'model' => $model,
				'uniqueId' => $uniqueId,
			])->render();
	}

	public function getFormSettingContentView()
	{
		return $this->formSettingContentView ?: "{$this->getPackage()}::setting/{$this->getKey()}.content";
	}

    public function validate($input = null)
    {
        $validator = $this->validator($this->ruleList, $input);
         
        if ($validator->fails()) 
        {
            throw $this->validateException()->setMessageError($validator->messages());
        }
    } 

    public function validator($rule = [], $input = null, $message = [])
    {
        return new \Telenok\Core\Interfaces\Validator\Setting($rule, $input, $message);
    }

    public function validateException()
    {
        return new \Telenok\Core\Interfaces\Exception\Validate();
    }

    public function getPackage()
    {
        if ($this->package) return $this->package;
        
        $list = explode('\\', __NAMESPACE__);
        
        return strtolower(array_get($list, 1));
    }

    public function LL($key = '', $param = [])
    {
        $key_ = "{$this->getPackage()}::setting/{$this->getKey()}.$key";
        $key_default_ = "{$this->getPackage()}::default.$key";
        
        $word = \Lang::get($key_, $param);
        
        // not found in current wordspace
        if ($key_ === $word)
        {
            $word = \Lang::get($key_default_, $param);
            
            // not found in default wordspace
            if ($key_default_ === $word)
            {
                return $key_;
            }
        } 
        
        return $word;
    }
}

?>