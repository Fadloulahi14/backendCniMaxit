<?php
namespace App\src\service;
use App\src\entity\Citoyen;
interface IserviceCitoyen{
    public function findByNci(string $nci): ?Citoyen;
    public function findAll(): array;

}