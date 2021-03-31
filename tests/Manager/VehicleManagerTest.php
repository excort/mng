<?php
namespace App\Tests\Manager;

use App\Entity\Registration;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Manager\RegistrationProvider;
use App\Manager\VehicleManager;
use App\Manager\VehicleProvider;
use App\Service\MongoProvider;
use MongoDB\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

// https://phpunit.readthedocs.io/ru/latest/writing-tests-for-phpunit.html
// https://symfony.com/doc/current/testing.html#phpunit-configuration
// https://github.com/sebastianbergmann/phpunit/issues/4502
// http://docs.mockery.io/en/latest/reference/alternative_should_receive_syntax.html
// https://stackoverflow.com/questions/21181038/phpunit-assert-a-parameter-when-pass-it-to-mock-object

class VehicleManagerTest extends TestCase
{
    /**
     * анотации работают только с версии 10
     * @dataProvider getVehicleByUserProvider
     */
    public function testGetVehicleByUserTest(
        bool $isAdmin,
        ?string $manufacturer,
        array $arRegistration,
        string $vehicleId
    ):void {
        $registrationMock = $this->createMock(Registration::class);
        $registrationMock
            ->expects($this->any())
            ->method('getVehicleId')
            ->willReturn(Uuid::fromString($arRegistration[0]['vehicleId']))
        ;

        $registrationProviderMock = $this->createMock(RegistrationProvider::class);
        $registrationProviderMock
            ->expects($this->any())
            ->method('findRegistrations')
            ->willReturn([$registrationMock])
        ;

        $vehicleMock = $this->createMock(Vehicle::class);
        $vehicleMock
            ->expects($this->any())
            ->method('getId')
            ->willReturn(Uuid::fromString($vehicleId))
        ;
        $vehicleMock
            ->expects($this->any())
            ->method('addRegistration')
        ;

        $vehicleProviderMock = $this->createMock(VehicleProvider::class);
        $vehicleProviderMock
            ->expects($this->once())
            ->method('findVehicle')
            ->willReturn([$vehicleMock])
        ;

        $vehicleManager = new VehicleManager(
            $registrationProviderMock,
            $vehicleProviderMock
        );

        $userMock = $this->createMock(User::class);
        $userMock
            ->expects($this->any())
            ->method('isAdmin')
            ->willReturn($isAdmin);
        ;

        $result = $vehicleManager->getVehicleByUser($userMock, null, 1, 5);

        $this->assertEquals($result[0]->getId(), $vehicleId);
    }

    public function getVehicleByUserProvider(): array
    {
        return [
            [
                false,
                null,
                [
                    [
                        "_id" => "2308751a-6635-4f11-afc6-5a16ccf775e7",
                        "vehicleId" => "2b9cabf9-5126-4fa3-897b-ec636198434a",
                    ],
                    [
                        "_id" => "2308751a-6635-4f11-afc6-5a16ccf775e7",
                        "vehicleId" => "2b9cabf9-5126-4fa3-897b-ec636198434b",
                    ]
                ],
                "2b9cabf9-5126-4fa3-897b-ec636198434d",
            ],
            [
                true,
                null,
                [
                    [
                        "_id" => "2308751a-6635-4f11-afc6-5a16ccf775e7",
                        "vehicleId" => "2b9cabf9-5126-4fa3-897b-ec636198434a",
                    ],
                    [
                        "_id" => "2308751a-6635-4f11-afc6-5a16ccf775e7",
                        "vehicleId" => "2b9cabf9-5126-4fa3-897b-ec636198434b",
                    ]
                ],
                "2b9cabf9-5126-4fa3-897b-ec636198434d",
            ],
            [
                true,
                "2b9cabf9-5126-4fa3-897b-ec636198434c",
                [
                    [
                        "_id" => "2308751a-6635-4f11-afc6-5a16ccf775e7",
                        "vehicleId" => "2b9cabf9-5126-4fa3-897b-ec636198434a",
                    ],
                    [
                        "_id" => "2308751a-6635-4f11-afc6-5a16ccf775e7",
                        "vehicleId" => "2b9cabf9-5126-4fa3-897b-ec636198434b",
                    ]
                ],
                "2b9cabf9-5126-4fa3-897b-ec636198434d",
            ],
        ];
    }
}
