<?php

namespace Telenok\Core\Module\Files\Browser;

class Controller extends \Telenok\Core\Interfaces\Presentation\TreeTab\Controller { 
    
    protected $key = 'file-browser';
    protected $parent = 'files';
    protected $icon = 'fa fa-file';
    
    public function getActionParam()
    {
        return '{}';
    }
    
    public function getTree()
    {
        return false;
    }
    
    public function getWizardListContent()
    {
        return array(
            'content' => \View::make("core::module/file-browser.wizard", array(
                    'controller' => $this,
                    'uniqueId' => str_random(),
                ))->render() 
        );
    }

    public function getTreeList()
    {
        $basePath = base_path();
        $basePathLength = \Str::length($basePath);
        
        $id = $basePath.\Input::get('id');
        
        $listTree = [];
                
        foreach (\Symfony\Component\Finder\Finder::create()->ignoreDotFiles(true)->ignoreVCS(true)->directories()->in( $id )->depth(0) as $dir)
        { 
            $path = $dir->getPathname();

            $listTree[] = array(
                "data" => $dir->getFilename(),
                "metadata" => array('path' => substr($dir->getPathname(), $basePathLength, \Str::length($path) - $basePathLength)),
                "state" => "closed",
                "children" => [],
            );
        }
        
        if (!\Input::get('id'))
        {
            $listTree = array(
                'data' => array(
                    "title" => "Root node", 
                    "attr" => array('id' => 'root-not-delete'), 
                ),
                "state" => "open",
                'children' => $listTree
            );
        }
        
        return $listTree;
    }
    
}

?>