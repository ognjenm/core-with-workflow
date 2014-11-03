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
\Route::any('telenok/error', array('as' => 'error.access-denied', 'uses' => "Telenok\Core\Controller\Backend\Controller@errorAccessDenied"));

\Route::any('telenok', array('as' => 'cmf.content', 'uses' => "Telenok\Core\Controller\Backend\Controller@getContent"));
 
\Route::any('telenok/login', array('as' => 'cmf.login', 'uses' => "Telenok\Core\Controller\Backend\Controller@login"));
\Route::any('telenok/logout', array('as' => 'cmf.logout', 'uses' => "Telenok\Core\Controller\Backend\Controller@logout"));

\Route::any('telenok/clear-cache', array('as' => 'cmf.clear.cache', function()
{
    \Event::fire('telenok.compile.route');
}));

\Route::any('telenok/install', array('as' => 'cmf.install', 'uses' => "Telenok\Core\Controller\Install\Controller@getInstallContent"));
\Route::any('telenok/install/verify', array('as' => 'cmf.install.process', 'uses' => "Telenok\Core\Controller\Install\Controller@getInstall@process"));


// Fields
\Route::any('telenok/field/relation-one-to-one/list/title/type/{id}', array('as' => 'cmf.field.relation-one-to-one.list.title', 'uses' => "Telenok\Core\Field\RelationOneToOne\Controller@getTitleList"));

\Route::any('telenok/field/relation-one-to-many/list/title/type/{id}', array('as' => 'cmf.field.relation-one-to-many.list.title', 'uses' => "Telenok\Core\Field\RelationOneToMany\Controller@getTitleList"));
\Route::any('telenok/field/relation-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.relation-one-to-many.list.table', 'uses' => "Telenok\Core\Field\RelationOneToMany\Controller@getTableList"));

\Route::any('telenok/field/relation-many-to-many/list/title/type/{id}', array('as' => 'cmf.field.relation-many-to-many.list.title', 'uses' => "Telenok\Core\Field\RelationManyToMany\Controller@getTitleList"));
\Route::any('telenok/field/relation-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.relation-many-to-many.list.table', 'uses' => "Telenok\Core\Field\RelationManyToMany\Controller@getTableList"));

\Route::any('telenok/field/tree/list/title/type/{id}', array('as' => 'cmf.field.tree.list.title', 'uses' => "Telenok\Core\Field\System\Tree\Controller@getTitleList"));
\Route::any('telenok/field/tree/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.tree.list.table', 'uses' => "Telenok\Core\Field\System\Tree\Controller@getTableList"));

\Route::any('telenok/field/morph-many-to-many/list/title/type/{id}', array('as' => 'cmf.field.morph-many-to-many.list.title', 'uses' => "Telenok\Core\Field\MorphManyToMany\Controller@getTitleList"));
\Route::any('telenok/field/morph-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.morph-many-to-many.list.table', 'uses' => "Telenok\Core\Field\MorphManyToMany\Controller@getTableList"));

\Route::any('telenok/field/morph-one-to-many/list/title/type/{id}', array('as' => 'cmf.field.morph-one-to-many.list.title', 'uses' => "Telenok\Core\Field\MorphOneToMany\Controller@getTitleList"));
\Route::any('telenok/field/morph-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.morph-one-to-many.list.table', 'uses' => "Telenok\Core\Field\MorphOneToMany\Controller@getTableList"));

\Route::any('telenok/field/morph-one-to-one/list/title/type/{id}', array('as' => 'cmf.field.morph-one-to-one.list.title', 'uses' => "Telenok\Core\Field\MorphOneToOne\Controller@getTitleList"));

\Route::any('telenok/field/permission/list/title', array('as' => 'cmf.field.permission.list.title', 'uses' => "Telenok\Core\Field\System\Permission\Controller@getTitleList"));
 
\Route::any('telenok/field/file-many-to-many/upload', array('as' => 'cmf.field.file-many-to-many.upload', 'uses' => "Telenok\Core\Field\FileManyToMany\Controller@upload"));
 

// Module Dashboard

\Route::any('telenok/module/dashboard', array('as' => 'cmf.module.dashboard', 'uses' => "Telenok\Core\Module\Dashboard\Controller@getContent"));

