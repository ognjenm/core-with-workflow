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

if (!\Request::is('telenok', 'telenok/*'))
{
    return;
}

\Route::filter('csrf', 'Telenok\Core\Filter\Router\Controller@csrf');
\Route::filter('auth', 'Telenok\Core\Filter\Router\Backend\Controller@auth');
\Route::filter('access-module', 'Telenok\Core\Filter\Router\Backend\Controller@accessModule');

\Route::when('/*', 'csrf', ['post']);
 
 
// Errors
\Route::get('telenok/error', array('as' => 'error.access-denied', 'uses' => "Telenok\Core\Controller\Backend\Controller@errorAccessDenied"));

\Route::get('telenok', array('as' => 'cmf.content', 'uses' => "Telenok\Core\Controller\Backend\Controller@getContent"));
 
\Route::any('telenok/login', array('as' => 'cmf.login', 'uses' => "Telenok\Core\Controller\Backend\Controller@login"));
\Route::post('telenok/logout', array('as' => 'cmf.logout', 'uses' => "Telenok\Core\Controller\Backend\Controller@logout"));

\Route::any('telenok/user/update/ui-setting', array('as' => 'cmf.user.update.ui-setting', 'uses' => "Telenok\Core\Controller\Backend\Controller@updateBackendUISetting"));

//\Route::any('telenok/install', array('as' => 'cmf.install', 'uses' => "Telenok\Core\Controller\Install\Controller@getInstallContent"));
//\Route::any('telenok/install/verify', array('as' => 'cmf.install.process', 'uses' => "Telenok\Core\Controller\Install\Controller@getInstall@process"));


// Fields
\Route::get('telenok/field/relation-one-to-one/list/title/type/{id}', array('as' => 'cmf.field.relation-one-to-one.list.title', 'uses' => "Telenok\Core\Field\RelationOneToOne\Controller@getTitleList"));

\Route::get('telenok/field/relation-one-to-many/list/title/type/{id}', array('as' => 'cmf.field.relation-one-to-many.list.title', 'uses' => "Telenok\Core\Field\RelationOneToMany\Controller@getTitleList"));
\Route::get('telenok/field/relation-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.relation-one-to-many.list.table', 'uses' => "Telenok\Core\Field\RelationOneToMany\Controller@getTableList"));

\Route::get('telenok/field/relation-many-to-many/list/title/type/{id}', array('as' => 'cmf.field.relation-many-to-many.list.title', 'uses' => "Telenok\Core\Field\RelationManyToMany\Controller@getTitleList"));
\Route::get('telenok/field/relation-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.relation-many-to-many.list.table', 'uses' => "Telenok\Core\Field\RelationManyToMany\Controller@getTableList"));

\Route::get('telenok/field/tree/list/title/type/{id}', array('as' => 'cmf.field.tree.list.title', 'uses' => "Telenok\Core\Field\System\Tree\Controller@getTitleList"));
\Route::get('telenok/field/tree/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.tree.list.table', 'uses' => "Telenok\Core\Field\System\Tree\Controller@getTableList"));

\Route::get('telenok/field/morph-many-to-many/list/title/type/{id}', array('as' => 'cmf.field.morph-many-to-many.list.title', 'uses' => "Telenok\Core\Field\MorphManyToMany\Controller@getTitleList"));
\Route::get('telenok/field/morph-many-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.morph-many-to-many.list.table', 'uses' => "Telenok\Core\Field\MorphManyToMany\Controller@getTableList"));

\Route::get('telenok/field/morph-one-to-many/list/title/type/{id}', array('as' => 'cmf.field.morph-one-to-many.list.title', 'uses' => "Telenok\Core\Field\MorphOneToMany\Controller@getTitleList"));
\Route::get('telenok/field/morph-one-to-many/list/table/model/{id}/field/{fieldId}/uniqueId/{uniqueId}', array('as' => 'cmf.field.morph-one-to-many.list.table', 'uses' => "Telenok\Core\Field\MorphOneToMany\Controller@getTableList"));

\Route::get('telenok/field/morph-one-to-one/list/title/type/{id}', array('as' => 'cmf.field.morph-one-to-one.list.title', 'uses' => "Telenok\Core\Field\MorphOneToOne\Controller@getTitleList"));

\Route::get('telenok/field/permission/list/title', array('as' => 'cmf.field.permission.list.title', 'uses' => "Telenok\Core\Field\System\Permission\Controller@getTitleList"));
 
