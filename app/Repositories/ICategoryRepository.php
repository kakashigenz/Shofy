<?php

namespace App\Repositories;

interface ICategoryRepository
{
    public function all($type, $search);
    public function find($id);
    public function create($data);
    public function update($data, $id);
    public function destroy($id);
    function getList($type, $search, $start, $length);
}
