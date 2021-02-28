<?php
namespace App\Command;

use App\Entity\Manufacturer;
use App\Entity\User;
use App\Manager\ManufacturerProvider;
use App\Manager\UserProvider;
use Faker\Factory;
use Faker\Generator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

class FixteresCommand extends Command
{
    private const USER_COUNT = 200;
    private const USER_ADMIN_COUNT = 5;

    private const MANUFACTURER_COUNT = 40;

    protected static $defaultName = 'app:fixtures-load';

    private Generator $faker;

    public function __construct(
        private ManufacturerProvider $manufacturerProvider,
        private UserProvider $userProvider,
        private LoggerInterface $logger,
    ) {
        $this->faker = Factory::create();

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Start load fixtures:');

        $this->getUserFixtures(true);

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
            $this->logger->info('Create user: {login} with pass: {pass}', [
                'login' => $login,
                'pass' => $pass,
            ]);

            $user = new User(
                $login,
                $pass,
                $this->faker->firstName . ' ' . $this->faker->lastName,
                $i<self::USER_ADMIN_COUNT
            );

            $this->userProvider->createUser($user);
        }
    }

    private function getVehicleFixtures(bool $withCleaning = true): void
    {
        $this->getManufacturerFixtures($withCleaning);

        $this->getManufacturerFixtures($withCleaning);
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
}
