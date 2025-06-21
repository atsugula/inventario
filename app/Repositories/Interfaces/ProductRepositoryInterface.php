<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface
{
    public function allPaginated();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}