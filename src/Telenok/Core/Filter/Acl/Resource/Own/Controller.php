<?php

namespace Telenok\Core\Filter\Acl\Resource\Own;

class Controller extends \Telenok\Core\Interfaces\Filter\Acl\Resource\Controller {

    public $key = 'own';

    public function filter($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
        if ($this->resourceHasFilter($resource, $permission, $subject))
        {
            //$queryCommon->where($resource->getTable().'.created_by_user', $subject->getKey());
        }
    }

}

?>