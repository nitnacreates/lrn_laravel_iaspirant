<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* super registration routes
* Route::get('/super/register','\App\Http\Controllers\Auth\Super\RegisterController@showRegistrationForm')->name('super::register');
* Route::post('/super/register','\App\Http\Controllers\Auth\Super\RegisterController@register');
*/

// ////////// superadmin section //////////
Route::get('/super/login','\App\Http\Controllers\Auth\Super\LoginController@showLoginForm')->name('super::login');
Route::post('/super/login','\App\Http\Controllers\Auth\Super\LoginController@login');
Route::post('/super/logout','\App\Http\Controllers\Auth\Super\LoginController@logout')->name('super::logout');

// superadmin grouping
Route::group([
    'as' => 'super::',
    'prefix' => 'super',
    'namespace' => 'Super',
    'middleware' => 'superadmin'
    ], function () {

    \View::share ( 'namespace', 'super');

    $allowed_paths = array(
        'auth' => ['login'],
        'dashboard' => ['show'],
        'media' => ['show', 'store', 'picker'],
        'news' => ['show','stash', 'summon'],
        'prelim' => ['schedule', 'pariksha', 'pariksha-stash', 'pariksha-attempts', 'pariksha-attempts-show'],
        'question' => ['show','stash', 'summon'],
        'syllabus' => ['show', 'stash', 'picker'],
        'theory' => ['show', 'stash'],
        'theory-summaries' => ['show', 'stash'],
        'essays' => ['show', 'stash', 'summon'],
    );
    foreach($allowed_paths as $controller => $act) {
        Route::match(['get', 'post'], $controller.'/{action}', ['as' => $controller, function (Request $request, $action = 'show') use($controller) {
            $app = app();
            $laidall = array_map("ucfirst", explode('-', $controller));
            $laid_controller = implode('', $laidall);
            $controller_path = '\App\Http\Controllers\Super\\'. $laid_controller .'Controller';
            $createdController = $app->make($controller_path);

            $action = implode("_", explode('-', $action));
            return $createdController->callAction($action, array());
        }])->where(array(
            'action' => '^('. implode('|', $act) .')',
        ));
    }

    Route::any('{notfound?}', function () {
        return redirect()->to('/super/dashboard/show');
    })->where('notfound', '.*');
});

// ////////// user section //////////

Auth::routes();
Route::group([
    'as' => 'user::',
    'prefix' => 'user',
    'namespace' => 'User',
    'middleware' => 'auth'
    ], function () {

    \View::share ( 'namespace', 'user');
    
    $allowed_paths = array(
        'profile' => ['show', 'mutate-profile', 'mutate-password'],
        'subscription' => ['show'],
        'media' => ['show', 'store', 'picker', 'summon'],
        'essays' => ['show', 'stash']
    );
    foreach($allowed_paths as $controller => $act) {
        Route::match(['get', 'post'], $controller.'/{action}', ['as' => $controller, function (Request $request, $action) use($controller) {
            $app = app();
            $laidall = array_map("ucfirst", explode('-', $controller));
            $laid_controller = implode('', $laidall);
            $controller_path = '\App\Http\Controllers\User\\'. $laid_controller .'Controller';
            $createdController = $app->make($controller_path);

            $action = implode("_", explode('-', $action));
            return $createdController->callAction($action, array());
        }])->where(array(
            'action' => '^('. implode('|', $act) .')',
        ));
    }

});

// ////////// public section //////////
Route::group([
    'as' => 'front::',
    'namespace' => 'Front',
    // 'middleware' => 'web'    // web is default middleware
    ], function () {

    \View::share ( 'namespace', 'front');

    // @ paths
    $at_allowed_paths = array(
        'notes' => ['show', 'list', 'create']
    );

    foreach($at_allowed_paths as $current_controller => $act) {
        Route::match(['get', 'post'], '@{user_name}/{controller}/{action}', ['as' => $current_controller, function(Request $request, $user_name, $controller_name, $action_name) {

            $app = app();
            $laidall = array_map("ucfirst", explode('-', $controller_name));
            $laid_controller = implode('', $laidall);
            $controller_path = '\App\Http\Controllers\Front\\'. $laid_controller .'Controller';
            $createdController = $app->make($controller_path);

            $action_name = implode("_", explode('-', $action_name));
            return $createdController->callAction($action_name, array('user_name' => $user_name));

        }])->where(array(
            'controller' => $current_controller,
            'action' => '^('. implode('|', $act) .')',
        ));
    }

    // three paths
    $front_three_paths = array(
        ['news','date', 'date'],
        ['news','show', 'link'],
    );
    foreach($front_three_paths as $path) {
        Route::match(['get', 'post'], $path[0] .'/'. $path[1] .'/{param}', ['as' => $path[0] .'-'.$path[1], function(Request $request, $pass_param) use ($path) {

            $app = app();
            $laidall = array_map("ucfirst", explode('-', $path[0]));
            $laid_controller = implode('', $laidall);
            $controller_path = '\App\Http\Controllers\Front\\'. $laid_controller .'Controller';
            $createdController = $app->make($controller_path);

            $action_name = implode("_", explode('-', $path[1]));
            return $createdController->callAction($action_name, array($pass_param));
        }]);
    }

    // public paths
    $front_allowed_paths = array(
        'syllabus' => ['show', 'picker'],
        'news' => ['list', 'summon'],
        'index' => ['index'],
    );

    foreach($front_allowed_paths as $current_controller => $act) {
        Route::match(['get', 'post'], $current_controller.'/{action}', ['as' => $current_controller, function(Request $request, $action_name) use ($current_controller) {

            $app = app();
            $laidall = array_map("ucfirst", explode('-', $current_controller));
            $laid_controller = implode('', $laidall);
            $controller_path = '\App\Http\Controllers\Front\\'. $laid_controller .'Controller';
            $createdController = $app->make($controller_path);

            $action_name = implode("_", explode('-', $action_name));
            return $createdController->callAction($action_name, array());

        }])->where(array(
            'controller' => $current_controller,
            'action' => '^('. implode('|', $act) .')',
        ));
    }

});

Route::get('/', '\App\Http\Controllers\Front\IndexController@index');

// last line
Route::any('{notfound?}', function () {
    return redirect()->to('/');
})->where('notfound', '.*');
