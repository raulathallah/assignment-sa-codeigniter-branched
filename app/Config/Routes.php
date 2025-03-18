<?php

use App\Controllers\Academic;
use App\Controllers\Enrollment;
use App\Controllers\Home;
use App\Controllers\Mahasiswa;
use App\Controllers\Users;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [Home::class, 'index']);

$routes->get('email', [Home::class, 'sendEmail']);
$routes->post('upload/upload', [Home::class, 'upload']);

$routes->get('academic-statistic', [Academic::class, 'getAcademicStatistic']);

$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('register', 'Auth::register', ['as' => 'register']);
    $routes->post('register', 'Auth::attemptRegister');

    $routes->get('login', 'Auth::login', ['as' => 'login']);
    $routes->post('login', 'Auth::attemptLogin');
});

$routes->group('', ['filter' => 'role:student'], function ($routes) {
    $routes->get('my-profile', [Mahasiswa::class, 'detailProfile']);
});

$routes->group('enrollments', ['filter' => 'role:student'], function ($routes) {
    $routes->get('/', 'Enrollment::index');
    $routes->get('create', 'Enrollment::create');
    $routes->post('store', 'Enrollment::store');
});

$routes->group('admin/enrollments', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('/', 'Enrollment::index');
    $routes->get('create', 'Enrollment::create');
    $routes->post('store', 'Enrollment::store');
    $routes->get('delete/(:num)', 'Enrollment::delete/$1');
});

$routes->group('admin/users', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('create', 'Users::create');
    $routes->post('store', 'Users::store');
    $routes->get('edit/(:num)', 'Users::edit/$1');
    $routes->put('update/(:num)', 'Users::update/$1');
    $routes->delete('delete/(:num)', 'Users::delete/$1');
    $routes->get('addToGroup/(:num)', 'Users::addToGroup/$1');
    $routes->put('addToGroupSave/(:num)', 'Users::addToGroupSave/$1');
});

$routes->group('student', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('/', [Mahasiswa::class, 'index']);
    $routes->get('detail/(:any)', [Mahasiswa::class, 'detail']);
    $routes->get('create', [Mahasiswa::class, 'create']);
    $routes->get('edit/(:num)', [Mahasiswa::class, 'update']);
    $routes->post('save_add', [Mahasiswa::class, 'save_add']);
    $routes->post('save_update', [Mahasiswa::class, 'save_update']);
    $routes->get('delete/(:any)', [Mahasiswa::class, 'delete']);
});

$routes->group('course', ['filter' => 'role:lecturer'], function ($routes) {
    $routes->get('/', [Academic::class, 'index']);
    $routes->get('detail/(:any)', [Academic::class, 'getCourseDetail']);
    $routes->get('create', [Academic::class, 'createCourse']);
    $routes->get('edit/(:num)', [Academic::class, 'updateCourse']);
    $routes->post('save_add', [Academic::class, 'course_save_add']);
    $routes->post('save_update', [Academic::class, 'course_save_update']);
    $routes->get('delete/(:any)', [Academic::class, 'deleteCourse']);
});

$routes->group('dashboard', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('student', [Home::class, 'dashboardStudent']);
    $routes->get('admin', [Home::class, 'dashboardAdmin']);
    $routes->get('lecturer', [Home::class, 'dashboardLecturer']);
});