// Module Profile
\Route::any('telenok/module/users-profile/action-param', array('as' => 'cmf.module.users-profile.action.param', 'uses' => "Telenok\Core\Module\Users\Profile\Controller@getActionParam"));
\Route::any('telenok/module/users-profile', array('as' => 'cmf.module.users-profile', 'uses' => "Telenok\Core\Module\Users\Profile\Controller@getContent"));
// Module Users
//\Route::any('telenok/module/users/action-param', array('as' => 'cmf.module.users.action.param', 'uses' => "Telenok\Core\Module\Users\Controller@getActionParam"));
//\Route::any('telenok/module/users/profile/action-param', array('as' => 'cmf.module.users-profile.action.param', 'uses' => "Telenok\Core\Module\Users\Profile\Controller@getActionParam"));


// Module Objects\Type
\Route::any('telenok/module/objects-type/action-param', array('as' => 'cmf.module.objects-type.action.param', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@getActionParam"));
\Route::any('telenok/module/objects-type/get/namespace-model-by-path', array('as' => 'cmf.module.objects-type.get.namespace-model-by-path', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@getNamespaceModelContent"));
\Route::any('telenok/module/objects-type/get/namespace-form-by-path', array('as' => 'cmf.module.objects-type.get.namespace-form-by-path', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@getNamespaceFormContent"));

// Module Objects\Field 
\Route::any('telenok/module/objects-field/get/namespace-by-path', array('as' => 'cmf.module.objects-field.get.namespace-by-path', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getNamespaceContent"));
\Route::any('telenok/module/objects-field/action-param', array('as' => 'cmf.module.objects-field.action.param', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getActionParam"));
\Route::any('telenok/module/objects-field/list/tree', array('as' => 'cmf.module.objects-field.list.tree', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getTreeList"));
\Route::any('telenok/module/objects-field', array('as' => 'cmf.module.objects-field', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getContent"));
\Route::any('telenok/module/objects-field/list', array('as' => 'cmf.module.objects-field.list', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getList"));

// Module Objects\Lists
\Route::any('telenok/module/objects-lists/action-param', array('as' => 'cmf.module.objects-lists.action.param', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getActionParam"));
\Route::any('telenok/module/objects-lists', array('as' => 'cmf.module.objects-lists', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getContent"));
\Route::any('telenok/module/objects-lists/create/type', array('as' => 'cmf.module.objects-lists.create', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@create"));
\Route::any('telenok/module/objects-lists/edit', array('as' => 'cmf.module.objects-lists.edit', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@edit"));
\Route::any('telenok/module/objects-lists/store/type/{id}', array('as' => 'cmf.module.objects-lists.store', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@store"));
\Route::any('telenok/module/objects-lists/update/type/{id}', array('as' => 'cmf.module.objects-lists.update', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@update"));
\Route::any('telenok/module/objects-lists/delete/{id}', array('as' => 'cmf.module.objects-lists.delete', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@delete"));
\Route::any('telenok/module/objects-lists/list', array('as' => 'cmf.module.objects-lists.list', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getList"));
\Route::any('telenok/module/objects-lists/list/edit/type/{id}', array('as' => 'cmf.module.objects-lists.list.edit', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@editList"));
\Route::any('telenok/module/objects-lists/list/delete', array('as' => 'cmf.module.objects-lists.list.delete', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@deleteList"));
\Route::any('telenok/module/objects-lists/list/lock', array('as' => 'cmf.module.objects-lists.list.lock', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@lockList"));
\Route::any('telenok/module/objects-lists/lock', array('as' => 'cmf.module.objects-lists.lock', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@lock"));
\Route::any('telenok/module/objects-lists/list/unlock', array('as' => 'cmf.module.objects-lists.list.unlock', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@unlockList"));
\Route::any('telenok/module/objects-lists/list/tree', array('as' => 'cmf.module.objects-lists.list.tree', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getTreeList"));
\Route::any('telenok/module/objects-lists/get/namespace-by-path', array('as' => 'cmf.module.objects-lists.get.namespace-by-path', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getNamespaceContent"));

\Route::any('telenok/module/objects-lists/wizard/create/type', array('as' => 'cmf.module.objects-lists.wizard.create', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@create"));
\Route::any('telenok/module/objects-lists/wizard/edit', array('as' => 'cmf.module.objects-lists.wizard.edit', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@edit"));
\Route::any('telenok/module/objects-lists/wizard/store/type/{id}', array('as' => 'cmf.module.objects-lists.wizard.store', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@store"));
\Route::any('telenok/module/objects-lists/wizard/update/type/{id}', array('as' => 'cmf.module.objects-lists.wizard.update', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@update"));
\Route::any('telenok/module/objects-lists/wizard/delete/{id}', array('as' => 'cmf.module.objects-lists.wizard.delete', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@delete"));
\Route::any('telenok/module/objects-lists/wizard/choose', array('as' => 'cmf.module.objects-lists.wizard.choose', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@choose"));
\Route::any('telenok/module/objects-lists/wizard/list', array('as' => 'cmf.module.objects-lists.wizard.list', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@getWizardList"));

// Module Objects\Version
\Route::any('telenok/module/objects-version/action-param', array('as' => 'cmf.module.objects-version.action.param', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@getActionParam"));
\Route::any('telenok/module/objects-version', array('as' => 'cmf.module.objects-version', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@getContent"));
\Route::any('telenok/module/objects-version/list', array('as' => 'cmf.module.objects-version.list', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@getList"));

// Module Objects\Sequence
\Route::any('telenok/module/objects-sequence/list', array('as' => 'cmf.module.objects-sequence.list', 'uses' => "Telenok\Core\Module\Objects\Sequence\Controller@getList"));

// Module Web Domain
\Route::any('telenok/module/web-domain/action-param', array('as' => 'cmf.module.web-domain.action.param', 'uses' => "Telenok\Core\Module\Web\Domain\Controller@getActionParam"));

// Module Page Controller
\Route::any('telenok/module/web-page-controller/action-param', array('as' => 'cmf.module.web-page-controller.action.param', 'uses' => "Telenok\Core\Module\Web\PageController\Controller@getActionParam"));

// Module Files
\Route::any('telenok/module/files/browser/action-param', array('as' => 'cmf.module.files-browser.action.param', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@getActionParam"));
\Route::any('telenok/module/files/browser', array('as' => 'cmf.module.files-browser', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@getContent"));
\Route::any('telenok/module/files/browser/list', array('as' => 'cmf.module.files-browser.list', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@getList"));
\Route::any('telenok/module/files/browser/create', array('as' => 'cmf.module.files-browser.create', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@create"));
\Route::any('telenok/module/files/browser/edit', array('as' => 'cmf.module.files-browser.edit', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@edit"));
\Route::any('telenok/module/files/browser/store', array('as' => 'cmf.module.files-browser.store', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@store"));
\Route::any('telenok/module/files/browser/update', array('as' => 'cmf.module.files-browser.update', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@update"));
\Route::any('telenok/module/files/browser/delete', array('as' => 'cmf.module.files-browser.delete', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@delete"));






// Module Files\Browser
//\Route::any('telenok/module/file-browser/wizard/list', array('as' => 'cmf.module.file-browser.wizard.list', 'uses' => "Telenok\Core\Module\Files\Browser\Wizard\Directory\Controller@getListContent"));
//\Route::any('telenok/module/file-browser/wizard/tree', array('as' => 'cmf.module.file-browser.wizard.tree', 'uses' => "Telenok\Core\Module\Files\Browser\Wizard\Directory\Controller@getTreeList"));
//\Route::any('telenok/module/file-browser/wizard/process', array('as' => 'cmf.module.file-browser.wizard.process', 'uses' => "Telenok\Core\Module\Files\Browser\Wizard\Directory\Controller@processTree"));



// Module System\Setting
\Route::any('telenok/module/system-setting/action-param', array('as' => 'cmf.module.system-setting.action.param', 'uses' => "Telenok\Core\Module\System\Setting\Controller@getActionParam"));
\Route::any('telenok/module/system-setting/save', array('as' => 'cmf.module.system-setting.save', 'uses' => "Telenok\Core\Module\System\Setting\Controller@save"));


// Module Web\PageConstructor
\Route::any('telenok/module/web-page-constructor/action-param', array('as' => 'cmf.module.web-page-constructor.action.param', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@getActionParam"));
\Route::any('telenok/module/web-page-constructor/list/page', array('as' => 'cmf.module.web-page-constructor.list.page', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@getListPage"));
\Route::any('telenok/module/web-page-constructor/view/page/container/{id}/language-id/{languageId}', array('as' => 'cmf.module.web-page-constructor.view.page.container', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@viewPageContainer"));
\Route::any('telenok/module/web-page-constructor/view/page/insert/language-id/{languageId}/page-id/{pageId}/widget-key/{key}/widget-id/{id}/container/{container}/bufferId/{bufferId}/order/{order}/', array('as' => 'cmf.module.web-page-constructor.view.page.insert.widget', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@insertWidget"));
\Route::any('telenok/module/web-page-constructor/view/page/remove/widget-id/{id}/', array('as' => 'cmf.module.web-page-constructor.view.page.remove.widget', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@removeWidget"));
\Route::any('telenok/module/web-page-constructor/view/page/widget/buffer/add/{id}/key/{key}', array('as' => 'cmf.module.web-page-constructor.view.buffer.add.widget', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@addBufferWidget"));
\Route::any('telenok/module/web-page-constructor/view/page/widget/buffer/delete/{id}', array('as' => 'cmf.module.web-page-constructor.view.buffer.delete.widget', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@deleteBufferWidget"));

// Module Web\Page
\Route::any('telenok/module/web-page/action-param', array('as' => 'cmf.module.web-page.action.param', 'uses' => "Telenok\Core\Module\Web\Page\Controller@getActionParam"));

// Module Web\WidgetOnPage
/*
\Route::any('telenok/module/web-page/widget-on-page/action-param', array('as' => 'cmf.module.web-page-wop.action.param', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@getActionParam"));
\Route::any('telenok/module/web-page/widget-on-page', array('as' => 'cmf.module.web-page-wop', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@getContent"));
\Route::any('telenok/module/web-page/widget-on-page/list', array('as' => 'cmf.module.web-page-wop.list', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@getList"));
\Route::any('telenok/module/web-page/widget-on-page/create', array('as' => 'cmf.module.web-page-wop.create', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@create"));
\Route::any('telenok/module/web-page/widget-on-page/edit/{id}', array('as' => 'cmf.module.web-page-wop.edit', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@edit"));
\Route::any('telenok/module/web-page/widget-on-page/store', array('as' => 'cmf.module.web-page-wop.store', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@store"));
\Route::any('telenok/module/web-page/widget-on-page/update/{id}', array('as' => 'cmf.module.web-page-wop.update', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@update"));
\Route::any('telenok/module/web-page/widget-on-page/delete/{id}', array('as' => 'cmf.module.web-page-wop.delete', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@delete"));
\Route::any('telenok/module/web-page/widget-on-page/list', array('as' => 'cmf.module.web-page-wop.list', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@getList"));
\Route::any('telenok/module/web-page/widget-on-page/list/edit', array('as' => 'cmf.module.web-page-wop.list.edit', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@editList"));
\Route::any('telenok/module/web-page/widget-on-page/list/delete', array('as' => 'cmf.module.web-page-wop.list.delete', 'uses' => "Telenok\Core\Module\Web\WidgetOnPage\Controller@deleteList"));
*/ 


// Module Workflow Process
\Route::any('telenok/module/workflow-process/action-param', array('as' => 'cmf.module.workflow-process.action.param', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@getActionParam"));
\Route::any('telenok/module/workflow-process/diagram/show', array('as' => 'cmf.module.workflow-process.diagram.show', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@diagramShow"));
\Route::any('telenok/module/workflow-process/diagram/stensilset', array('as' => 'cmf.module.workflow-process.diagram.stensilset', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@diagramStencilSet"));
\Route::any('telenok/module/workflow/store/property', array('as' => 'cmf.workflow.store-property', 'uses' => "Telenok\Core\Interfaces\Workflow\Element@storeProperty"));
\Route::any('telenok/module/workflow/apply/diagram', array('as' => 'cmf.workflow.apply-diagram', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@applyDiagram"));

\Route::any('telenok/module/workflow/element/start/point-model/property', array('as' => 'cmf.workflow.point-model.property', 'uses' => "Telenok\Core\Workflow\Point\Start\Model@getPropertyContent"));
\Route::any('telenok/module/workflow/element/end/point-end/property', array('as' => 'cmf.workflow.point-end.property', 'uses' => "Telenok\Core\Workflow\Point\End\End@getPropertyContent"));
\Route::any('telenok/module/workflow/element/form-element-hide/property', array('as' => 'cmf.workflow.form-element-hide.property', 'uses' => "Telenok\Core\Workflow\Activity\FormElementHide@getPropertyContent"));



/*\Route::any('telenok/module/workflow-process', array('as' => 'cmf.module.workflow-process', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@getContent"));
\Route::any('telenok/module/workflow-process/list', array('as' => 'cmf.module.workflow-process.list', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@getList"));
\Route::any('telenok/module/workflow-process/create', array('as' => 'cmf.module.workflow-process.create', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@create"));
\Route::any('telenok/module/workflow-process/edit/{id}', array('as' => 'cmf.module.workflow-process.edit', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@edit"));
\Route::any('telenok/module/workflow-process/store', array('as' => 'cmf.module.workflow-process.store', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@store"));
\Route::any('telenok/module/workflow-process/update/{id}', array('as' => 'cmf.module.workflow-process.update', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@update"));
\Route::any('telenok/module/workflow-process/delete/{id}', array('as' => 'cmf.module.workflow-process.delete', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@delete"));
\Route::any('telenok/module/workflow-process/list', array('as' => 'cmf.module.workflow-process.list', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@getList"));
\Route::any('telenok/module/workflow-process/list/edit', array('as' => 'cmf.module.workflow-process.list.edit', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@editList"));
\Route::any('telenok/module/workflow-process/list/delete', array('as' => 'cmf.module.workflow-process.list.delete', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@deleteList"));
*/
// Module Workflow Thread
/*
\Route::any('telenok/module/workflow-thread/action-param', array('as' => 'cmf.module.workflow-thread.action.param', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@getActionParam"));
\Route::any('telenok/module/workflow-thread', array('as' => 'cmf.module.workflow-thread', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@getContent"));
\Route::any('telenok/module/workflow-thread/list', array('as' => 'cmf.module.workflow-thread.list', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@getList"));
\Route::any('telenok/module/workflow-thread/create', array('as' => 'cmf.module.workflow-thread.create', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@create"));
\Route::any('telenok/module/workflow-thread/edit/{id}', array('as' => 'cmf.module.workflow-thread.edit', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@edit"));
\Route::any('telenok/module/workflow-thread/store', array('as' => 'cmf.module.workflow-thread.store', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@store"));
\Route::any('telenok/module/workflow-thread/update/{id}', array('as' => 'cmf.module.workflow-thread.update', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@update"));
\Route::any('telenok/module/workflow-thread/delete/{id}', array('as' => 'cmf.module.workflow-thread.delete', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@delete"));
\Route::any('telenok/module/workflow-thread/list', array('as' => 'cmf.module.workflow-thread.list', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@getList"));
\Route::any('telenok/module/workflow-thread/list/edit', array('as' => 'cmf.module.workflow-thread.list.edit', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@editList"));
\Route::any('telenok/module/workflow-thread/list/delete', array('as' => 'cmf.module.workflow-thread.list.delete', 'uses' => "Telenok\Core\Module\Workflow\Thread\Controller@deleteList"));
*/



 

 
// Widget Html
/*
\Route::any('telenok/widget/html/content', array('as' => 'cmf.widget.html.content', 'uses' => "Telenok\Core\Widget\Html\Controller@getContent"));
\Route::any('telenok/widget/html/setting/get', array('as' => 'cmf.widget.html.setting.get', 'uses' => "Telenok\Core\Widget\Html\Controller@getSettingContent"));
\Route::any('telenok/widget/html/setting/set', array('as' => 'cmf.widget.html.setting.set', 'uses' => "Telenok\Core\Widget\Html\Controller@setSetting"));

*/

