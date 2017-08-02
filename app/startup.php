<?
 require_once DIR_CORE.'model.php';
 require_once DIR_CORE.'view.php';
 require_once DIR_CORE.'controller.php';
 require_once DIR_LIBRARY.'route.php';
 Route::getInstance();
 Route::start(); // маршрутизатор
?>