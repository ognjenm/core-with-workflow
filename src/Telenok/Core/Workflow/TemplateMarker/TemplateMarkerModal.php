<?php namespace Telenok\Core\Workflow\TemplateMarker;

class TemplateMarkerModal extends \Illuminate\Routing\Controller implements \Telenok\Core\Interfaces\IRequest {

    use \Telenok\Core\Support\PackageLoad; 

    protected $package = '';
    protected $languageDirectory = 'workflow-template-marker';
    protected $key = 'modal';

    public function getMarkerModalContent($uniqueId = '', $attr = [], $onlyAviableAtStart = false, $excludeByKey = [], $processId = 0)
    {
        $attr = \Illuminate\Support\Collection::make($attr);
		
        return view('core::module/workflow-template-marker.modal', [
            'controller' => $this,
            'fieldId' => $attr->get('fieldId'),
            'buttonId' => $attr->get('buttonId'),
            'process' => \App\Model\Telenok\Workflow\Process::find($processId),
            'onlyAviableAtStart' => $onlyAviableAtStart,
            'excludeByKey' => $excludeByKey,
            'uniqueId' => $uniqueId,
        ])->render();
    }

	/*
	 * @param string
	 * @param \Telenok\Core\Workflow\Thread
	 */
    public function processMarkersString($string = '', $thread = null)
    {
        $collection = app('telenok.config')->getWorkflowTemplateMarker(true)->all();

		$result = null;

        foreach($collection as $c)
        { 
            $string = $c->processMarkerString($string, $thread);
        }

        $filename = str_random();

        try
        {
            \File::makeDirectory(storage_path('tmp'), 0777, true, true);

            \File::put(storage_path('tmp/') . $filename, '<?php return ' . $string . ';'); 

			$result = include(storage_path('tmp/') . $filename);
        } 
        catch (\Exception $ex) 
        {
            throw $ex;
        }
        finally
        { 
            if (\File::exists(storage_path('tmp/') . $filename))
            {
                \File::delete(storage_path('tmp/') . $filename);
            }
        }

        return $result;
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

    public function setKey($param = '')
    {
        $this->key = $param;
        
        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

	public static function make() 
	{
		return new static;
	}
}