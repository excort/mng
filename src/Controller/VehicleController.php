<?php
namespace App\Controller;

use App\Entity\User;
use App\Manager\ManufacturerProvider;
use App\Manager\VehicleManager;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    private Serializer $serializer;

    public function __construct(
        private VehicleManager $vehicleManager,
        private ManufacturerProvider $manufacturerProvider,
    ) {
        $this->serializer = SerializerBuilder::create()->build();
    }

    #[Route('/vehicle/list/{page}', name: 'vehicle_list', methods:["GET"], defaults: ['page' => 1])]
    public function getVehicleList(int $page, Request $request): JsonResponse
    {
        $manufacturer = $request->get('manufacturer', null);
        if ($manufacturer) {
            $manufacturer = $this->manufacturerProvider->getManufacturer(['_id' => $manufacturer]);
        }

        /** @var User $user */
        $user = $this->getUser();

        $vehicles = $this->vehicleManager->getVehicleByUser($user, $manufacturer, $page);

        /**
         * можно добавить кол-во записей всего имеется и сколько страниц доступно с учетом расположение
         * элементов на странице.
         */
        $data = $this->serializer->serialize($vehicles, 'json');
        $response = new JsonResponse();

        return $response->setJson($data);
    }
}
