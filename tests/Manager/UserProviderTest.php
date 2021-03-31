<?php
namespace App\Tests\Manager;

use App\Entity\User;
use App\Manager\UserProvider;
use App\Service\MongoProvider;
use MongoDB\Collection;
use MongoDB\InsertOneResult;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class UserProviderTest extends TestCase
{
    private MockObject|MongoProvider $mongoProviderMock;

    private UserProvider $userProvider;

    public function setUp(): void
    {
        $this->mongoProviderMock = $this->createMock(MongoProvider::class);
    }

    public function testCreateUser():void {
        $insertOneResultMock = $this->createMock(InsertOneResult::class);
        $insertOneResultMock
            ->expects($this->any())
            ->method('getInsertedId')
            ->willReturn(Uuid::v4());
        ;

        $collectionMock = $this->createMock(Collection::class);
        $collectionMock
            ->expects($this->any())
            ->method('insertOne')
            ->willReturn($insertOneResultMock);
        ;

        $this->mongoProviderMock
            ->expects($this->any())
            ->method('getUserCollection')
            ->willReturn($collectionMock);
        ;

        $this->userProvider = new UserProvider(
            $this->mongoProviderMock
        );

        $userMock = $this->createMock(User::class);
        $userMock
            ->expects($this->any())
            ->method('bsonSerialize')
            ->willReturn(['test']);
        ;

        $result = $this->userProvider->createUser($userMock);

        $this->assertInstanceOf(Uuid::class, $result);
    }
}
