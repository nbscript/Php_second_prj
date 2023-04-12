<?php

namespace App;


use App\controllers\auth\AuthController;
use App\controllers\home\HomeController;
use App\controllers\pages\PageController;
use App\controllers\roles\RoleController;
use App\controllers\users\UsersController;



class Router {

//    private $routes = [
//        '/^\/' . APP_BASE_PATH . '\/?$/' => ['controller' => 'home\\HomeController', 'action' => 'index'],
//        '/^\/' . APP_BASE_PATH . '\/users(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'users\\UsersController'],
//        '/^\/' . APP_BASE_PATH . '\/auth(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'auth\\AuthController'],
//        '/^\/' . APP_BASE_PATH . '\/roles(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'roles\\RoleController'],
//        '/^\/' . APP_BASE_PATH . '\/pages(\/(?P<action>[a-z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'pages\\PageController'],
//    ];
//
//    public function run() {
//        $uri = $_SERVER['REQUEST_URI'];
//        $controller = null;
//        $action = null;
//        $params = null;
//
//        foreach ($this->routes as $pattern => $route) {
//            if (preg_match($pattern, $uri, $matches)) {
//                $controller = "App\\controllers\\" . $route['controller'];
//                $action = $route['action'] ?? $matches['action'] ?? 'index';
//                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
//                break;
//            }
//        }
//
//        if (!$controller) {
//            http_response_code(404);
//            var_dump($controller);
//            echo "Page not found!";
//            return;
//        }
//
//        $controllerInstance = new $controller();
//        //var_dump($controllerInstance);
//        print_r($controllerInstance);
//        if (!method_exists($controllerInstance, $action)) {
//            http_response_code(404);
//            echo "Action not found!";
//            return;
//        }
//        call_user_func_array([$controllerInstance, $action], [$params]);
//    }



    public function run(): void
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';

        switch ($page) {
            case '':
            case 'home':
                $controller = new HomeController();
                $controller->index();
                break;
            case 'users':
                $controller = new UsersController();
                if(isset($_GET['action'])) {
                    switch ($_GET['action']) {
                        case 'create':
                            $controller->create();
                            break;
                        case 'store':
                            $controller->store();
                            break;
                        case 'delete':
                            $controller->delete();
                            break;
                        case 'edit':
                            $controller->edit();
                            break;
                        case 'update':
                            $controller->update();
                            break;
                        default:
                            break;
                    }
                }
                else {
                    $controller->index();
                }
                break;
            case 'roles':
                $controller = new RoleController();
                if(isset($_GET['action'])) {
                    switch ($_GET['action']) {
                        case 'create':
                            $controller->create();
                            break;
                        case 'store':
                            $controller->store();
                            break;
                        case 'edit':
                            $controller->edit($_GET['id']);
                            break;
                        case 'update':
                            $controller->update();
                            break;
                        case 'delete':
                            $controller->delete();
                            break;
                        default:
                            break;
                    }
                }
                else {
                    $controller->index();
                }
                break;

            case 'pages':
                $controller = new PageController();
                if(isset($_GET['action'])) {
                    switch ($_GET['action']) {
                        case 'create':
                            $controller->create();
                            break;
                        case 'store':
                            $controller->store();
                            break;
                        case 'edit':
                            $controller->edit($_GET['id']);
                            break;
                        case 'update':
                            $controller->update();
                            break;
                        case 'delete':
                            $controller->delete();
                            break;
                        default:
                            break;
                    }
                }
                else {
                    $controller->index();
                }
                break;

            case 'register':
                $controller = new AuthController();
                $controller->register();
                break;
            case 'login':
                $controller = new AuthController();
                $controller->login();
                break;
            case 'authenticate':
                $controller = new AuthController();
                $controller->authenticate();
                break;
            case 'logout':
                $controller = new AuthController();
                $controller->logout();
                break;
            case 'auth':
                $controller = new AuthController();
                if(isset($_GET['action'])) {
                    switch ($_GET['action']) {
                        case 'store':
                            $controller->store();
                            break;
                    case 'authenticate':
                        $controller->authenticate();
                        break;
                        default:
                            break;
                    }
                }
                else {
                    $controller->login();
                }
                break;
            default :
                http_response_code(404);
                echo 'Page not found';
                break;
        }
    }
}