\Route::post('telenok/field/file-many-to-many/upload', array('as' => 'cmf.field.file-many-to-many.upload', 'uses' => "Telenok\Core\Field\FileManyToMany\Controller@upload"));
 

// Module Dashboard 
\Route::get('telenok/module/dashboard', array('as' => 'cmf.module.dashboard', 'uses' => "Telenok\Core\Module\Dashboard\Controller@getContent"));

// Module Profile
\Route::get('telenok/module/users-profile-edit/action-param', array('as' => 'cmf.module.users-profile-edit.action.param', 'uses' => "Telenok\Core\Module\Users\ProfileEdit\Controller@getActionParam"));
\Route::get('telenok/module/users-profile-edit', array('as' => 'cmf.module.users-profile-edit', 'uses' => "Telenok\Core\Module\Users\ProfileEdit\Controller@getContent"));
\Route::post('telenok/module/users-profile-edit/update', array('as' => 'cmf.module.users-profile-edit.update', 'uses' => "Telenok\Core\Module\Users\ProfileEdit\Controller@update"));

// Module Users
//\Route::any('telenok/module/users/action-param', array('as' => 'cmf.module.users.action.param', 'uses' => "Telenok\Core\Module\Users\Controller@getActionParam"));
//\Route::any('telenok/module/users/profile/action-param', array('as' => 'cmf.module.users-profile-edit.action.param', 'uses' => "Telenok\Core\Module\Users\ProfileEdit\Controller@getActionParam"));


