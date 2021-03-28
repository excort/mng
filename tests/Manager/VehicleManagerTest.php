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

class VehicleManagerTest extends TestCase
{
    private RegistrationProvider $registrationProviderMock;
    private VehicleProvider $vehicleProviderMock;

    private VehicleManager $vehicleManager;

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

        $registrationProviderMock = $this->createMock(RegistrationProvider::class);
//        $registrationProviderMock
//            ->expects($this->any())
//            ->method('findRegistrations')
//            ->willReturn([$registrationMock])
//        ;
        $registrationProviderMock
            ->shouldReceive('geocode')
            ->andReturn($response)

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
//        $vehicleMock
//            ->expects($this->any())
//            ->method('addRegistration')
//            ->with($registrationProviderMock)
//            ->willReturn($vehicleMock)
//        ;

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

        dump($result);die();
        $ttt = 42;
        // assert that your calculator added the numbers correctly!
        $this->assertEquals(42, $ttt);
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
//            [
//                true,
//                null,
//                [
//                    [
//                        "_id" => "2308751a-6635-4f11-afc6-5a16ccf775e7",
//                        "vehicleId" => "2b9cabf9-5126-4fa3-897b-ec636198434a",
//                    ],
//                    [
//                        "_id" => "2308751a-6635-4f11-afc6-5a16ccf775e7",
//                        "vehicleId" => "2b9cabf9-5126-4fa3-897b-ec636198434b",
//                    ]
//                ],
//                "2b9cabf9-5126-4fa3-897b-ec636198434d",
//            ],
//            [
//                true,
//                "2b9cabf9-5126-4fa3-897b-ec636198434c",
//                [
//                    [
//                        "_id" => "2308751a-6635-4f11-afc6-5a16ccf775e7",
//                        "vehicleId" => "2b9cabf9-5126-4fa3-897b-ec636198434a",
//                    ],
//                    [
//                        "_id" => "2308751a-6635-4f11-afc6-5a16ccf775e7",
//                        "vehicleId" => "2b9cabf9-5126-4fa3-897b-ec636198434b",
//                    ]
//                ],
//                "2b9cabf9-5126-4fa3-897b-ec636198434d",
//            ],
        ];
    }
}
