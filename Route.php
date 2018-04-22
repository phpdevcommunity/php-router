<?php

namespace Fady\Routing;

/**
 * Class Route
 * @package Fady\Routing
 */
class Route
{
    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $routeName;

    /**
     * @var array
     */
    protected $varsNames = [];

    /**
     * @var array
     */
    protected $vars = [];

    /**
     * Route constructor.
     * @param $url
     * @param $controller
     * @param $action
     * @param array $varsNames
     */
    public function __construct($url, $controller, $action, array $varsNames = [])
    {
        $this->setUrl($url)
            ->setController($controller)
            ->setAction($action)
            ->setVarsNames($varsNames);
    }

    /**
     * @return bool
     */
    public function hasVars()
    {
        return !empty($this->varsNames);
    }

    /**
     * @param $url
     * @return null|
     */
    public function match($url)
    {
        if (preg_match('`^' . $this->url . '$`', $url, $matches)) {
            return $matches;
        }
        return null;
    }

    /**
     * @param string $action
     * @return Route
     */
    public function setAction(string $action)
    {
        if (is_string($action)) {
            $this->action = $action;
        }
        return $this;
    }

    /**
     * @param string $controller
     * @return Route
     */
    public function setController(string $controller)
    {
        if (is_string($controller)) {
            $this->controller = $controller;
        }
        return $this;
    }

    /**
     * @param string $url
     * @return Route
     */
    public function setUrl(string $url)
    {
        if (is_string($url)) {
            $this->url = $url;
        }
        return $this;
    }

    /**
     * @param array $varsNames
     * @return Route
     */
    public function setVarsNames(array $varsNames)
    {
        $this->varsNames = $varsNames;
        return $this;
    }

    /**
     * @param array $vars
     * @return Route
     */
    public function setVars(array $vars)
    {
        $this->vars = $vars;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @return array
     */
    public function getVarsNames()
    {
        return $this->varsNames;
    }

    /**
     * @return mixed
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * @param string $routeName
     * @return Route
     */
    public function setRouteName(string $routeName)
    {
        $this->routeName = $routeName;
        return $this;
    }


}
