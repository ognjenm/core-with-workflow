<?php

\Validator::resolver(function($translator, $data, $rules, $messages, $customAttributes)
{
    return new \Telenok\Core\Interfaces\Validator\Validator($translator, $data, $rules, $messages, $customAttributes);
});

Validator::extend('valid_regex', function($attribute, $value, $parameters)
{
    return (@preg_match($value, NULL) !== FALSE);
}); 

\Event::listen('telenok.setting.add', function($list) 
{
    $list->push('Telenok\Core\Setting\AppLocaleDefault\Controller');
    $list->push('Telenok\Core\Setting\AppLocales\Controller');
});

\Event::listen('telenok.workflow.event.add', function($list) 
{/*
    $list->push('auth.attempt');
    $list->push('auth.login');
    $list->push('auth.logout');
    $list->push('workflow.create.before');
    $list->push('workflow.create.after');
    $list->push('workflow.update.before');
    $list->push('workflow.update.after');
    $list->push('workflow.save.before');
    $list->push('workflow.save.after');
*/});

\Event::listen('telenok.acl.filter.resource.add', function($list)
{
    $list->push('Telenok\Core\Filter\Acl\Resource\ObjectType\Controller');
	$list->push('Telenok\Core\Filter\Acl\Resource\ObjectTypeOwn\Controller');
	$list->push('Telenok\Core\Filter\Acl\Resource\DirectRight\Controller');
});

\Event::listen('telenok.module.menu.left', function($list)
{
    $list->put('web', 1);
    $list->put('objects', 2);
    $list->put('system', 3);
    
    $list->put('dashboard', 0);
    $list->put('objects-field', 0);
    $list->put('objects-lists', 0);
    $list->put('objects-type', 0);
    $list->put('objects-version', 0);
    $list->put('system-setting', 0);
    $list->put('web-page', 10);
});

\Event::listen('telenok.module.menu.top', function($list)
{
    $list->push('users-profile@topMenuMain');
    $list->push('users-profile@topMenuLogout');
});

\Event::listen('telenok.workflow.action.add', function($list) 
{
    $list->push('Telenok\Core\Workflow\Point\Start\BeforeSave');
    $list->push('Telenok\Core\Workflow\Point\End');
    $list->push('Telenok\Core\Workflow\Edge\SequenceFlow');
    $list->push('Telenok\Core\Workflow\Action\ValidateField');
    //$list->push('Telenok\Core\Workflow\Action\SendMessage');
    //$list->push('Telenok\Core\Workflow\Action\Log');
});

\Event::listen('telenok.objects-field.add', function($list) 
{
    $list->push('Telenok\Core\Field\Integer\Controller');
    $list->push('Telenok\Core\Field\IntegerUnsigned\Controller');
    $list->push('Telenok\Core\Field\Text\Controller');
    $list->push('Telenok\Core\Field\String\Controller');
    $list->push('Telenok\Core\Field\Checkbox\Controller');
	$list->push('Telenok\Core\Field\ComplexArray\Controller');
    $list->push('Telenok\Core\Field\RelationOneToOne\Controller');
    $list->push('Telenok\Core\Field\RelationOneToMany\Controller');
    $list->push('Telenok\Core\Field\RelationManyToMany\Controller');
    $list->push('Telenok\Core\Field\System\Tree\Controller');
    $list->push('Telenok\Core\Field\MorphOneToOne\Controller');
    $list->push('Telenok\Core\Field\MorphOneToMany\Controller');
    $list->push('Telenok\Core\Field\MorphManyToMany\Controller');
    $list->push('Telenok\Core\Field\System\CreatedBy\Controller');
    $list->push('Telenok\Core\Field\System\UpdatedBy\Controller');
    $list->push('Telenok\Core\Field\System\DeletedBy\Controller');
    $list->push('Telenok\Core\Field\System\LockedBy\Controller');
    $list->push('Telenok\Core\Field\System\Active\Controller');
    $list->push('Telenok\Core\Field\System\Permission\Controller');
    $list->push('Telenok\Core\Field\FileManyToMany\Controller');
    $list->push('Telenok\Core\Field\System\WorkflowStatus\Controller');
    $list->push('Telenok\Core\Field\Upload\Controller');
});


/*
\Event::listen('telenok.module.profile.add', function($param){
    \App::make('telenok.config')->addModule($param);
});
*/

//\Event::fire('telenok.module.add', 'Telenok\Core\Module\Dashboard\Controller');
//\Event::fire('telenok.module.add', 'Telenok\Core\Module\Web\Controller');
//\Event::fire('telenok.module.add', 'Telenok\Core\Module\Page\Controller');
//\Event::fire('telenok.module.add', 'Telenok\Core\Module\Users\Profile\Controller');



 



\Event::listen('telenok.compile.route', function()
{
    \App::make('telenok.config')->compileRouter();
});



\Event::listen('telenok.compile.setting', function()
{
    \App::make('telenok.config')->compileSetting();
});



\App::make('telenok.config')->runWorkflowListener();


Event::listen('illuminate.query', function($sql, $bindings, $time) {
    
	if (\Config::get('querylog'))
	{
		// Uncomment this if you want to include bindings to queries
		$sql = str_replace(array('%', '?'), array('%%', '"%s"'), $sql);
		$sql = vsprintf($sql, $bindings);

		var_dump($sql);
	}
});



?>