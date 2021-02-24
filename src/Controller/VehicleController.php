<?php
namespace App\Controller;

use App\Entity\Manufacturer;
use App\Manager\ManufacturerManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class VehicleController
{
    public function __construct(
        private ManufacturerManager $manufacturerManager
    ) {
//        $this->mongoProvider = $mongoProvider;
    }

    #[Route('/vehicle/list', name: 'vehicle_list', methods:["GET"])]
    public function getVehicleList(): Response
    {
        $manufacturer = new Manufacturer(Uuid::v4(),'NAME','SITE');
        $this->manufacturerManager->createManufacturer($manufacturer);

    }

    #[Route('/vehicle/{id}', name: 'vehicle_by_id', methods:["GET"])]
    public function getVehicleById(int $id): Response
    {
        dump(__METHOD__);die();
    }
}
