<?php
namespace App\Controller;

use App\Entity\Auth;
use App\Manager\AuthProvider;
use App\Manager\UserProvider;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController  extends AbstractController
{
    public function __construct(
        private JWTEncoderInterface $JWTEncoder,
        private AuthProvider $authProvider,
        private UserProvider $userProvider
    ) {
    }

    /**
     * @throws JWTEncodeFailureException
     */
    #[Route('/user/login', name: 'user_login', methods:["POST"])]
    public function login(Request $request): Response
    {
        $token = $this->JWTEncoder->encode(['username' => $this->getUser()->getUsername()]);

        $auth = new Auth($this->getUser(), $token);
        $this->authProvider->createAuth($auth);

        return new JsonResponse(['token' => $token]);
    }
}
