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
Auth::routes();
Route::get('/super/register','\App\Http\Controllers\Auth\Super\RegisterController@showRegistrationForm')->name('super::register');
Route::post('/super/register','\App\Http\Controllers\Auth\Super\RegisterController@register');
Route::get('/super/login','\App\Http\Controllers\Auth\Super\LoginController@showLoginForm')->name('super::login');
Route::post('/super/login','\App\Http\Controllers\Auth\Super\LoginController@login');
Route::post('/super/logout','\App\Http\Controllers\Auth\Super\LoginController@logout')->name('super::logout');

// frontend
Route::get('/', '\App\Http\Controllers\Front\IndexController@index');

// superadmin
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
        'news' => ['show','stash'],
        'syllabus' => ['show', 'stash', 'picker'],
        'theory' => ['show', 'stash'],
        'theory-summaries' => ['show', 'stash'],
        'essays' => ['show', 'stash'],
    );
    foreach($allowed_paths as $controller => $act) {
        Route::match(['get', 'post'], $controller.'/{action}', ['as' => $controller, function (Request $request, $action = 'show') use($controller) {
            $app = app();
            $laidall = array_map("ucfirst", explode('-', $controller));
            $laid_controller = implode('', $laidall);
            $controller_path = '\App\Http\Controllers\Super\\'. $laid_controller .'Controller';
            $createdController = $app->make($controller_path);

            return $createdController->callAction($action, array());
        }])->where(array(
            'action' => '^('. implode('|', $act) .')',
        ));
    }

    Route::any('{notfound?}', function () {
        return redirect()->to('/super/dashboard/show');
    })->where('notfound', '.*');
});



Route::group([
    'as' => 'front::',
    'namespace' => 'Front',
    'middleware' => 'web'
    ], function () {

    \View::share ( 'namespace', 'front');

    $allowed_paths = array(
        'home' => ['show'],
        'theory' => ['show'],
        'essay' => ['show', 'roster'],
        'question' => ['show', 'roster', 'rear'],
        'news' => ['show', 'today']
    );


    foreach($allowed_paths as $controller => $act) {
        Route::match(['get', 'post'], $controller.'/{action}', ['as' => $controller, function (Request $request, $action = 'show') use($controller) {
            $app = app();
            $controller_path = '\App\Http\Controllers\Front\\'. ucfirst($controller) .'Controller';
            $createdController = $app->make($controller_path);

            return $createdController->callAction($action, array());
        }])->where(array(
            'action' => '^('. implode('|', $act) .')',
        ));
    }

    Route::any('{notfound?}', function () {
        return redirect()->to('/');
    })->where('notfound', '.*');
});