// Module Objects\Type
\Route::get('telenok/module/objects-type/action-param', array('as' => 'cmf.module.objects-type.action.param', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@getActionParam"));
//\Route::get('telenok/module/objects-type/get/namespace-model-by-path', array('as' => 'cmf.module.objects-type.get.namespace-model-by-path', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@getNamespaceModelContent"));
//\Route::get('telenok/module/objects-type/get/namespace-form-by-path', array('as' => 'cmf.module.objects-type.get.namespace-form-by-path', 'uses' => "Telenok\Core\Module\Objects\Type\Controller@getNamespaceFormContent"));

// Module Objects\Field 
//\Route::any('telenok/module/objects-field/get/namespace-by-path', array('as' => 'cmf.module.objects-field.get.namespace-by-path', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getNamespaceContent"));
\Route::get('telenok/module/objects-field/action-param', array('as' => 'cmf.module.objects-field.action.param', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getActionParam"));
\Route::get('telenok/module/objects-field/list/tree', array('as' => 'cmf.module.objects-field.list.tree', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getTreeList"));
\Route::get('telenok/module/objects-field', array('as' => 'cmf.module.objects-field', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getContent"));
\Route::get('telenok/module/objects-field/list', array('as' => 'cmf.module.objects-field.list', 'uses' => "Telenok\Core\Module\Objects\Field\Controller@getList"));


// Module Objects\Sequence
\Route::get('telenok/module/objects-sequence/action-param', array('as' => 'cmf.module.objects-sequence.action.param', 'uses' => "Telenok\Core\Module\Objects\Sequence\Controller@getActionParam"));
\Route::get('telenok/module/objects-sequence', array('as' => 'cmf.module.objects-sequence', 'uses' => "Telenok\Core\Module\Objects\Sequence\Controller@getContent"));
\Route::get('telenok/module/objects-sequence/list', array('as' => 'cmf.module.objects-sequence.list', 'uses' => "Telenok\Core\Module\Objects\Sequence\Controller@getList"));


// Module Objects\Lists
\Route::get('telenok/module/objects-lists/action-param', array('as' => 'cmf.module.objects-lists.action.param', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getActionParam"));
\Route::get('telenok/module/objects-lists', array('as' => 'cmf.module.objects-lists', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getContent"));
\Route::get('telenok/module/objects-lists/create/type', array('as' => 'cmf.module.objects-lists.create', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@create"));
\Route::get('telenok/module/objects-lists/edit', array('as' => 'cmf.module.objects-lists.edit', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@edit"));
\Route::post('telenok/module/objects-lists/store/type/{id}', array('as' => 'cmf.module.objects-lists.store', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@store"));
\Route::post('telenok/module/objects-lists/update/type/{id}', array('as' => 'cmf.module.objects-lists.update', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@update"));
\Route::post('telenok/module/objects-lists/delete/{id}', array('as' => 'cmf.module.objects-lists.delete', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@delete"));
\Route::get('telenok/module/objects-lists/list', array('as' => 'cmf.module.objects-lists.list', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getList"));
\Route::get('telenok/module/objects-lists/list/json', array('as' => 'cmf.module.objects-lists.list.json', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getListJson"));
\Route::get('telenok/module/objects-lists/list/edit/', array('as' => 'cmf.module.objects-lists.list.edit', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@editList"));
\Route::post('telenok/module/objects-lists/list/delete', array('as' => 'cmf.module.objects-lists.list.delete', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@deleteList"));
\Route::any('telenok/module/objects-lists/list/lock', array('as' => 'cmf.module.objects-lists.list.lock', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@lockList"));
\Route::any('telenok/module/objects-lists/lock', array('as' => 'cmf.module.objects-lists.lock', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@lock"));
\Route::any('telenok/module/objects-lists/list/unlock', array('as' => 'cmf.module.objects-lists.list.unlock', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@unlockList"));
\Route::get('telenok/module/objects-lists/list/tree', array('as' => 'cmf.module.objects-lists.list.tree', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getTreeList"));
//\Route::get('telenok/module/objects-lists/get/namespace-by-path', array('as' => 'cmf.module.objects-lists.get.namespace-by-path', 'uses' => "Telenok\Core\Module\Objects\Lists\Controller@getNamespaceContent"));

\Route::get('telenok/module/objects-lists/wizard/create/type', array('as' => 'cmf.module.objects-lists.wizard.create', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@create"));
\Route::get('telenok/module/objects-lists/wizard/edit', array('as' => 'cmf.module.objects-lists.wizard.edit', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@edit"));
\Route::post('telenok/module/objects-lists/wizard/store/type/{id}', array('as' => 'cmf.module.objects-lists.wizard.store', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@store"));
\Route::post('telenok/module/objects-lists/wizard/update/type/{id}', array('as' => 'cmf.module.objects-lists.wizard.update', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@update"));
\Route::post('telenok/module/objects-lists/wizard/delete/{id}', array('as' => 'cmf.module.objects-lists.wizard.delete', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@delete"));
\Route::get('telenok/module/objects-lists/wizard/choose', array('as' => 'cmf.module.objects-lists.wizard.choose', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@choose"));
\Route::get('telenok/module/objects-lists/wizard/list', array('as' => 'cmf.module.objects-lists.wizard.list', 'uses' => "Telenok\Core\Module\Objects\Lists\Wizard\Controller@getWizardList"));

// Module Objects\Version
\Route::get('telenok/module/objects-version/action-param', array('as' => 'cmf.module.objects-version.action.param', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@getActionParam"));
\Route::get('telenok/module/objects-version', array('as' => 'cmf.module.objects-version', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@getContent"));
\Route::get('telenok/module/objects-version/list', array('as' => 'cmf.module.objects-version.list', 'uses' => "Telenok\Core\Module\Objects\Version\Controller@getList"));

// Module Objects\Sequence
\Route::get('telenok/module/objects-sequence/list', array('as' => 'cmf.module.objects-sequence.list', 'uses' => "Telenok\Core\Module\Objects\Sequence\Controller@getList"));

// Module Web Domain
\Route::get('telenok/module/web-domain/action-param', array('as' => 'cmf.module.web-domain.action.param', 'uses' => "Telenok\Core\Module\Web\Domain\Controller@getActionParam"));

// Module Page Controller
\Route::get('telenok/module/web-page-controller/action-param', array('as' => 'cmf.module.web-page-controller.action.param', 'uses' => "Telenok\Core\Module\Web\PageController\Controller@getActionParam"));

// Module Files
\Route::get('telenok/module/files/browser/action-param', array('as' => 'cmf.module.files-browser.action.param', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@getActionParam"));
\Route::get('telenok/module/files/browser', array('as' => 'cmf.module.files-browser', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@getContent"));
\Route::get('telenok/module/files/browser/list', array('as' => 'cmf.module.files-browser.list', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@getList"));
\Route::get('telenok/module/files/browser/create', array('as' => 'cmf.module.files-browser.create', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@create"));
\Route::get('telenok/module/files/browser/edit', array('as' => 'cmf.module.files-browser.edit', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@edit"));
\Route::post('telenok/module/files/browser/store', array('as' => 'cmf.module.files-browser.store', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@store"));
\Route::post('telenok/module/files/browser/update', array('as' => 'cmf.module.files-browser.update', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@update"));
\Route::post('telenok/module/files/browser/delete', array('as' => 'cmf.module.files-browser.delete', 'uses' => "Telenok\Core\Module\Files\Browser\Controller@delete"));






// Module Files\Browser
//\Route::any('telenok/module/file-browser/wizard/list', array('as' => 'cmf.module.file-browser.wizard.list', 'uses' => "Telenok\Core\Module\Files\Browser\Wizard\Directory\Controller@getListContent"));
//\Route::any('telenok/module/file-browser/wizard/tree', array('as' => 'cmf.module.file-browser.wizard.tree', 'uses' => "Telenok\Core\Module\Files\Browser\Wizard\Directory\Controller@getTreeList"));
//\Route::any('telenok/module/file-browser/wizard/process', array('as' => 'cmf.module.file-browser.wizard.process', 'uses' => "Telenok\Core\Module\Files\Browser\Wizard\Directory\Controller@processTree"));



// Module System\Setting
\Route::get('telenok/module/system-setting/action-param', array('as' => 'cmf.module.system-setting.action.param', 'uses' => "Telenok\Core\Module\System\Setting\Controller@getActionParam"));
\Route::post('telenok/module/system-setting/save', array('as' => 'cmf.module.system-setting.save', 'uses' => "Telenok\Core\Module\System\Setting\Controller@save"));


// Module Web\PageConstructor
\Route::get('telenok/module/web-page-constructor/action-param', array('as' => 'cmf.module.web-page-constructor.action.param', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@getActionParam"));
\Route::get('telenok/module/web-page-constructor/list/page', array('as' => 'cmf.module.web-page-constructor.list.page', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@getListPage"));
\Route::get('telenok/module/web-page-constructor/view/page/container/{id}/language-id/{languageId}', array('as' => 'cmf.module.web-page-constructor.view.page.container', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@viewPageContainer"));
\Route::get('telenok/module/web-page-constructor/view/page/insert/language-id/{languageId}/page-id/{pageId}/widget-key/{key}/widget-id/{id}/container/{container}/bufferId/{bufferId}/order/{order}/', array('as' => 'cmf.module.web-page-constructor.view.page.insert.widget', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@insertWidget"));
\Route::get('telenok/module/web-page-constructor/view/page/remove/widget-id/{id}/', array('as' => 'cmf.module.web-page-constructor.view.page.remove.widget', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@removeWidget"));
\Route::post('telenok/module/web-page-constructor/view/page/widget/buffer/add/{id}/key/{key}', array('as' => 'cmf.module.web-page-constructor.view.buffer.add.widget', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@addBufferWidget"));
\Route::post('telenok/module/web-page-constructor/view/page/widget/buffer/delete/{id}', array('as' => 'cmf.module.web-page-constructor.view.buffer.delete.widget', 'uses' => "Telenok\Core\Module\Web\PageConstructor\Controller@deleteBufferWidget"));

// Module Web\Page
\Route::any('telenok/module/web-page/action-param', array('as' => 'cmf.module.web-page.action.param', 'uses' => "Telenok\Core\Module\Web\Page\Controller@getActionParam"));
  

// Module Workflow Process
\Route::get('telenok/module/workflow-process/action-param', array('as' => 'cmf.module.workflow-process.action.param', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@getActionParam"));
\Route::get('telenok/module/workflow-process/diagram/show', array('as' => 'cmf.module.workflow-process.diagram.show', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@diagramShow"));
\Route::get('telenok/module/workflow-process/diagram/stensilset', array('as' => 'cmf.module.workflow-process.diagram.stensilset', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@diagramStencilSet"));
\Route::any('telenok/module/workflow/store/property', array('as' => 'cmf.workflow.store-property', 'uses' => "Telenok\Core\Interfaces\Workflow\Element@storeProperty"));
\Route::any('telenok/module/workflow/apply/diagram', array('as' => 'cmf.workflow.apply-diagram', 'uses' => "Telenok\Core\Module\Workflow\Process\Controller@applyDiagram"));

\Route::get('telenok/module/workflow/element/start/point-model/property', array('as' => 'cmf.workflow.point-model.property', 'uses' => "Telenok\Core\Workflow\Point\Start\Model@getPropertyContent"));
\Route::get('telenok/module/workflow/element/start/point-form/property', array('as' => 'cmf.workflow.point-form.property', 'uses' => "Telenok\Core\Workflow\Point\Start\Form@getPropertyContent"));
\Route::get('telenok/module/workflow/element/end/point-end/property', array('as' => 'cmf.workflow.point-end.property', 'uses' => "Telenok\Core\Workflow\Point\End\End@getPropertyContent"));
\Route::get('telenok/module/workflow/element/form-field-hide/property', array('as' => 'cmf.workflow.form-field-hide.property', 'uses' => "Telenok\Core\Workflow\Activity\FormFieldHide@getPropertyContent"));
