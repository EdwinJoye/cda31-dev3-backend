<?php

namespace App\Service;

use App\Entity\Collaborator;
use App\Repository\CollaboratorRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthService
{
    private $collaboratorRepository;
    private $tokenStorage;
    private $userPasswordHasher;

    public function __construct(CollaboratorRepository $collaboratorRepository, 
    TokenStorageInterface $tokenStorage,
    UserPasswordHasherInterface $userPasswordHasher
    )
    {
        $this->collaboratorRepository = $collaboratorRepository;
        $this->tokenStorage = $tokenStorage;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function createNewCollaborator(Collaborator $collaborator)
    {
        $this->collaboratorRepository->save($collaborator);
    }

    public function loginUser(string $email, string $password)
    {
        $collaborator = $this->collaboratorRepository->findByEmail($email);
        if ($collaborator) {
            // Vérifie le mot de passe hashé
            if ($this->userPasswordHasher->isPasswordValid($collaborator, $password)) {
                return $collaborator;
            }
        }
        return null;
    }
}
