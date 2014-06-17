<?php 

namespace Telenok\Core\Interfaces\Validator;

class Model {

    protected $model;
    protected $ruleList;
    protected $input;
    protected $validator;
    protected $message;
    protected $customAttributes;

    public function __construct($model = null, $input = null, $message = [], $customAttributes = [])
    { 
        $input = $input instanceof \Illuminate\Support\Collection ? $input : \Illuminate\Support\Collection::make($input);

        $this->model = $model;
        $this->input = !$input->isEmpty() ? $input : \Illuminate\Support\Collection::make(\Input::all());
        $this->message = array_merge(\Lang::get('core::default.error'), (array)$message);
        $this->ruleList = $this->processRule($model->getRule());
        $this->customAttributes = $customAttributes;
    }

    protected function processRule($rule)
    {
        array_walk_recursive($rule, function(&$el, $key, $this_) {
            $el = preg_replace_callback('/\:\w+\:/', function($matches) use ($this_) {
                return array_get($this_->input, trim($matches[0], ':'));
            }, $el);
        }, $this);
        
        return $rule;
    }

    public function passes()
    {
        if ($this->model instanceof \Telenok\Core\Interfaces\Eloquent\Object\Model && $this->model->exists)
        {
            $this->ruleList = array_intersect_key($this->ruleList, $this->input->all());

            if (empty($this->ruleList))
            {
                return true;
            }
        }

        $this->validator = \Validator::make($this->input->all(), $this->ruleList, $this->message, $this->input->merge($this->customAttributes)->all())->setModel($this->model);

        if ($this->validator->passes()) 
		{
			return true;
		}
		
        return false;
    }

    public function fails()
    {
        return !$this->passes();
    }

    public function messages()
    {
        $messages = $this->validator->messages()->all();
        
        return empty($messages) ? ['undefined' => $this->message['undefined']] : $messages;
    }

    public function validator()
    {
        return $this->validator;
    }
}

?>