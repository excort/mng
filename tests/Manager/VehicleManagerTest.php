<?php
namespace App\Tests\Manager;

use App\Entity\User;
use App\Manager\RegistrationProvider;
use App\Manager\VehicleManager;
use App\Manager\VehicleProvider;
use App\Service\MongoProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class VehicleManagerTest extends TestCase
{
    private MongoProvider $registrationProviderMock;
    private RegistrationProvider $vehicleProviderMock;
    private VehicleProvider $mongoProviderMock;

    private VehicleManager $vehicleManager;

    public function setUp(): void
    {
        $this->mongoProviderMock = $this->createMock(MongoProvider::class);
        $this->registrationProviderMock = $this->createMock(RegistrationProvider::class);
        $this->vehicleProviderMock = $this->createMock(VehicleProvider::class);

        $this->vehicleManager = new VehicleManager(
            $this->mongoProviderMock,
            $this->registrationProviderMock,
            $this->vehicleProviderMock
        );
    }

    /**
     * @dataProvider addWithCarCategoryProvider
     */
    //#[Assert\Uuid]
    public function testGetVehicleByUserTest(bool $isAdmin)
    {
        $userMock = $this->createMock(User::class);
        $userMock
            ->expects($this->any())
            ->method('isAdmin')
            ->willReturn($isAdmin);
        ;
// https://phpunit.readthedocs.io/ru/latest/writing-tests-for-phpunit.html
// https://symfony.com/doc/current/testing.html#phpunit-configuration

        $this->vehicleManager->getVehicleByUser($userMock, null, 1, 5);

        $ttt = 5;
        // assert that your calculator added the numbers correctly!
        $this->assertEquals(42, $ttt);
    }
}
