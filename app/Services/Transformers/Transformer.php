<?php

namespace App\Services\Transformers;

use App\Services\Paginate\Paginate;
use Illuminate\Support\Collection;

abstract class Transformer
{
    protected $resourceName = 'data';

    public function collection(Collection $data)
    {
        return [
            str_plural($this->resourceName) => $data->map([$this, 'transform'])
        ];
    }

    public function item($data)
    {
        return [
            $this->resourceName => $this->transform($data)
        ];
    }

    public function paginate(Paginate $paginated)
    {
        $resourceName = str_plural($this->resourceName);

        $countName = str_plural($this->resourceName) . 'Count';

        $data = [
            $resourceName => $paginated->getData()->map([$this, 'transform'])
        ];

        return array_merge($data, [
            $countName => $paginated->getTotal()
        ]);
    }

    abstract public function transform($data);
}
