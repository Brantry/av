<?php

namespace Av\Library;


use Dotenv\Dotenv;

class Application
{
    private $router;

    public function __construct()
    {
        $this->bootstrap();
        $this->router = new Router();
    }

    /**
     * Enable PHP error reporting
     * @param bool $html
     * @return Application
     */
    public function enableErrorReporting(bool $html = false): self
    {
        ini_set('display_errors', 1);
        ini_set('error_reporting', 1);
        ini_set('html_errors', $html);
        return $this;
    }

    /**
     * Start listening to request
     */
    public function run()
    {
        $this->router->listen();
    }

    private function bootstrap(): Application
    {
        Container::set("klein", new \Klein\Klein());
        return $this;
    }
}