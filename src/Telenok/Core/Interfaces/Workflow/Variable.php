<?php

namespace Telenok\Core\Interfaces\Workflow;

class Variable {

    use \Telenok\Core\Support\PackageLoad;

    protected $key = '';
    protected $languageDirectory = 'workflow-parameter';
    protected $package = '';
    protected $formFieldView = '';
    protected $formModelView = '';

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($param = '')
    {
        $this->key = $param;
        
        return $this;
    }

    public function getName()
    {
        return $this->LL('name');
    }

    public function getFormFieldView()
    {
        return $this->formFieldView ?: "core::workflow-parameter.{$this->getKey()}.field";
    }

    public function getFormModelView()
    {
        return $this->formModelView ?: "core::workflow-parameter.{$this->getKey()}.model";
    }

    public function setFormFieldView($param = '')
    {
        $this->formFieldView = $param;
        
        return $this;
    }

    public function getFormFieldContent($controller = null, $model = null, $uniqueId = null)
    { 
        return view($this->getFormFieldView(), array(
                'parentController' => $controller,
                'controller' => $this,
                'model' => $model,
                'uniqueId' => $uniqueId,
            ))->render();
    }

    public function getFormModelContent($controller = null, $process = null, $parameter = null, $uniqueId = null)
    { 
        return view($this->getFormModelView(), array(
                'parentController' => $controller,
                'controller' => $this,
                'process' => $process,
                'parameter' => $parameter,
                'uniqueId' => $uniqueId,
            ))->render();
    }

	/*
	 * Return value of variable from already started process's thread
	 * 
	 * @param \App\Model\Telenok\Workflow\Variable
	 * @param mixed
	 * @param \App\Http\Controllers\Module\Workflow\Thread\Controller
	 * @param mixed Some data of parameter which launched with the process
	 */
    public function getValue($model = null, $value = null, $thread = null)
    {
		return $value;
    }

	/*
	 * Store value of variable
	 * 
	 * @param \App\Model\Telenok\Workflow\Variable
	 * @param mixed
	 * @param \App\Http\Controllers\Module\Workflow\Thread\Controller
	 * @param mixed Some data of parameter which launched with the process
	 */
    public function setValue($model = null, $value = null, $thread = null)
    {
		if (!$thread)
		{
			return;
		}

		$modelThread = $thread->getModelThread();

		$variables = $modelThread->variable;

		$variables->put($model->code, $value);

		$modelThread->variable = $variables;

		$modelThread->storeOrUpdate([], false, false);
    }

	public function toString($model = null, $value, $thread = null)
	{
		return (string)$value;
	}
}