<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

\Route::filter('csrf', 'Telenok\Core\Filter\Router\Controller@csrf');
\Route::filter('auth', 'Telenok\Core\Filter\Router\Backend\Controller@auth');
\Route::filter('access-module', 'Telenok\Core\Filter\Router\Backend\Controller@accessModule');

\Route::when('/*', 'csrf', ['post']);


// Errors
\Route::any('cmf/error', array('as' => 'error.access-denied', 'uses' => "Telenok\Core\Controller\Backend\Controller@errorAccessDenied"));

\Route::any('cmf', array('as' => 'cmf.content', 'uses' => "Telenok\Core\Controller\Backend\Controller@getContent"));
 
\Route::any('cmf/login', array('as' => 'cmf.login', 'uses' => "Telenok\Core\Controller\Backend\Controller@login"));
\Route::any('cmf/logout', array('as' => 'cmf.logout', 'uses' => "Telenok\Core\Controller\Backend\Controller@logout"));

\Route::any('cmf/clear-cache', array('as' => 'cmf.clear.cache', function()
{
    \Event::fire('telenok.compile.route');
}));

\Route::any('cmf/install', array('as' => 'cmf.install', 'uses' => "Telenok\Core\Support\Install\Controller@getContent"));
\Route::any('cmf/install/verify', array('as' => 'cmf.install.process', 'uses' => "Telenok\Core\Support\Install\Controller@process"));


// Fields
\Route::any('cmf/field/relation-one-to-one/list/title/type/{id}', array('as' => 'cmf.field.relation-one-to-one.list.title', 'uses' => "Telenok\Core\Field\RelationOneToOne\Controller@getTitleList"));

\Route::any('cmf/field/relation-one-to-many/list/title/type/{id}', array('as' => 'cmf.field.relation-one-to-many.list.title', 'uses' => "Telenok\Core\Field\RelationOneToMany\Controller@getTitleList"));
\Route::any('cmf/field/relation-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.relation-one-to-many.list.table', 'uses' => "Telenok\Core\Field\RelationOneToMany\Controller@getTableList"));

\Route::any('cmf/field/relation-many-to-many/list/title/type/{id}', array('as' => 'cmf.field.relation-many-to-many.list.title', 'uses' => "Telenok\Core\Field\RelationManyToMany\Controller@getTitleList"));
\Route::any('cmf/field/relation-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.relation-many-to-many.list.table', 'uses' => "Telenok\Core\Field\RelationManyToMany\Controller@getTableList"));

\Route::any('cmf/field/tree/list/title/type/{id}', array('as' => 'cmf.field.tree.list.title', 'uses' => "Telenok\Core\Field\System\Tree\Controller@getTitleList"));
\Route::any('cmf/field/tree/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.tree.list.table', 'uses' => "Telenok\Core\Field\System\Tree\Controller@getTableList"));

\Route::any('cmf/field/morph-many-to-many/list/title/type/{id}', array('as' => 'cmf.field.morph-many-to-many.list.title', 'uses' => "Telenok\Core\Field\MorphManyToMany\Controller@getTitleList"));
\Route::any('cmf/field/morph-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.morph-many-to-many.list.table', 'uses' => "Telenok\Core\Field\MorphManyToMany\Controller@getTableList"));

\Route::any('cmf/field/morph-one-to-many/list/title/type/{id}', array('as' => 'cmf.field.morph-one-to-many.list.title', 'uses' => "Telenok\Core\Field\MorphOneToMany\Controller@getTitleList"));
\Route::any('cmf/field/morph-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.morph-one-to-many.list.table', 'uses' => "Telenok\Core\Field\MorphOneToMany\Controller@getTableList"));

\Route::any('cmf/field/morph-one-to-one/list/title/type/{id}', array('as' => 'cmf.field.morph-one-to-one.list.title', 'uses' => "Telenok\Core\Field\MorphOneToOne\Controller@getTitleList"));

