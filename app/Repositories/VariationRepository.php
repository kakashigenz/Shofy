<?php

namespace App\Repositories;

use App\Models\Variation;
use App\Repositories\IVariationRepository;

class VariationRepository implements IVariationRepository
{
    protected $model;
    public function __construct(Variation $model)
    {
        $this->model = $model;
    }
    public function create($data)
    {
        return $this->model::create($data);
    }
    public function update($data, $id)
    {
        return $this->model::find($id)->update($data);
    }
    public function destroy($id)
    {
        return $this->model::find($id)->delete();
    }

    public function where(string $field, string $value, string $op = '=')
    {
        return $this->model::with('option')->where($field,$op,$value);
    }
}
