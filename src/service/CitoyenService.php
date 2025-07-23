<?php

namespace App\src\service;

use App\src\repository\CitoyenRepository;
use App\src\entity\Citoyen;

class CitoyenService implements IserviceCitoyen
{private $citoyenRepository;

    public function __construct(CitoyenRepository $citoyenRepository)
    {
        $this->citoyenRepository = $citoyenRepository;
    }

    public function findByNci(string $nci): ?Citoyen
    {
        return $this->citoyenRepository->findByNci($nci);
    }
    public function findAll(): array
    {
        return $this->citoyenRepository->findAll();
    }
}
