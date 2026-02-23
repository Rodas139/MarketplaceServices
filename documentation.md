- __Adminlte__ _v(2.3.7)_ 
    - https://adminlte.io/themes/AdminLTE/documentation/index.html
    - public\vendor\admin-lte

- Session alerts _success_, _error_, _warning_, _info_ at `resources\views\admin\partials\alerts.blade.php`


# File Repository
- The polymorphic relationship _target_ sets what entity is the file related. 
- The polymorphic relationship _source_ sets what entity uploaded the file.

In the routes file, it's possible to limit the _target_ parameter [Laravel docs](https://laravel.com/docs/11.x/routing#parameters-regular-expression-constraints) with the _whereIn_ function
```php
Route::post('files/{target}/{id}/upload', [Admin\FileRepositoryController::class, 'upload'])
    ->name('admin.files.upload')
    ->whereIn('target', [FileRepository::PROJECT_SOURCE_CLASS, FileRepository::QUOTE_SOURCE_CLASS]);
```


# Ajax Form and Refresh Datable 
###### [Source Code](public\assets\admin\js\main.js#L636)
On the form add the data atribute ```data-toggle=ajax-form``` and ```data-refresh-datatables=*datatable id*```. If _data-refresh-datatables_ is not provided, it will refresh the var _oTable_, so you could save the data in this var.
Example of formOptions in the controller 
```php
$formOptions = ['route'=> ['admin.calendar.store'], 'method' => 'POST','data-toggle'=>"ajax-form", "data-refresh-datatables"=>'#datatable-events-modal'];
```

