<?php

namespace Av;

use AVException as Exception;
use Klein;


/**
 * With this router you can listen to all request and the initialize the controller
 *
 * @package Av
 * @author  Denzyl<denzyl@live.nl>
 */
class Router
{
    /**
     * @var Klein\Klein $klein
     */
    private $klein;

    public function __construct()
    {
        $this->klein = Container::get('klein');
    }

    /**
     * Listen to  all requests
     * GET,POST,DELETE,HEAD, ETC
     */
    public function listen()
    {
        $this->klein->respond("/?/[:controller]?/?/[:action]?/?/[*:params]?", function (Klein\Request $request) {
            $this->initializeController(
                $request->param('controller', "index"),
                $request->param('action', 'index')
            );
        });
        $this->klein->dispatch();
    }

    /**
     * Initialize the controller with the corresponding action
     *
     * @param string $controllerParam
     * @param string $actionParam
     * @return int|Klein\AbstractResponse
     */
    private function initializeController($controllerParam = 'index', $actionParam = 'index')
    {
        $action = (strlen($actionParam) == 0 ? 'index' : $actionParam);

        $class = ucfirst($controllerParam);
        $controller = "Av\Controller\{$class";
        $action = "{$action}Action";
        if (class_exists($controller)) {

            /** @var Controller $instance */
            $instance = new $controller();

            $action = method_exists($instance, $action) ? $action : 'indexAction';
            method_exists($instance, 'initialize') ? $instance->initialize() : null;
            $instance->setController(get_class($instance));
            $instance->setAction($action);
            $instance->$action();
        } else {
            return $this->klein->response()->code(404);
        }
    }
}
