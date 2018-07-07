<?php

namespace Webby\Routing;

/**
 * Class Route
 * @package Webbym\Routing
 */
class Route
{
    /**
     * @var string
     */
    protected $name;

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
    protected $path;

    /**
     * @var array
     */
    protected $varsNames = [];

    /**
     * @var array
     */
    protected $vars = [];

    /**
     * @var array
     */
    protected $requirements = [];


    /**
     * Route constructor.
     * @param string $path
     * @param string $controller
     * @param string $action
     * @param array $requirements
     * @param array $varsNames
     */
    public function __construct(string $name, string $path, string $controller, string $action, array $requirements = [])
    {
        $this->setPath($path)
            ->setController($controller)
            ->setName($name)
            ->setAction($action)
            ->setRequirements($requirements);
    }


    /**
     * @return bool
     */
    public function hasVars()
    {
        if (empty($this->varsNames)) {

            preg_match_all('/{[^}]*}/', $this->path, $matches);
            $this->setVarsNames(reset($matches));
        }
        return !empty($this->getVarsNames());
    }

    /**
     * @param string $path
     * @return null|bool
     */
    public function match(string $path)
    {
        $path = $this->trimPath($path);
        if (preg_match('#^'.$this->generateRegex().'$#sD', $path, $matches)) {

            return array_filter($matches, function ($key) {
                return is_string($key);
            }, ARRAY_FILTER_USE_KEY);
        }

        return null;
    }


    /**
     * @return string
     */
    private function generateRegex()
    {

        $regex = $this->path;
        if ($this->hasVars()) {

            foreach ($this->getVarsNames() as $variable) {
                $varName = trim($variable,'{\}');
                $regex = str_replace($variable, '(?P<'.$varName.'>[^/]++)', $regex);
            }
        }

        return $regex;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Route
     */
    public function setName(string $name): Route
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @param string $action
     * @return Route
     */
    public function setAction(string $action)
    {

        $this->action = $action;
        return $this;
    }

    /**
     * @param string $controller
     * @return Route
     */
    public function setController(string $controller)
    {

        $this->controller = $controller;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }


    /**
     * @param string $path
     * @return Route
     */
    public function setPath(string $path)
    {
        $this->path = $this->trimPath($path);
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
     * @param string $varName
     * @return $this
     */
    public function addVarName(string $varName)
    {
        $this->varsNames[] = $varName;
        return $this;
    }

    /**
     * @param array $vars
     * @return Route
     */
    public function setVars(array $vars = [])
    {
        $this->vars = $vars;
        return $this;
    }

    /**
     * @param array $vars
     * @return Route
     */
    public function addVar(string $value)
    {
        $this->vars[] = $value;
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
     * @return array
     */
    public function getRequirements(): array
    {
        return $this->requirements;
    }

    /**
     * @param array $requirements
     * @return Route
     */
    public function setRequirements(array $requirements = []): Route
    {
        $this->requirements = $requirements;
        return $this;
    }


    /**
     * @param string $path
     * @return string
     */
    private function trimPath(string $path) :string
    {

        return '/'.rtrim(ltrim(trim($path), '/'), '/');
    }
}
