<?php
namespace App\src\controller;

interface IcontrollerCitoyen{
    public function findByNci(string $nci): void;

    public function findAll(): void;

}
