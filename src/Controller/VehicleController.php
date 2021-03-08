<?php
namespace App\Controller;

use App\Manager\ManufacturerProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    public function __construct(
        private ManufacturerProvider $manufacturerProvider
    ) {
//        $this->mongoProvider = $mongoProvider;
    }

    #[Route('/vehicle/list', name: 'vehicle_list', methods:["GET"])]
    public function getVehicleList(): Response
    {
        dump(__METHOD__);die();

    }

    #[Route('/vehicle/{id}', name: 'vehicle_by_id', methods:["GET"])]
    public function getVehicleById(int $id): Response
    {
        dump(__METHOD__);die();
    }
}
