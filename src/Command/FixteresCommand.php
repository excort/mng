<?php
namespace App\Command;

use App\Entity\Manufacturer;
use App\Entity\Registration;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Manager\ManufacturerProvider;
use App\Manager\RegistrationProvider;
use App\Manager\UserProvider;
use App\Manager\VehicleProvider;
use Faker\Factory;
use Faker\Generator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Uid\Uuid;

class FixteresCommand extends Command
{
    private const USER_COUNT = 200;
    private const USER_ADMIN_COUNT = 5;

    private const MANUFACTURER_COUNT = 40;

    private const VEHICLE_COUNT = 10000;

    protected static $defaultName = 'app:fixtures-load';

    private Generator $faker;

    public function __construct(
        private ManufacturerProvider $manufacturerProvider,
        private UserProvider $userProvider,
        private VehicleProvider $vehicleProvider,
        private RegistrationProvider $registrationProvider,
        private LoggerInterface $logger,
        private UserPasswordEncoderInterface $userPasswordEncoder
    ) {
        $this->faker = Factory::create();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Start load fixtures:');

        $this->getUserFixtures(true);

        $this->getManufacturerFixtures(true);

        $this->getVehicleFixtures(true);

        $output->writeln('Finish load fixtures');

        return Command::SUCCESS;
    }

    private function getUserFixtures(bool $withCleaning = true): void
    {
        if ($withCleaning) {
            $this->userProvider->clearUsers();
        }

        for ($i = 0; $i< self::USER_COUNT; $i++) {
            $login = $this->faker->unique()->userName;
            $pass = $this->faker->password;

            //** dev env for user access */
            $this->logger->info('Create user: ', [
                'login' => $login,
                'pass' => $pass,
            ]);

            $user = new User();
            $user
                ->setLogin($login)
                ->setPass($this->userPasswordEncoder->encodePassword($user,$pass))
                ->setFullName($this->faker->firstName . ' ' . $this->faker->lastName)
                ->setAdmin($i<self::USER_ADMIN_COUNT)
            ;

            $this->userProvider->createUser($user);
        }
    }

    private function getManufacturerFixtures(bool $withCleaning = true)
    {
        if ($withCleaning) {
            $this->manufacturerProvider->clearManufacturer();
        }

        for ($i = 0; $i< self::MANUFACTURER_COUNT; $i++) {
            $manufacturer = new Manufacturer(
                Uuid::v4(),
                $this->faker->company,
                $this->faker->domainName,
            );

            $this->manufacturerProvider->createManufacturer($manufacturer);
        }
    }

    private function getVehicleFixtures(bool $withCleaning = true): void
    {
        if ($withCleaning) {
            $this->vehicleProvider->clearVehicle();
            $this->registrationProvider->clearRegistration();
        }

        $manufacturers = $this->manufacturerProvider->findManufacturers();
        if (!$manufacturers) {
            return;
        }

        for ($i = 0; $i< self::VEHICLE_COUNT; $i++) {
            $manufacturer = $manufacturers[random_int(0, count($manufacturers) - 1)];

            $vehicle = new Vehicle(
                $this->faker->colorName . ' ' . $this->faker->country,
                $this->faker->city,
                $this->faker->dateTime,
                strtoupper($this->faker->lexify('?????????????????')),
                $manufacturer
            );

            $this->vehicleProvider->createVehicle($vehicle);

            $this->getRegistrationFixtures($vehicle, random_int(1,10));
        }
    }

    private function getRegistrationFixtures(Vehicle $vehicle, int $count)
    {
        /**
         * https://ru.stackoverflow.com/questions/424282/%D0%9A%D0%B0%D0%BA-%D0%BE%D0%BF%D1%80%D0%B5%D0%B4%D0%B5%D0%BB%D0%B8%D1%82%D1%8C-%D1%87%D0%B5%D1%82%D0%BD%D0%BE%D0%B5-%D1%87%D0%B8%D1%81%D0%BB%D0%BE-%D0%B8%D0%BB%D0%B8-%D0%BD%D0%B5%D1%82-%D0%BF%D1%80%D0%B8-%D0%BF%D0%BE%D0%BC%D0%BE%D1%89%D0%B8-php
         * является ли число четным
         */
        $user = null;
        if (($count & 1)) {
            $users = $this->userProvider->findUsers();
            /** @var User $user */
            $user = $users[random_int(0,count($users)-1)];
        }

        for ($i = 0; $i< $count; $i++) {

            $registration = new Registration(
                Uuid::v4(),
                $user,
                $user ? $user->getFullName() : $this->faker->firstName . ' ' . $this->faker->lastName,
                strtoupper($this->faker->lexify('??') . $this->faker->numerify('####') . $this->faker->lexify('??')),
                $this->faker->dateTime,
            );
            $registration->setVehicleId($vehicle->getId());

            $this->registrationProvider->createRegistration($registration);
        }
    }

}
