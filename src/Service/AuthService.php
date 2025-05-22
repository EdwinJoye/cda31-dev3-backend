<?php

namespace App\Service;

use App\Entity\Collaborator;
use App\Repository\CollaboratorRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthService
{
    private $collaboratorRepository;
    private $tokenStorage;

    public function __construct(CollaboratorRepository $collaboratorRepository, TokenStorageInterface $tokenStorage)
    {
        $this->collaboratorRepository = $collaboratorRepository;
        $this->tokenStorage = $tokenStorage;
    }

    public function createNewCollaborator(Collaborator $collaborator)
    {
        $this->collaboratorRepository->save($collaborator);
    }

    public function loginUser()
    {
        $token = $this->tokenStorage->getToken();
        if ($token && $token->getUser() instanceof UserInterface) {
            $email = $token->getUser()->getUserIdentifier();
            return $this->collaboratorRepository->findByEmail($email);
        }
        return null;
    }
}
