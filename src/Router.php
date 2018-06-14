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
    /**
     * @var Container
     */
    private $di;

    public function __construct()
    {
        $this->klein = Container::get('klein');

    }

    /**
     * Listen to  all requests
     * GET,POST,DELETE,HEAD
     */
    public function listen()
    {
        ////?/[:controller]?/?/[:action]?/?/[*:params]?
        $this->klein->respond("/?/[:controller]?/?/[:action]?/?/[*:params]?", function ($request) {
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
    private function initializeController($controllerParam = "index", $actionParam = "index")
    {

        $action = (strlen($actionParam) == 0 ? "index" : $actionParam);
        try {
            $concept_controller = "Av\Controller\\" . ucfirst($controllerParam);
            $concept_action = "{$action}Action";

            if (class_exists($concept_controller)) {

                $instance = new $concept_controller();


                $action = method_exists($instance, $concept_action) ? $concept_action : "indexAction";
                method_exists($instance, 'initialize') ? $instance->initialize() : "";
                $instance->setController(get_class($instance));
                $instance->setAction($action);
                $instance->$action();
            } else {
                return $this->klein->response()->code(404);
            }

        } catch (Exception $e) {

            echo $e->getMessage();
        }
    }


}
