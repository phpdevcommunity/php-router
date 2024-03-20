<?php

declare(strict_types=1);

namespace DevCoder;

use DevCoder\Traits\RouteTrait;
use InvalidArgumentException;
use function array_filter;
use function is_string;
use function preg_match;
use function preg_match_all;
use function reset;
use function str_replace;
use function trim;

/**
 * Class Route
 * @package DevCoder
 */
final class Route
{
    use RouteTrait;

    private string $name;
    private string $path;

    /**
     * @var mixed
     */
    private $handler;

    /**
     * @var array<string>
     */
    private array $methods = [];

    /**
     * @var array<string>
     */
    private array $attributes = [];

    /**
     * @var array<string, string>
     */
    private array $wheres = [];

    /**
     * Constructor for the Route class.
     *
     * @param string $name The name of the route.
     * @param string $path The path of the route.
     * @param mixed $handler The handler for the route.
     *    $handler = [
     *      0 => (string) Controller name : HomeController::class.
     *      1 => (string|null) Method name or null if invoke method
     *    ]
     * @param array $methods The HTTP methods for the route. Default is ['GET', 'HEAD'].
     *
     * @throws InvalidArgumentException If the HTTP methods argument is empty.
     */
    public function __construct(string $name, string $path, $handler, array $methods = ['GET', 'HEAD'])
    {
        if ($methods === []) {
            throw new InvalidArgumentException('HTTP methods argument was empty; must contain at least one method');
        }
        $this->name = $name;
        $this->path = Helper::trimPath($path);
        $this->handler = $handler;
        $this->methods = $methods;

        if (in_array('GET', $this->methods) && !in_array('HEAD', $this->methods)) {
            $this->methods[] = 'HEAD';
        }
    }

    /**
     * Matches a given path against the route's path and extracts attribute values.
     *
     * @param string $path The path to match against.
     * @return bool True if the path matches the route's path, false otherwise.
     */
    public function match(string $path): bool
    {
        $regex = $this->getPath();
        foreach ($this->getVarsNames() as $variable) {
            $varName = trim($variable, '{\}');
            $regex = str_replace($variable, '(?P<' . $varName . '>[^/]++)', $regex);
        }

        if (!preg_match('#^' . $regex . '$#sD', Helper::trimPath($path), $matches)) {
            return false;
        }

        $values = array_filter($matches, static function ($key) {
            return is_string($key);
        }, ARRAY_FILTER_USE_KEY);

        foreach ($values as $key => $value) {
            if (array_key_exists($key, $this->wheres) && !preg_match('/^'.$this->wheres[$key].'$/', $value)) {
                return false;
            }
            $this->attributes[$key] = $value;
        }

        return true;
    }

    /**
     * Returns the name of the Route.
     *
     * @return string The name of the Route.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the path of the Route.
     *
     * @return string The path of the Route.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Returns the HTTP methods for the Route.
     *
     * @return array The HTTP methods for the Route.
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getVarsNames(): array
    {
        preg_match_all('/{[^}]*}/', $this->path, $matches);
        return reset($matches) ?? [];
    }

    public function hasAttributes(): bool
    {
        return $this->getVarsNames() !== [];
    }

    /**
     * @return array<string>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Sets a number constraint on the specified route parameters.
     *
     * @param mixed ...$parameters The route parameters to apply the constraint to.
     * @return self The updated Route instance.
     */
    public function whereNumber(...$parameters): self
    {
        $this->assignExprToParameters($parameters, '[0-9]+');
        return $this;
    }

    /**
     * Sets a slug constraint on the specified route parameters.
     *
     * @param mixed ...$parameters The route parameters to apply the constraint to.
     * @return self The updated Route instance.
     */
    public function whereSlug(...$parameters): self
    {
        $this->assignExprToParameters($parameters, '[a-z0-9-]+');
        return $this;
    }

    /**
     * Sets an alphanumeric constraint on the specified route parameters.
     *
     * @param mixed ...$parameters The route parameters to apply the constraint to.
     * @return self The updated Route instance.
     */
    public function whereAlphaNumeric(...$parameters): self
    {
        $this->assignExprToParameters($parameters, '[a-zA-Z0-9]+');
        return $this;
    }

    /**
     * Sets an alphabetic constraint on the specified route parameters.
     *
     * @param mixed ...$parameters The route parameters to apply the constraint to.
     * @return self The updated Route instance.
     */
    public function whereAlpha(...$parameters): self
    {
        $this->assignExprToParameters($parameters, '[a-zA-Z]+');
        return $this;
    }

    /**
     * Sets a custom constraint on the specified route parameter.
     *
     * @param string $parameter The route parameter to apply the constraint to.
     * @param string $expression The regular expression constraint.
     * @return self The updated Route instance.
     */
    public function where(string $parameter, string $expression): self
    {
        $this->wheres[$parameter] = $expression;
        return $this;
    }

    private function assignExprToParameters(array $parameters, string $expression): void
    {
        foreach ($parameters as $parameter) {
            $this->where($parameter, $expression);
        }
    }
}
