<?php

return [

    'list.btn.create' => 'Create',
    'list.btn.refresh' => 'Refresh',
    'list.btn.select' => 'Selected',
    'list.save.success' => 'Widget updated',
    'list.btn.choose' => 'Choose',
    'list.btn.delete.all' => 'Delete all',

    'btn.save' => 'Save',
    'btn.save.close' => 'Save and close',
    'btn.close' => 'Close',
    'btn.rename' => 'Rename',
    'btn.create' => 'Create',
    'btn.delete' => 'Delete',
    'btn.edit' => 'Edit',
    'btn.search' => 'Search',
    'btn.clear' => 'Clear',
    'btn.repair' => 'Restore',
    'btn.choose' => 'Choose',
    'btn.prev' => 'Back',
    'btn.next' => 'Next',
    'btn.filter' => 'Filter',
    'btn.create.folder' => 'Create folder',

    'notice.title' => 'Notice',
    'notice.saved' => 'Saved!',
    'notice.saved.description' => 'Data saved successfully',
    'notice.saved.thank.you' => 'Thank you!',
    'notice.error' => 'Error!',
    'notice.error.undefined' => 'Sorry, an error occurred',
    'notice.sure' => 'Are you sure ?',
    'notice.warning' => 'Warning!',
    'notice.choose' => "Select an Option",
    'notice.not-found' => "Oops, nothing found!",

    'tree.root' => 'Tree',

    'home' => 'Main',
    'action' => 'Action',

    'field' => ':attribute [:locale]',
    'field.rule' => 'Rules for field',

    'entity.title' => 'Title',
    'current' => 'Current',
    'addition' => 'Addition',

	
    'table.empty' => 'Отсутствуют доступные данные для таблицы',
    'table.empty.showed' => 'Отсутствуют данные для показа',
    'table.empty.filtered' => 'Отсутствуют данные под условиями поиска',
    'table.search' => 'Search',
    'table.showed' => 'Показаны записи с _START_ до _END_',
    'table.filter.header' => 'Search filter',
    'table.filter.btn' => 'Search',

    'tooltip.description' => "Field description",
    

    'property.default' => "Default value",
    'property.required' => "Required",

    'property.title.title' => 'Title',
    'property.bgcolor.title' => 'Background color',

    
    'error.table.nonexists' => 'Table ":table" doesnt extst in database',
    'error.field.create' => 'Cant create field ":key" in table ":table"',
    'error.file.update' => 'Error update file :file',
    'error.widget.link.nonexistent' => 'Original linked widget nonexistent or deleted',
	'error.tab.field.key' => 'Please, set "Id" or "Code" for tab of field in "field_object_tab" key',
	'error.file.upload.require' => 'Please, upload required file in field ":attribute"',
	
    'error' => [
        'undefined' => 'Sorry, an error occurred',
        'required' => 'Field ":attribute" required',
        'numeric' => 'In the field ":attribute" allowed only numbers',
        'between' => 'Field ":attribute" should be from :min to :max',
        'integer' => 'Field ":attribute" should contain integer',
        'alpha' => 'Field ":attribute" should contain latin symbols',
        'alpha_num' => 'Field ":attribute" should contain integer and latin symbols',
        'unique' => 'Record with value of field ":attribute" already exists in table ":table". Field ":attribute" should be unique in table ":table"',
        'min' => 'Too low value in field ":attribute". String should contain not less than :min symbol(s) or be digits from :min',
        'max' => 'Слишком большое значение в поле ":attribute". Строка должна содержать не более :max символа(ов) или быть числом не более :max',
        'regex' => 'Значение в поле ":attribute" не соответствует регулярному выражению',
    ],

    'migration' => [
        'id' => [
            'ru' => "№",
            'en' => "№",
        ],
        'title' => [
            'ru' => "Заголовок",
            'en' => "Title",
        ],
        'title_list' => [
            'ru' => "Заголовок списка",
            'en' => "Title of list",
        ],
    ]


];