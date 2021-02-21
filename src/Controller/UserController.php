<?php
namespace App\Controller;

use App\DTO\UserDTO;
use App\Manager\GroupReportMaker;
use App\Manager\UserManager;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    /**
     * UserController constructor.
     */
    public function __construct(
//        UserManager $userManager,
//        SerializerInterface $serializer
    ) {
//        $this->userManager = $userManager;
//        $this->serializer = $serializer;
    }

    #[Route('/user/list', name: 'user_list')]
    public function getUserList(): Response
    {
        dump(__METHOD__);die();
    }
}
