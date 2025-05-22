<?php

namespace App\Service;

use App\Entity\Collaborator;
use App\Repository\CollaboratorRepository;

class AuthService
{
    private $collaboratorRepository;

    public function __construct(CollaboratorRepository $collaboratorRepository)
    {
        $this->collaboratorRepository = $collaboratorRepository;
    }

    public function createNewCollaborator(Collaborator $collaborator)
    {
        $this->collaboratorRepository->save($collaborator);
    }

    public function loginUser(int $email, int $password)
    {
        $collaborator = $this->collaboratorRepository->findByEmail($email);

        if ($collaborator->getPassword() != $password) {
            return $collaborator;
        }
    }
}
