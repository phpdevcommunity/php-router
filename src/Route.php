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
            throw new InvalidArgumentException('HTTP methods argument was empty; must contain at least one method');
        }
        $this->name = $name;
        $this->path = Helper::trimPath($path);
        $this->handler = $handler;
        $this->methods = $methods;
    }

    public function match(string $path): bool
    {
        $regex = $this->getPath();
        foreach ($this->getVarsNames() as $variable) {
            $varName = trim($variable, '{\}');
            $regex = str_replace($variable, '(?P<' . $varName . '>[^/]++)', $regex);
        }

        if (preg_match('#^' . $regex . '$#sD', Helper::trimPath($path), $matches)) {
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
}
