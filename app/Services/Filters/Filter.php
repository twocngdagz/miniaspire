<?php

namespace App\Services\Filters;

use ReflectionClass;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filter
{
    protected $request;

    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getFilterMethods()
    {
        $class = new ReflectionClass(static::class);

        $methods = array_map(function ($method) use ($class) {
            if ($method->class === $class->getName()) {
                return $method->name;
            }
            return null;
        }, $class->getMethods());

        return array_filter($methods);
    }

    protected function getFilters()
    {
        return array_filter($this->request->only($this->getFilterMethods()));
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $name => $value) {
            if (method_exists($this, $name)) {
                if ($value) {
                    $this->$name($value);
                } else {
                    $this->$name();
                }
            }
        }

        return $this->builder;
    }
}
