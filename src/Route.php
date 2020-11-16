<?php

namespace DevCoder;

/**
 * Class Route
 * @package DevCoder
 */
class Route
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
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
    protected $methods = ['GET', 'POST'];

    /**
     * Route constructor.
     * @param string $name
     * @param string $path
     * @param array $controller
     * @param array $methods
     */
    public function __construct(string $name, string $path, array $controller, array $methods = [])
    {
        $this->name = $name;
        $this->path = $path;
        $this->controller = $controller;
        $this->methods = $methods;
    }

    /**
     * @return bool
     */
    public function hasVars(): bool
    {
        return !empty($this->getVarsNames());
    }

    /**
     * @param string $path
     * @return array|null
     */
    public function match(string $path): ?array
    {
        if (preg_match('#^'.$this->generateRegex().'$#sD', $this->trimPath($path), $matches)) {
            return array_filter($matches, function ($key) {
                return is_string($key);
            }, ARRAY_FILTER_USE_KEY);
        }

        return null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    public function addVar(string $key, string $value): self
    {
        $this->vars[$key] = $value;
        return $this;
    }

    public function getController(): array
    {
        return $this->controller;
    }

    public function getVars(): array
    {
        return $this->vars;
    }

    public function getVarsNames(): array
    {
        preg_match_all('/{[^}]*}/', $this->path, $matches);
        return reset($matches);
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    private function trimPath(string $path) :string
    {
        return '/'.rtrim(ltrim(trim($path), '/'), '/');
    }

    /**
     * @return string
     */
    private function generateRegex(): string
    {
        $regex = $this->path;
        foreach ($this->getVarsNames() as $variable) {
            $varName = trim($variable,'{\}');
            $regex = str_replace($variable, '(?P<'.$varName.'>[^/]++)', $regex);
        }
        return $regex;
    }
}
