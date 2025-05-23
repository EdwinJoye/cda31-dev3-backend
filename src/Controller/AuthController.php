<?php

namespace App\Controller;

use App\Entity\Collaborator;
use App\Service\AuthService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request): JsonResponse
    { 
         $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Données manquantes pour la création.'], 400);
        }
        try {
            $collaborator = new Collaborator();
            // À adapter selon les propriétés de l'entité
            $collaborator->setFirstname($data['firstname'] ?? '');
            $collaborator->setLastname($data['lastname'] ?? '');
            $collaborator->setGender($data['gender'] ?? '');
            $collaborator->setEmail($data['email'] ?? '');
            $collaborator->setPassword($data['password'] ?? '');
            $collaborator->setPhone($data['phone'] ?? null);
            $collaborator->setBirthdate(isset($data['birthdate']) ? new \DateTime($data['birthdate']) : null);
            $collaborator->setCity($data['city'] ?? null);
            $collaborator->setCountry($data['country'] ?? null);
            $collaborator->setPhoto($data['photo'] ?? null);
            $collaborator->setCategory($data['category'] ?? null);
            $collaborator->setIsAdmin($data['isAdmin'] ?? false);
    
            $this->authService->createNewCollaborator($collaborator );
            return $this->json($collaborator );
        } catch (InvalidArgumentException $e){
            return $this->json(['error' => $e->getMessage()], 400);
        }catch (\Exception $e){
            return $this->json(['error' => $e->getMessage()], 500);
        }

    }
    #[Route('/me', name: 'app_me')]
    public function me(Request $request)
    {

        $user = $this->authService->getMe();
        return $this->json($user ); 
    }
    
    #[Route('/login', name: 'app_login')]
    public function login(Request $request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = $this->authService->loginUser($email, $password);
        return $this->json($user ); 
    }
}

