<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function findById(int $id): Category;
    public function create(array $data): Category;
    public function update(int $id, array $data): Category;
    public function delete(int $id): bool;
}
