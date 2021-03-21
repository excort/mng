<?php
namespace App\Manager;

use App\Entity\Manufacturer;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Service\MongoProvider;
use MongoDB\Collection;

class VehicleManager
{
    private Collection $collection;

    public function __construct(
        private MongoProvider $mongoProvider,
        private RegistrationProvider $registrationProvider,
    ) {
        $this->collection = $this->mongoProvider->getVehicleCollection();
    }

    public function getVehicleByUser(User $user, ?Manufacturer $manufacturer = null, int $page = 1): array
    {
        $params = [];
        if (!$user->isAdmin()) {
            $params = $this->getVehicleRegistrationByUser($user);
        }

        dump($params);die();



//        $vehicles = $this->vehicleProvider->findVehicle(
//            [/*'userId' => $user->getId()*/],
//            [
//                'sort' => ['productionDate' => -1],
//                'limit' => 100,
//            ]
//        );
    }

    private function getVehicleRegistrationByUser(User $user): array
    {
        return $this->registrationProvider->findRegistrations(
            ['owner' => $user->getId()],
            [
                'projection' => [
                    'vehicleId' => 1,
                ],
            ]
        );
    }
}
