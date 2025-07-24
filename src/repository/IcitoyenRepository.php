<?php

namespace App\src\repository;
use App\src\entity\Citoyen;

interface IcitoyenRepository{
     public function findByNci(string $nci): ?Citoyen;


    public function findAll(): array;

}