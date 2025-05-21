<?php

namespace App\Service;

use App\Entity\Collaborator;
use App\Repository\CollaboratorRepository;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use InvalidArgumentException;

class CollaboratorService
{
    private $collaboratorRepository;

    public function __construct(CollaboratorRepository $collaboratorRepository)
    {
        $this->collaboratorRepository = $collaboratorRepository;
    }

    public function getAll(): array
    {
        try {
            return $this->collaboratorRepository->findAll();
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des collaborateurs : " . $e->getMessage());
        }
    }

    public function getById(int $id): Collaborator
    {
        $collaborator = $this->collaboratorRepository->findById($id);
        if (!$collaborator) {
            throw new EntityNotFoundException("Collaborateur avec l'ID $id non trouvé.");
        }
        return $collaborator;
    }

    public function getByEmail(string $email): Collaborator
    {
        $collaborator = $this->collaboratorRepository->findByEmail($email);
        if (!$collaborator) {
            throw new EntityNotFoundException("Collaborateur avec l'email $email non trouvé.");
        }
        return $collaborator;
    }

    public function getRandom(): Collaborator
    {
        $collaborator = $this->collaboratorRepository->findRandom();
        if (!$collaborator) {
            throw new EntityNotFoundException("Aucun collaborateur trouvé pour un tirage aléatoire.");
        }
        return $collaborator;
    }

    public function filterColaboratorByCategory(string $category): array
    {
        try {
            return $this->collaboratorRepository->findByFiltersCategory($category);
        } catch (Exception $e) {
            throw new Exception("Erreur lors du filtrage par catégorie : " . $e->getMessage());
        }
    }

    public function filterColaboratorByName(string $name): array
    {
        try {
            return $this->collaboratorRepository->findByFiltersName($name);
        } catch (Exception $e) {
            throw new Exception("Erreur lors du filtrage par nom : " . $e->getMessage());
        }
    }

    public function filterColaboratorByText(array $text): array
    {
        try {
            return $this->collaboratorRepository->findByFiltersText($text);
        } catch (Exception $e) {
            throw new Exception("Erreur lors du filtrage par texte : " . $e->getMessage());
        }
    }

    public function createCollaborator(object $data)
    {
        try {
            if (!$data instanceof Collaborator) {
                throw new InvalidArgumentException("L'objet fourni n'est pas une instance de Collaborator.");
            }
            return $this->collaboratorRepository->save($data);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la création du collaborateur : " . $e->getMessage());
        }
    }

    public function updateCollaborator(int $id, object $data)
    {
        try {
            if (!$data instanceof Collaborator) {
                throw new InvalidArgumentException("L'objet fourni n'est pas une instance de Collaborator.");
            }
            $collaborator = $this->collaboratorRepository->findById($id);
            if (!$collaborator) {
                throw new EntityNotFoundException("Collaborateur avec l'ID $id non trouvé pour la mise à jour.");
            }
            return $this->collaboratorRepository->update($id, $data);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la mise à jour du collaborateur : " . $e->getMessage());
        }
    }

    public function deleteCollaborator(int $id)
    {
        try {
            $collaborator = $this->collaboratorRepository->findById($id);
            if (!$collaborator) {
                throw new EntityNotFoundException("Collaborateur avec l'ID $id non trouvé pour la suppression.");
            }
            return $this->collaboratorRepository->delete($id);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la suppression du collaborateur : " . $e->getMessage());
        }
    }
}
