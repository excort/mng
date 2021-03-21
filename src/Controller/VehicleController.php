<?php
namespace App\Controller;

use App\Manager\ManufacturerProvider;
use App\Manager\VehicleManager;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    public function __construct(
        private VehicleManager $vehicleManager,
        private ManufacturerProvider $manufacturerProvider,
    ) { }

    #[Route('/vehicle/list/{page}', name: 'vehicle_list', methods:["GET"], defaults: ['page' => 1])]
    public function getVehicleList(int $page, Request $request): Response
    {
        $manufacturer = $request->get('manufacturer', null);
        if ($manufacturer) {
            $manufacturer = $this->manufacturerProvider->getManufacturer(['_id' => $manufacturer]);
        }

        /** @var User $user */
        $user = $this->getUser();

        $vehicles = $this->vehicleManager->getVehicleByUser($user, $manufacturer, $page);

        return new JsonResponse(['data' => $vehicles]);
    }
}
