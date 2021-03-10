<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController  extends AbstractController
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

    #[Route('/user/login', name: 'user_login', methods:["POST"])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        dump(__METHOD__, $this->getUser(), $authenticationUtils->getLastUsername());die();
    }

    #[Route('/user/list', name: 'user_list')]
    public function getUserList(): Response
    {
        dump(__METHOD__, $this->getUser());die();
    }
}
