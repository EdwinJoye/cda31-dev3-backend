<?php

namespace App\Controller;

use App\Entity\Collaborator;
use App\Service\CollaboratorService;
use Doctrine\ORM\EntityNotFoundException;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class CollaboratorController extends AbstractController
{
    private CollaboratorService $collaboratorService;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(CollaboratorService $collaboratorService, UserPasswordHasherInterface $passwordHasher)
    {
        $this->collaboratorService = $collaboratorService;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/all/collaborators', name: 'app_all_collaborator', methods: ['GET'])]
    public function allCollaborator(): JsonResponse
    {
        try {
            $allCollaborators = $this->collaboratorService->getAll();
            return $this->json(['allCollaborators' => $allCollaborators]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/collaborator/id', name: 'app_collaborator_id', methods: ['POST'])]
    public function collaboratorById(Request $request): JsonResponse
    {
        $id = $request->request->get('id');
        if (!$id) {
            return $this->json(['error' => 'ID manquant dans la requête.'], 400);
        }
        try {
            $collaborator = $this->collaboratorService->getById($id);
            return $this->json(['collaborator' => $collaborator]);
        } catch (EntityNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Route('/collaborator/email', name: 'app_collaborator_email', methods: ['POST'])]
    public function collaboratorByEmail(Request $request): JsonResponse
    {
        $email = $request->request->get('email');
        if (!$email) {
            return $this->json(['error' => 'Email manquant dans la requête.'], 400);
        }
        try {
            $collaborator = $this->collaboratorService->getByEmail($email);
            return $this->json(['collaborator' => $collaborator]);
        } catch (EntityNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Route('/collaborator/random', name: 'app_collaborator_random', methods: ['GET'])]
    public function randomCollaborator(): JsonResponse
    {
        try {
            $collaborator = $this->collaboratorService->getRandom();
            return $this->json(['collaborator' => $collaborator]);
        } catch (EntityNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Route('/collaborators/category', name: 'app_collaborator_by_category', methods: ['POST'])]
    public function collaboratorsByCategory(Request $request): JsonResponse
    {
        $category = $request->request->get('category');
        if (!$category) {
            return $this->json(['error' => 'Catégorie manquante dans la requête.'], 400);
        }
        try {
            $collaborators = $this->collaboratorService->filterColaboratorByCategory($category);
            return $this->json(['collaborators' => $collaborators]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/collaborators/name', name: 'app_collaborator_by_name', methods: ['POST'])]
    public function collaboratorsByName(Request $request): JsonResponse
    {
         $data = json_decode($request->getContent(), true);
    
        // Vérifier si les données sont présentes et contiennent le champ lastname
        if (!$data || !isset($data['lastname']) || empty($data['lastname'])) {
            return $this->json(['error' => 'Nom manquant dans la requête.'], 400);
        }
        
        // Récupérer le nom depuis les données JSON décodées
        $name = $data['lastname'];
        try {
            $collaborators = $this->collaboratorService->filterColaboratorByName($name);
            return $this->json(['collaborators' => $collaborators]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/collaborators/filter', name: 'app_collaborator_by_text', methods: ['POST'])]
    public function collaboratorsByText(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data || !isset($data['text'])) {
            return $this->json(['error' => 'Paramètre "text" manquant dans la requête.'], 400);
        }
        try {
            $collaborators = $this->collaboratorService->filterColaboratorByText($data);
            return $this->json(['collaborators' => $collaborators]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/collaborator/create', name: 'app_collaborator_create', methods: ['POST'])]
    public function createCollaborator(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Données manquantes pour la création.'], 400);
        }
        try {
            $collaborator = new Collaborator();

            $plainPassword = $data['password'] ?? '';
            $hashedPassword = $this->passwordHasher->hashPassword($collaborator, $plainPassword);
            $collaborator->setPassword($hashedPassword);

            // À adapter selon les propriétés de l'entité
            $collaborator->setFirstname($data['firstname'] ?? '');
            $collaborator->setLastname($data['lastname'] ?? '');
            $collaborator->setGender($data['gender'] ?? '');
            $collaborator->setEmail($data['email'] ?? '');
            $collaborator->setPassword($hashedPassword);
            $collaborator->setPhone($data['phone'] ?? null);
            $collaborator->setBirthdate(isset($data['birthdate']) ? new \DateTime($data['birthdate']) : null);
            $collaborator->setCity($data['city'] ?? null);
            $collaborator->setCountry($data['country'] ?? null);
            $collaborator->setPhoto($data['photo'] ?? null);
            $collaborator->setCategory($data['category'] ?? null);
            $collaborator->setIsAdmin($data['isAdmin'] ?? false);

            $this->collaboratorService->createCollaborator($collaborator);
            return $this->json(['collaborateur' => $collaborator]);
        } catch (InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/collaborator/update/{id}', name: 'app_collaborator_update', methods: ['PUT'])]
    public function updateCollaborator(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!$data) {
            return $this->json(['error' => 'Données manquantes pour la mise à jour.'], 400);
        }
        try {
            $collaborator = $this->collaboratorService->getById($id);
            // Mise à jour des propriétés (à adapter selon tes besoins)
            if (isset($data['firstname'])) $collaborator->setFirstname($data['firstname']);
            if (isset($data['lastname'])) $collaborator->setLastname($data['lastname']);
            if (isset($data['gender'])) $collaborator->setGender($data['gender']);
            if (isset($data['email'])) $collaborator->setEmail($data['email']);
            if (isset($data['password'])) $collaborator->setPassword($data['password']);
            if (isset($data['phone'])) $collaborator->setPhone($data['phone']);
            if (isset($data['birthdate'])) $collaborator->setBirthdate(new \DateTime($data['birthdate']));
            if (isset($data['city'])) $collaborator->setCity($data['city']);
            if (isset($data['country'])) $collaborator->setCountry($data['country']);
            if (isset($data['photo'])) $collaborator->setPhoto($data['photo']);
            if (isset($data['category'])) $collaborator->setCategory($data['category']);
            if (isset($data['isAdmin'])) $collaborator->setIsAdmin($data['isAdmin']);

            $this->collaboratorService->updateCollaborator($id, $collaborator);
            return $this->json(['message' => 'Collaborateur mis à jour avec succès.']);
        } catch (EntityNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        } catch (InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/collaborator/delete/{id}', name: 'app_collaborator_delete', methods: ['DELETE'])]
    public function deleteCollaborator(int $id): JsonResponse
    {
        try {
            $this->collaboratorService->deleteCollaborator($id);
            return $this->json(['message' => 'Collaborateur supprimé avec succès.']);
        } catch (EntityNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
