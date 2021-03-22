<?php
namespace App\Manager;

use App\Entity\Manufacturer;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Service\MongoProvider;
use MongoDB\Collection;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Serializer;

class VehicleManager
{
    private Collection $collection;

    public function __construct(
        private MongoProvider $mongoProvider,
        private RegistrationProvider $registrationProvider,
        private VehicleProvider $vehicleProvider,
    ) {
        $this->collection = $this->mongoProvider->getVehicleCollection();
    }

    public function getVehicleByUser(
        User $user,
        ?Manufacturer $manufacturer = null,
        int $page = 1,
        int $perPage = 5
    ): array {
        $params = $this->getParams($user, $manufacturer);
        $options = $this->getOptions($page, $perPage);

        $vehicles = $this->vehicleProvider->findVehicle($params, $options);

        foreach ($vehicles as $vehicle) {
            $this->addRegistrationToVehicle($vehicle, $user);
        }

        return $vehicles;
    }

    private function addRegistrationToVehicle(Vehicle $vehicle, User $user)
    {
        $registrationList = $this->getVehicleRegistrationList($vehicle, $user->isAdmin() ? $user:null);

        foreach ($registrationList as $registration) {
            $vehicle->addRegistration($registration);
        }
    }

    private function getVehicleRegistrationList(Vehicle $vehicle, ?User $user = null): array
    {
        $params = ['vehicleId' => (string) $vehicle->getId()];

        if ($user) {
            $params['owner'] = $user->getId();
        }

        return $this->registrationProvider->findRegistrations(
            $params,
            []
        );
    }

    private function getVehicleIdByRegistrationForUser(User $user): array
    {
        $arRegistration = $this->registrationProvider->findRegistrations(
            ['owner' => $user->getId()],
            [
                'projection' => [
                    'vehicleId' => 1,
                ],
            ]
        );

        $result = [];
        foreach ($arRegistration as $registration) {
            $result[] = $registration["vehicleId"];
        }

        return $result;
    }

    private function getOptions(int $page, int $perPage): array
    {
        /**
         * Сортировка по _id добавлена для того что бы при одинаковой значении productionDate
         * у сущности vehicle, результат выдачи был одинаковым
         */
        $options = [
            'sort' => ['productionDate' => -1, '_id' => 1],
            'limit' => $perPage,
        ];

        if ($page>1) {
            $options['skip'] = $perPage * ($page-1);
        }

        return $options;
    }

    private function getParams(User $user, ?Manufacturer $manufacturer): array
    {
        $params = [];

        if (!$user->isAdmin()) {
            // https://docs.mongodb.com/manual/core/map-reduce/#MapReduce-Outputoptions
            $registration = $this->getVehicleIdByRegistrationForUser($user);

            if ($registration) {
                $params = ['_id' => ['$in' => $registration]];
            }

            return $params;
        }

        if ($manufacturer) {
            $params = ['manufacturer' => $manufacturer->getId()];
        }

        return $params;
    }
}
