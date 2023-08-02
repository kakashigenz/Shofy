<?php

namespace App\Repositories;

interface IVariationRepository
{
    function create($data);
    function update($data, $id);
    function destroy($id);
}
