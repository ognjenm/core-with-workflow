<?php

namespace Telenok\Core\Filter\Acl\Resource\DirectRight;

class Controller extends \Telenok\Core\Interfaces\Filter\Acl\Resource\Controller {

    public $key = 'direct-right'; 

    public function filterCan($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
		$now = \Carbon\Carbon::now();
		$sequence = new \Telenok\Object\Sequence();
		$spr = new \Telenok\Security\SubjectPermissionResource();
		
		// verify direct right of subject via SubjectPermissionResource on resource
		$queryCommon->leftJoin($spr->getTable() . ' as spr_filter_direct_right', function($join) use ($spr, $sequence, $subject, $permission, $now)
		{
			$join->on($sequence->getTable() . '.id', '=', 'spr_filter_direct_right.acl_resource_object_sequence');
			$join->where('spr_filter_direct_right.acl_permission_object_sequence', '=', $permission->getKey());
			$join->where('spr_filter_direct_right.acl_subject_object_sequence', '=', $subject->getKey());
			$join->on('spr_filter_direct_right.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('spr_filter_direct_right.active', '=', 1);
			$join->where('spr_filter_direct_right.start_at', '<=', $now);
			$join->where('spr_filter_direct_right.end_at', '>=', $now);
		}); 

		$queryWhere->OrWhereNotNull('spr_filter_direct_right.id');
	}
	
    public function filter($queryCommon, $queryWhere, $resource, $permission, $subject)
    {
		$now = \Carbon\Carbon::now();
		$spr = new \Telenok\Security\SubjectPermissionResource();

		// verify direct right of subject via SubjectPermissionResource on resource
		$queryCommon->leftJoin($spr->getTable() . ' as spr_filter_direct_right', function($join) use ($spr, $subject, $permission, $now)
		{
			$join->on('osequence.id', '=', 'spr_filter_direct_right.acl_resource_object_sequence');
			$join->where('spr_filter_direct_right.acl_permission_object_sequence', '=', $permission->getKey());
			$join->where('spr_filter_direct_right.acl_subject_object_sequence', '=', $subject->getKey());
			$join->on('spr_filter_direct_right.' . $spr->getDeletedAtColumn(), ' is ', \DB::raw("null"));
			$join->where('spr_filter_direct_right.active', '=', 1);
			$join->where('spr_filter_direct_right.start_at', '<=', $now);
			$join->where('spr_filter_direct_right.end_at', '>=', $now);
		}); 

		$queryWhere->OrWhereNotNull('spr_filter_direct_right.id');
	}
}

?>