<?php

namespace Telenok\Core\Model\File;

class FileExtension extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $table = 'file_extension';
	protected $ruleList = ['title' => ['required', 'min:1'], 'extension' => ['required', 'unique:file_extension,extension,:id:,id']];

	


    public function uploadFile()
    {
        return $this->hasMany('\Telenok\File\File', 'upload_file_file_extension');
    }

}
?>