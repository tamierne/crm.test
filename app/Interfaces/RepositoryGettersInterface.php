<?php

namespace App\Interfaces;

interface RepositoryGettersInterface
{
    public function getAllItems();
    public function getAllItemsWithPaginate();
}
