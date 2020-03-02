<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//$route['test'] = 'test/prueba1';

$route['api/message/(:any)'] = 'test/message/$1';

$route['api/login'] = 'test/login';

$route['api/log_error']['get'] = 'chrigarc/Backend_error/index';
$route['api/log_error/(:num)']['get'] = 'chrigarc/Backend_error/show/$1';

$route['api/log']['get'] = 'chrigarc/Log/index';
$route['api/log/(:num)']['get'] = 'chrigarc/Log/show/$1';

$route['api/job']['get'] = 'chrigarc/Queue_job/index';
$route['api/job/(:num)']['get'] = 'chrigarc/Queue_job/show/$1';

$route['api/user']['get'] = 'chrigarc/User/index';
$route['api/user/(:any)']['get'] = 'chrigarc/User/show/$1';
$route['api/user']['post'] = 'chrigarc/User/store';
$route['api/user/(:any)']['post'] = 'chrigarc/User/update/$1';
$route['api/user/(:any)']['delete'] = 'chrigarc/User/destroy/$1';

$route['api/role']['get'] = 'chrigarc/Role/index';
$route['api/role/(:any)']['get'] = 'chrigarc/Role/show/$1';
$route['api/role']['post'] = 'chrigarc/Role/store';
$route['api/role/(:any)']['post'] = 'chrigarc/Role/update/$1';
$route['api/role/(:any)']['delete'] = 'chrigarc/Role/destroy/$1';

$route['api/module']['get'] = 'chrigarc/Module/index';
$route['api/module/(:any)']['get'] = 'chrigarc/Module/show/$1';
$route['api/module']['post'] = 'chrigarc/Module/store';
$route['api/module/(:any)']['post'] = 'chrigarc/Module/update/$1';
$route['api/module/(:any)']['delete'] = 'chrigarc/Module/destroy/$1';

//$route['([a-z]+)/([a-z]+)'] = 'welcome';

