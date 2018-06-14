<?php

namespace Av;

use Av\Exception\UnknownParameter;
use Klein\DataCollection\DataCollection;
use Klein\Exceptions\UnhandledException;
use Pimple\Container as Pimple;

/**
 *Controller base for every controller
 *
 * @author Denzyl<denzyl@live.nl>
 */
abstract class Controller
{
    private $controller;
    private $action;

    /**
     * @var \Klein\Response $response
     */
    protected $response;
    /**
     * @var \Klein\Request $request
     */
    protected $request;
    /**
     * @var Container $di
     */
    protected $di;

    /**
     * @var  ViewManager $view
     */
    protected $view;

    /**
     * Get controller name
     *
     * @return string
     */
    public function getControllerName(): String
    {
        return str_replace("Av\\\\", '', $this->controller);
    }

    /**
     * Get action name
     *
     * @return string
     */
    public function getActionName(): String
    {
        return $this->action;
    }

    /**
     * Get parameter for a get request
     *
     * @param $key
     * @return mixed|string
     * @throws UnknownParameter
     */
    public function get(string $key)
    {
        try {

            if (!empty($this->request->param($key))) {
                return $this->request->param($key);
            } else {
                $raw_params = $this->request->params();
                $params = array_filter(array_map('strtoupper', explode("/", $raw_params[0])));

                unset($params[0]);
                unset($params['controller']);
                unset($params['action']);


                $clean_params = array();

                foreach (array_values($params) as $p) {
                    $clean_params[$p] = null;
                    if (isset($params[key($clean_params) + 1])) {


                        foreach ($params as $v) {
                            if ($v === $p) {
                                $clean_params[$p] = $v;
                            }
                        }
                    }


                }
                $dataCollection = new DataCollection($clean_params);
                return $dataCollection->get($key);

            }
        } catch (UnhandledException  $e) {
            throw new UnknownParameter($e);
        }

    }

    public function post()
    {
        return $this->request->paramsPost();
    }


    /**
     * @param $controller
     */
    public function setController($controller)
    {
        $this->controller = str_replace('Controller', '', $controller);
    }

    /**
     * @param $action
     */
    public function setAction($action)
    {
        $this->action = str_replace('Action', '', $action);
    }

    /**
     * Get cookie
     *
     * @return DataCollection
     */

    public function cookies()
    {
        return $this->request->cookies();
    }

    /**
     * Forward to another class
     * @param $resource
     * @throws \Exception
     */
    public function forward(array $resource)
    {

        $controller = $this->getResource($resource)[0];

        $action = $this->getResource($resource)[1];
        $parameters = $this->getResource($resource)[2];
        /** @var Controller $instance */
        $instance = new $controller();
        if (method_exists($instance, "initialize")) {
            $instance->initialize();
        }

        $instance->setController($controller);
        $instance->setAction($action);
        $instance->$action($parameters);
    }

    private function getResource($resource): array
    {
        $controller = null;
        $action = null;
        $params = array();
        if (!isset($resource['action'])) {
            $action = 'IndexAction';
        } else if (isset($resource['action']) && strlen($resource["action"]) > 0) {
            $action = $resource["action"];
        }

        if (isset($resource["controller"]) && strlen($resource["controller"]) > 0) {
            $controller = "Av\Controller\\" . $resource["controller"];

        } else {
            throw new \Exception('Can\'t initiate an empty controller');
        }
        /**
         * unset the unused variables so we can't send the rest of the params;
         */
        unset($params["controller"]);
        unset($params["action"]);

        return [$controller, $action, $params];
    }
}
