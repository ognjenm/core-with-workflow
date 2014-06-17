<?php

namespace Telenok\Core\Model\File;

class FileMimeType extends \Telenok\Core\Interfaces\Eloquent\Object\Model {

	protected $table = 'file_mime_type';
	protected $ruleList = ['title' => ['required', 'min:1'], 'mime_type' => ['required', 'unique:file_mime_type,mime_type,:id:,id']];


    public function uploadFile()
    {
        return $this->hasMany('\Telenok\Core\Model\File\File', 'upload_file_file_mime_type');
    }

}
?>