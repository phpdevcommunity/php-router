<?php

declare(strict_types=1);

namespace DevCoder;

use DevCoder\Traits\RouteTrait;

/**
 * Class Route
 * @package DevCoder
 */
final class Route
{
    use RouteTrait;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * @var mixed
     */
    private $handler;

    /**
     * @var array<string>
     */
    private $methods = [];

    /**
     * @var array<string>
     */
    private $attributes = [];

    /**
     * Route constructor.
     * @param string $name
     * @param string $path
     * @param mixed $handler
     *    $handler = [
     *      0 => (string) Controller name : HomeController::class.
     *      1 => (string|null) Method name or null if invoke method
     *    ]
     * @param array $methods
     */
    public function __construct(string $name, string $path, $handler, array $methods = ['GET'])
    {
        if ($methods === []) {
            throw new \InvalidArgumentException('HTTP methods argument was empty; must contain at least one method');
        }
        $this->name = $name;
        $this->path = $path;
        $this->handler = $handler;
        $this->methods = $methods;
    }

    public function match(string $path, string $method): bool
    {
        $regex = $this->getPath();
        foreach ($this->getVarsNames() as $variable) {
            $varName = trim($variable, '{\}');
            $regex = str_replace($variable, '(?P<' . $varName . '>[^/]++)', $regex);
        }

        if (in_array($method, $this->getMethods()) && preg_match('#^' . $regex . '$#sD', Helper::trimPath($path), $matches)) {
            $values = array_filter($matches, static function ($key) {
                return is_string($key);
            }, ARRAY_FILTER_USE_KEY);
            foreach ($values as $key => $value) {
                $this->attributes[$key] = $value;
            }
            return true;
        }
        return false;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @deprecated use getHandler()
     */
    public function getParameters()
    {
        return $this->getHandler();
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getVarsNames(): array
    {
        preg_match_all('/{[^}]*}/', $this->path, $matches);
        return reset($matches) ?? [];
    }

    /**
     * @deprecated use hasAttributes()
     */
    public function hasVars(): bool
    {
        return $this->hasAttributes();
    }

    public function hasAttributes(): bool
    {
        return $this->getVarsNames() !== [];
    }

    /**
     * @deprecated use getAttributes()
     */
    public function getVars(): array
    {
        return $this->getAttributes();
    }

    /**
     * @return array<string>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
