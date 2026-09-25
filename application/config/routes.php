<?php
/**
 * Routing Configuration
 */

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// System routes
$route['system/db_test'] = 'system/db_test';
$route['system/(:any)'] = 'system/$1';

// Auth routes
$route['auth/login'] = 'auth/login';
$route['auth/do_login'] = 'auth/do_login';
$route['auth/logout'] = 'auth/logout';

// API docs
$route['api/docs'] = 'api_docs/index';

// FCM / notification API routes
$route['api/fcm/debug']                = 'notifications_api/fcm_debug';
$route['api/auth/login']               = 'notifications_api/login';
$route['api/fcm/register']             = 'notifications_api/register';
$route['api/fcm/unregister']           = 'notifications_api/unregister';
$route['api/notifications/(:any)']     = 'notifications_api/inbox/$1';
$route['api/notifications']            = 'notifications_api/inbox';
$route['notifications']                = 'notifications/index';
$route['notifications/compose']        = 'notifications/index';
$route['notifications/send']                = 'notifications/send';
$route['notifications/quiz_rank']           = 'notifications/quiz_rank';
$route['notifications/quiz_rank_preview']   = 'notifications/quiz_rank_preview';
$route['notifications/quiz_rank_send']      = 'notifications/quiz_rank_send';
$route['notifications/user_notify']         = 'notifications/user_notify';
$route['notifications/user_notify_preview'] = 'notifications/user_notify_preview';
$route['notifications/user_notify_send']    = 'notifications/user_notify_send';

// Legacy dashboard routes now point to home landing
$route['dashboard'] = 'home/index';
$route['dashboard/(:any)'] = 'home/index';

// User routes
$route['users'] = 'users/index';
$route['users/(:any)'] = 'users/$1';
$route['users/(:any)/(:any)'] = 'users/$1/$2';

// Welcome page (old)
$route['welcome'] = 'welcome/index';

// Home landing
$route['home'] = 'home/index';

// Branding routes
$route['branding'] = 'branding/upload';
$route['branding/upload'] = 'branding/upload';
$route['branding/update_logo'] = 'branding/upload';
$route['branding/view'] = 'branding/view';
$route['branding/theme'] = 'branding/theme';

// Question MathML editing
$route['questions/edit_mathml/(:num)'] = 'questions/edit_mathml/$1';

// Questions listing with category filter
$route['questions'] = 'questions/index';

// Inline update of quiz question HTML
$route['questions/update_quiz_html'] = 'questions/update_quiz_html';

// Create/Edit full quiz question
$route['questions/create'] = 'questions/create';
$route['questions/edit_quiz/(:num)'] = 'questions/edit_quiz/$1';

// Student API routes
$route['api/student/home']                 = 'student_api/home';
$route['api/student/packages']             = 'student_api/packages';
$route['api/student/package/(:num)']       = 'student_api/package/$1';
$route['api/student/quizzes/(:num)']       = 'student_api/quizzes/$1';
$route['api/student/results']              = 'student_api/results';
$route['api/student/result/(:num)']        = 'student_api/result/$1';
$route['api/student/profile']              = 'student_api/profile';

// Teacher API routes
$route['api/teacher/packages']             = 'student_api/teacher_packages';
$route['api/teacher/students/(:num)']      = 'student_api/teacher_students/$1';