\Route::any('cmf/field/permission/list/title', array('as' => 'cmf.field.permission.list.title', 'uses' => "Telenok\Core\Field\System\Permission\Controller@getTitleList"));
 
\Route::any('cmf/field/file-many-to-many/upload', array('as' => 'cmf.field.file-many-to-many.upload', 'uses' => "Telenok\Core\Field\FileManyToMany\Controller@upload"));
 

// Module Dashboard

\Route::any('cmf/module/dashboard', array('as' => 'cmf.module.dashboard', 'uses' => "Telenok\Core\Module\Dashboard\Controller@getContent"));

// Module Profile

\Route::any('cmf/module/users-profile/action-param', array('as' => 'cmf.module.users-profile.action.param', 'uses' => "Telenok\Core\Module\Users\Profile\Controller@getActionParam"));
\Route::any('cmf/module/users-profile', array('as' => 'cmf.module.users-profile', 'uses' => "Telenok\Core\Module\Users\Profile\Controller@getContent"));


// Module Objects\Type
\Route::any('cmf/module/objects-type/action-param', array('as' => 'cmf.module.objects-type.action.param', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@getActionParam"));
\Route::any('cmf/module/objects-type/get/namespace-model-by-path', array('as' => 'cmf.module.objects-type.get.namespace-model-by-path', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@getNamespaceModelContent"));
\Route::any('cmf/module/objects-type/get/namespace-form-by-path', array('as' => 'cmf.module.objects-type.get.namespace-form-by-path', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@getNamespaceFormContent"));
//\Route::any('cmf/module/objects-type', array('as' => 'cmf.module.objects-type', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@getContent"));

/*
\Route::any('cmf/module/objects-type/list', array('as' => 'cmf.module.objects-type.list', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@getList"));
\Route::any('cmf/module/objects-type/create', array('as' => 'cmf.module.objects-type.create', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@create"));
\Route::any('cmf/module/objects-type/edit/{id}', array('as' => 'cmf.module.objects-type.edit', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@edit"));
\Route::any('cmf/module/objects-type/delete/{id}', array('as' => 'cmf.module.objects-type.delete', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@delete"));
\Route::any('cmf/module/objects-type/list/edit', array('as' => 'cmf.module.objects-type.list.edit', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@editList"));
\Route::any('cmf/module/objects-type/list/delete', array('as' => 'cmf.module.objects-type.list.delete', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@deleteList"));
\Route::any('cmf/module/objects-type/store', array('as' => 'cmf.module.objects-type.store', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@store"));
\Route::any('cmf/module/objects-type/update', array('as' => 'cmf.module.objects-type.update', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@update"));
\Route::any('cmf/module/objects-type/list/tree', array('as' => 'cmf.module.objects-type.list.tree', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@getTreeList"));
*/

// Module Objects\Field 
\Route::any('cmf/module/objects-field/get/namespace-by-path', array('as' => 'cmf.module.objects-field.get.namespace-by-path', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getNamespaceContent"));
\Route::any('cmf/module/objects-field/action-param', array('as' => 'cmf.module.objects-field.action.param', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getActionParam"));
\Route::any('cmf/module/objects-field/list/tree', array('as' => 'cmf.module.objects-field.list.tree', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getTreeList"));
\Route::any('cmf/module/objects-field', array('as' => 'cmf.module.objects-field', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getContent"));
\Route::any('cmf/module/objects-field/list', array('as' => 'cmf.module.objects-field.list', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getList"));
/*
\Route::any('cmf/module/objects-field/create', array('as' => 'cmf.module.objects-field.create', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@create"));
\Route::any('cmf/module/objects-field/edit/{id}', array('as' => 'cmf.module.objects-field.edit', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@edit"));
\Route::any('cmf/module/objects-field/delete/{id}', array('as' => 'cmf.module.objects-field.delete', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@delete"));
\Route::any('cmf/module/objects-field/list/edit', array('as' => 'cmf.module.objects-field.list.edit', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@editList"));
\Route::any('cmf/module/objects-field/list/delete', array('as' => 'cmf.module.objects-field.list.delete', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@deleteList"));
\Route::any('cmf/module/objects-field/store', array('as' => 'cmf.module.objects-field.store', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@store"));
\Route::any('cmf/module/objects-field/update', array('as' => 'cmf.module.objects-field.update', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@update"));
*/
// Module Objects\Lists
\Route::any('cmf/module/objects-lists/action-param', array('as' => 'cmf.module.objects-lists.action.param', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getActionParam"));
\Route::any('cmf/module/objects-lists', array('as' => 'cmf.module.objects-lists', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getContent"));
\Route::any('cmf/module/objects-lists/create/type/{id}', array('as' => 'cmf.module.objects-lists.create', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@create"));
\Route::any('cmf/module/objects-lists/edit/{id}', array('as' => 'cmf.module.objects-lists.edit', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@edit"));
\Route::any('cmf/module/objects-lists/store/type/{id}', array('as' => 'cmf.module.objects-lists.store', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@store"));
\Route::any('cmf/module/objects-lists/update/type/{id}', array('as' => 'cmf.module.objects-lists.update', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@update"));
\Route::any('cmf/module/objects-lists/delete/{id}', array('as' => 'cmf.module.objects-lists.delete', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@delete"));
\Route::any('cmf/module/objects-lists/list', array('as' => 'cmf.module.objects-lists.list', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getList"));
\Route::any('cmf/module/objects-lists/list/edit/type/{id}', array('as' => 'cmf.module.objects-lists.list.edit', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@editList"));
\Route::any('cmf/module/objects-lists/list/delete', array('as' => 'cmf.module.objects-lists.list.delete', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@deleteList"));
\Route::any('cmf/module/objects-lists/list/tree', array('as' => 'cmf.module.objects-lists.list.tree', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getTreeList"));
\Route::any('cmf/module/objects-lists/get/namespace-by-path', array('as' => 'cmf.module.objects-lists.get.namespace-by-path', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getNamespaceContent"));

\Route::any('cmf/module/objects-lists/wizard/create/type/{id}', array('as' => 'cmf.module.objects-lists.wizard.create', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@create"));
\Route::any('cmf/module/objects-lists/wizard/edit/{id}', array('as' => 'cmf.module.objects-lists.wizard.edit', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@edit"));
\Route::any('cmf/module/objects-lists/wizard/store/type/{id}', array('as' => 'cmf.module.objects-lists.wizard.store', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@store"));
\Route::any('cmf/module/objects-lists/wizard/update/type/{id}', array('as' => 'cmf.module.objects-lists.wizard.update', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@update"));
\Route::any('cmf/module/objects-lists/wizard/delete/{id}', array('as' => 'cmf.module.objects-lists.wizard.delete', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@delete"));
\Route::any('cmf/module/objects-lists/wizard/choose/type/{id}', array('as' => 'cmf.module.objects-lists.wizard.choose', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@choose"));
\Route::any('cmf/module/objects-lists/wizard/list/type/{id}', array('as' => 'cmf.module.objects-lists.wizard.list', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@getWizardList"));

// Module Objects\Version
\Route::any('cmf/module/objects-version/action-param', array('as' => 'cmf.module.objects-version.action.param', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@getActionParam"));
\Route::any('cmf/module/objects-version', array('as' => 'cmf.module.objects-version', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@getContent"));
\Route::any('cmf/module/objects-version/list', array('as' => 'cmf.module.objects-version.list', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@getList"));
/*\Route::any('cmf/module/objects-version/create', array('as' => 'cmf.module.objects-version.create', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@create"));
\Route::any('cmf/module/objects-version/edit/{id}', array('as' => 'cmf.module.objects-version.edit', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@edit"));
\Route::any('cmf/module/objects-version/delete/{id}', array('as' => 'cmf.module.objects-version.delete', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@delete"));
\Route::any('cmf/module/objects-version/list/edit', array('as' => 'cmf.module.objects-version.list.edit', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@editList"));
\Route::any('cmf/module/objects-version/list/delete', array('as' => 'cmf.module.objects-version.list.delete', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@deleteList"));
\Route::any('cmf/module/objects-version/store', array('as' => 'cmf.module.objects-version.store', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@store"));
\Route::any('cmf/module/objects-version/update', array('as' => 'cmf.module.objects-version.update', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@update"));
\Route::any('cmf/module/objects-version/list/tree', array('as' => 'cmf.module.objects-version.list.tree', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@getTreeList"));
*/
// Module Objects\Sequence
\Route::any('cmf/module/objects-sequence/list', array('as' => 'cmf.module.objects-sequence.list', 'uses' => "Telenok\Core\Module\Objects\Sequence\Controller@getList"));


// Module Users
\Route::any('cmf/module/users/action-param', array('as' => 'cmf.module.users.action.param', 'uses' => "Telenok\Core\Module\Users\Controller@getActionParam"));
\Route::any('cmf/module/users/profile/action-param', array('as' => 'cmf.module.users-profile.action.param', 'uses' => "Telenok\Core\Module\Users\Profile\Controller@getActionParam"));
 
 







// Module Files\Browser
\Route::any('cmf/module/file-browser/wizard/list', array('as' => 'cmf.module.file-browser.wizard.list', 'uses' => "Telenok\Core\Module\Files\Browser\ControllerWizardDirectory@getListContent"));
\Route::any('cmf/module/file-browser/wizard/tree', array('as' => 'cmf.module.file-browser.wizard.tree', 'uses' => "Telenok\Core\Module\Files\Browser\ControllerWizardDirectory@getTreeList"));
\Route::any('cmf/module/file-browser/wizard/process', array('as' => 'cmf.module.file-browser.wizard.process', 'uses' => "Telenok\Core\Module\Files\Browser\ControllerWizardDirectory@processTree"));



// Module Files\Lists
/*
\Route::any('cmf/module/files-lists', array('as' => 'cmf.module.files-lists', 'uses' => "Telenok\Core\Module\Files\Lists\Controller@getContent"));
\Route::any('cmf/module/files-lists/action-param', array('as' => 'cmf.module.files-lists.action.param', 'uses' => "Telenok\Core\Module\Files\Lists\Controller@getActionParam"));
\Route::any('cmf/module/files-lists/list', array('as' => 'cmf.module.files-lists.list', 'uses' => "Telenok\Core\Module\Files\Lists\Controller@getList"));
\Route::any('cmf/module/files-lists/create', array('as' => 'cmf.module.files-lists.create', 'uses' => "Telenok\Core\Module\Files\Lists\Controller@create"));
\Route::any('cmf/module/files-lists/edit/{id}', array('as' => 'cmf.module.files-lists.edit', 'uses' => "Telenok\Core\Module\Files\Lists\Controller@edit"));
\Route::any('cmf/module/files-lists/store', array('as' => 'cmf.module.files-lists.store', 'uses' => "Telenok\Core\Module\Files\Lists\Controller@store"));
\Route::any('cmf/module/files-lists/update/{id}', array('as' => 'cmf.module.files-lists.update', 'uses' => "Telenok\Core\Module\Files\Lists\Controller@update"));
\Route::any('cmf/module/files-lists/delete/{id}', array('as' => 'cmf.module.files-lists.delete', 'uses' => "Telenok\Core\Module\Files\Lists\Controller@delete"));
\Route::any('cmf/module/files-lists/list/edit', array('as' => 'cmf.module.files-lists.list.edit', 'uses' => "Telenok\Core\Module\Files\Lists\Controller@editList"));
\Route::any('cmf/module/files-lists/list/delete', array('as' => 'cmf.module.files-lists.list.delete', 'uses' => "Telenok\Core\Module\Files\Lists\Controller@deleteList"));
*/


// Module System\Setting
\Route::any('cmf/module/system-setting/action-param', array('as' => 'cmf.module.system-setting.action.param', 'uses' => "Telenok\Core\Module\System\Setting\Controller@getActionParam"));
\Route::any('cmf/module/system-setting/save', array('as' => 'cmf.module.system-setting.save', 'uses' => "Telenok\Core\Module\System\Setting\Controller@save"));


// Module Web\Page
\Route::any('cmf/module/web-page/action-param', array('as' => 'cmf.module.web-page.action.param', 'uses' => "Telenok\Core\Module\Web\Page\Controller@getActionParam"));
\Route::any('cmf/module/web-page/list/page', array('as' => 'cmf.module.web-page.list.page', 'uses' => "Telenok\Core\Module\Web\Page\Controller@getListPage"));
\Route::any('cmf/module/web-page/view/page/container/{id}/language-id/{languageId}', array('as' => 'cmf.module.web-page.view.page.container', 'uses' => "Telenok\Core\Module\Web\Page\Controller@viewPageContainer"));
\Route::any('cmf/module/web-page/view/page/insert/language-id/{languageId}/page-id/{pageId}/widget-key/{key}/widget-id/{id}/container/{container}/bufferId/{bufferId}/order/{order}/', array('as' => 'cmf.module.web-page.view.page.insert.widget', 'uses' => "Telenok\Core\Module\Web\Page\Controller@insertWidget"));
\Route::any('cmf/module/web-page/view/page/remove/widget-id/{id}/', array('as' => 'cmf.module.web-page.view.page.remove.widget', 'uses' => "Telenok\Core\Module\Web\Page\Controller@removeWidget"));
\Route::any('cmf/module/web-page/view/page/widget/buffer/add/{id}/key/{key}', array('as' => 'cmf.module.web-page.view.buffer.add.widget', 'uses' => "Telenok\Core\Module\Web\Page\Controller@addBufferWidget"));
\Route::any('cmf/module/web-page/view/page/widget/buffer/delete/{id}', array('as' => 'cmf.module.web-page.view.buffer.delete.widget', 'uses' => "Telenok\Core\Module\Web\Page\Controller@deleteBufferWidget"));


// Module Web\WidgetOnPage
/*
\Route::any('cmf/module/web-page/widget-on-page/action-param', array('as' => 'cmf.module.web-page-wop.action.param', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@getActionParam"));
\Route::any('cmf/module/web-page/widget-on-page', array('as' => 'cmf.module.web-page-wop', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@getContent"));
\Route::any('cmf/module/web-page/widget-on-page/list', array('as' => 'cmf.module.web-page-wop.list', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@getList"));
\Route::any('cmf/module/web-page/widget-on-page/create', array('as' => 'cmf.module.web-page-wop.create', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@create"));
\Route::any('cmf/module/web-page/widget-on-page/edit/{id}', array('as' => 'cmf.module.web-page-wop.edit', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@edit"));
\Route::any('cmf/module/web-page/widget-on-page/store', array('as' => 'cmf.module.web-page-wop.store', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@store"));
\Route::any('cmf/module/web-page/widget-on-page/update/{id}', array('as' => 'cmf.module.web-page-wop.update', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@update"));
\Route::any('cmf/module/web-page/widget-on-page/delete/{id}', array('as' => 'cmf.module.web-page-wop.delete', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@delete"));
\Route::any('cmf/module/web-page/widget-on-page/list', array('as' => 'cmf.module.web-page-wop.list', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@getList"));
\Route::any('cmf/module/web-page/widget-on-page/list/edit', array('as' => 'cmf.module.web-page-wop.list.edit', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@editList"));
\Route::any('cmf/module/web-page/widget-on-page/list/delete', array('as' => 'cmf.module.web-page-wop.list.delete', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@deleteList"));
*/ 


// Module Workflow Process
\Route::any('cmf/module/workflow-process/diagram/show/{id}', array('as' => 'cmf.module.workflow-process.diagram.show', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@diagramShow"));
\Route::any('cmf/module/workflow-process/diagram/stensilset', array('as' => 'cmf.module.workflow-process.diagram.stensilset', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@diagramStencilSet"));
/*
\Route::any('cmf/module/workflow-process/action-param', array('as' => 'cmf.module.workflow-process.action.param', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@getActionParam"));
\Route::any('cmf/module/workflow-process', array('as' => 'cmf.module.workflow-process', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@getContent"));
\Route::any('cmf/module/workflow-process/list', array('as' => 'cmf.module.workflow-process.list', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@getList"));
\Route::any('cmf/module/workflow-process/create', array('as' => 'cmf.module.workflow-process.create', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@create"));
\Route::any('cmf/module/workflow-process/edit/{id}', array('as' => 'cmf.module.workflow-process.edit', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@edit"));
\Route::any('cmf/module/workflow-process/store', array('as' => 'cmf.module.workflow-process.store', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@store"));
\Route::any('cmf/module/workflow-process/update/{id}', array('as' => 'cmf.module.workflow-process.update', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@update"));
\Route::any('cmf/module/workflow-process/delete/{id}', array('as' => 'cmf.module.workflow-process.delete', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@delete"));
\Route::any('cmf/module/workflow-process/list', array('as' => 'cmf.module.workflow-process.list', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@getList"));
\Route::any('cmf/module/workflow-process/list/edit', array('as' => 'cmf.module.workflow-process.list.edit', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@editList"));
\Route::any('cmf/module/workflow-process/list/delete', array('as' => 'cmf.module.workflow-process.list.delete', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@deleteList"));
*/
// Module Workflow Thread
/*
\Route::any('cmf/module/workflow-thread/action-param', array('as' => 'cmf.module.workflow-thread.action.param', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@getActionParam"));
\Route::any('cmf/module/workflow-thread', array('as' => 'cmf.module.workflow-thread', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@getContent"));
\Route::any('cmf/module/workflow-thread/list', array('as' => 'cmf.module.workflow-thread.list', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@getList"));
\Route::any('cmf/module/workflow-thread/create', array('as' => 'cmf.module.workflow-thread.create', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@create"));
\Route::any('cmf/module/workflow-thread/edit/{id}', array('as' => 'cmf.module.workflow-thread.edit', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@edit"));
\Route::any('cmf/module/workflow-thread/store', array('as' => 'cmf.module.workflow-thread.store', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@store"));
\Route::any('cmf/module/workflow-thread/update/{id}', array('as' => 'cmf.module.workflow-thread.update', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@update"));
\Route::any('cmf/module/workflow-thread/delete/{id}', array('as' => 'cmf.module.workflow-thread.delete', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@delete"));
\Route::any('cmf/module/workflow-thread/list', array('as' => 'cmf.module.workflow-thread.list', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@getList"));
\Route::any('cmf/module/workflow-thread/list/edit', array('as' => 'cmf.module.workflow-thread.list.edit', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@editList"));
\Route::any('cmf/module/workflow-thread/list/delete', array('as' => 'cmf.module.workflow-thread.list.delete', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@deleteList"));
*/



 

 
// Widget Html
/*
\Route::any('cmf/widget/html/content', array('as' => 'cmf.widget.html.content', 'uses' => "Telenok\Core\Widget\Html\Controller@getContent"));
\Route::any('cmf/widget/html/setting/get', array('as' => 'cmf.widget.html.setting.get', 'uses' => "Telenok\Core\Widget\Html\Controller@getSettingContent"));
\Route::any('cmf/widget/html/setting/set', array('as' => 'cmf.widget.html.setting.set', 'uses' => "Telenok\Core\Widget\Html\Controller@setSetting"));

*/

