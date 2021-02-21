<?php
namespace App\Controller;

use App\DTO\UserDTO;
use App\Manager\GroupReportMaker;
use App\Manager\UserManager;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController
{
    /**
     * VehicleController constructor.
     */
    public function __construct(
//        UserManager $userManager,
//        SerializerInterface $serializer
    ) {
//        $this->userManager = $userManager;
//        $this->serializer = $serializer;
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
