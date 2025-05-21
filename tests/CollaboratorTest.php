<?php

namespace App\Tests\Service;

use App\Entity\Collaborator;
use App\Repository\CollaboratorRepository;
use App\Service\CollaboratorService;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use Exception;

class CollaboratorServiceTest extends TestCase
{
    
    private $collaboratorRepositoryMock;
    private $collaboratorService;
    private $collaboratorMock;

    protected function setUp(): void
    {
        // Création des mocks
        /** @var CollaboratorRepository&MockObject $collaboratorRepositoryMock */
        $this->collaboratorRepositoryMock = $this->createMock(CollaboratorRepository::class);
        $this->collaboratorMock = $this->createMock(Collaborator::class);
        
        // Initialisation du service avec le mock du repository
        $this->collaboratorService = new CollaboratorService($this->collaboratorRepositoryMock);
    }

    public function testGetAll(): void
    {
        // Préparation
        $expectedCollaborators = [$this->collaboratorMock];
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedCollaborators);
        
        // Appel de la méthode à tester
        $result = $this->collaboratorService->getAll();
        
        // Assertions
        $this->assertSame($expectedCollaborators, $result);
    }

    public function testGetAllThrowsException(): void
    {
        // Configuration du mock pour lancer une exception
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willThrowException(new Exception('Erreur de base de données'));
        
        // Assertions
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Erreur lors de la récupération des collaborateurs : Erreur de base de données');
        
        // Appel de la méthode à tester
        $this->collaboratorService->getAll();
    }

    public function testGetById(): void
    {
        // Préparation
        $id = 1;
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($this->collaboratorMock);
        
        // Appel de la méthode à tester
        $result = $this->collaboratorService->getById($id);
        
        // Assertions
        $this->assertSame($this->collaboratorMock, $result);
    }

    public function testGetByIdThrowsException(): void
    {
        // Préparation
        $id = 1;
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn(null);
        
        // Assertions
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage("Collaborateur avec l'ID 1 non trouvé.");
        
        // Appel de la méthode à tester
        $this->collaboratorService->getById($id);
    }

    public function testGetByEmail(): void
    {
        // Préparation
        $email = 'test@example.com';
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($this->collaboratorMock);
        
        // Appel de la méthode à tester
        $result = $this->collaboratorService->getByEmail($email);
        
        // Assertions
        $this->assertSame($this->collaboratorMock, $result);
    }

    public function testGetByEmailThrowsException(): void
    {
        // Préparation
        $email = 'test@example.com';
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);
        
        // Assertions
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage("Collaborateur avec l'email test@example.com non trouvé.");
        
        // Appel de la méthode à tester
        $this->collaboratorService->getByEmail($email);
    }

    public function testGetRandom(): void
    {
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findRandom')
            ->willReturn($this->collaboratorMock);
        
        // Appel de la méthode à tester
        $result = $this->collaboratorService->getRandom();
        
        // Assertions
        $this->assertSame($this->collaboratorMock, $result);
    }

    public function testGetRandomThrowsException(): void
    {
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findRandom')
            ->willReturn(null);
        
        // Assertions
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage("Aucun collaborateur trouvé pour un tirage aléatoire.");
        
        // Appel de la méthode à tester
        $this->collaboratorService->getRandom();
    }

    public function testFilterColaboratorByCategory(): void
    {
        // Préparation
        $category = 'Développeur';
        $expectedCollaborators = [$this->collaboratorMock];
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findByFiltersCategory')
            ->with($category)
            ->willReturn($expectedCollaborators);
        
        // Appel de la méthode à tester
        $result = $this->collaboratorService->filterColaboratorByCategory($category);
        
        // Assertions
        $this->assertSame($expectedCollaborators, $result);
    }

    public function testFilterColaboratorByCategoryThrowsException(): void
    {
        // Préparation
        $category = 'Développeur';
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findByFiltersCategory')
            ->with($category)
            ->willThrowException(new Exception('Erreur de filtrage'));
        
        // Assertions
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Erreur lors du filtrage par catégorie : Erreur de filtrage');
        
        // Appel de la méthode à tester
        $this->collaboratorService->filterColaboratorByCategory($category);
    }

    public function testFilterColaboratorByName(): void
    {
        // Préparation
        $name = 'Doe';
        $expectedCollaborators = [$this->collaboratorMock];
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findByFiltersName')
            ->with($name)
            ->willReturn($expectedCollaborators);
        
        // Appel de la méthode à tester
        $result = $this->collaboratorService->filterColaboratorByName($name);
        
        // Assertions
        $this->assertSame($expectedCollaborators, $result);
    }

    public function testFilterColaboratorByNameThrowsException(): void
    {
        // Préparation
        $name = 'Doe';
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findByFiltersName')
            ->with($name)
            ->willThrowException(new Exception('Erreur de filtrage'));
        
        // Assertions
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Erreur lors du filtrage par nom : Erreur de filtrage');
        
        // Appel de la méthode à tester
        $this->collaboratorService->filterColaboratorByName($name);
    }

    public function testFilterColaboratorByText(): void
    {
        // Préparation
        $text = ['PHP', 'Symfony'];
        $expectedCollaborators = [$this->collaboratorMock];
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findByFiltersText')
            ->with($text)
            ->willReturn($expectedCollaborators);
        
        // Appel de la méthode à tester
        $result = $this->collaboratorService->filterColaboratorByText($text);
        
        // Assertions
        $this->assertSame($expectedCollaborators, $result);
    }

    public function testFilterColaboratorByTextThrowsException(): void
    {
        // Préparation
        $text = ['PHP', 'Symfony'];
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findByFiltersText')
            ->with($text)
            ->willThrowException(new Exception('Erreur de filtrage'));
        
        // Assertions
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Erreur lors du filtrage par texte : Erreur de filtrage');
        
        // Appel de la méthode à tester
        $this->collaboratorService->filterColaboratorByText($text);
    }

    public function testCreateCollaborator(): void
    {
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->collaboratorMock)
            ->willReturn($this->collaboratorMock);
        
        // Appel de la méthode à tester
        $result = $this->collaboratorService->createCollaborator($this->collaboratorMock);
        
        // Assertions
        $this->assertSame($this->collaboratorMock, $result);
    }

    public function testCreateCollaboratorWithInvalidArgument(): void
    {
        // Préparation
        $invalidObject = new \stdClass();
        
        // Assertions
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'objet fourni n'est pas une instance de Collaborator.");
        
        // Appel de la méthode à tester
        $this->collaboratorService->createCollaborator($invalidObject);
    }

    public function testCreateCollaboratorThrowsException(): void
    {
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->collaboratorMock)
            ->willThrowException(new Exception('Erreur de sauvegarde'));
        
        // Assertions
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Erreur lors de la création du collaborateur : Erreur de sauvegarde');
        
        // Appel de la méthode à tester
        $this->collaboratorService->createCollaborator($this->collaboratorMock);
    }

    public function testUpdateCollaborator(): void
    {
        // Préparation
        $id = 1;
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($this->collaboratorMock);
            
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('update')
            ->with($id, $this->collaboratorMock)
            ->willReturn($this->collaboratorMock);
        
        // Appel de la méthode à tester
        $result = $this->collaboratorService->updateCollaborator($id, $this->collaboratorMock);
        
        // Assertions
        $this->assertSame($this->collaboratorMock, $result);
    }

    public function testUpdateCollaboratorWithInvalidArgument(): void
    {
        // Préparation
        $id = 1;
        $invalidObject = new \stdClass();
        
        // Assertions
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'objet fourni n'est pas une instance de Collaborator.");
        
        // Appel de la méthode à tester
        $this->collaboratorService->updateCollaborator($id, $invalidObject);
    }

    public function testUpdateCollaboratorWithNonExistentId(): void
    {
        // Préparation
        $id = 1;
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn(null);
        
        // Assertions
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage("Collaborateur avec l'ID 1 non trouvé pour la mise à jour.");
        
        // Appel de la méthode à tester
        $this->collaboratorService->updateCollaborator($id, $this->collaboratorMock);
    }

    public function testUpdateCollaboratorThrowsException(): void
    {
        // Préparation
        $id = 1;
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($this->collaboratorMock);
            
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('update')
            ->with($id, $this->collaboratorMock)
            ->willThrowException(new Exception('Erreur de mise à jour'));
        
        // Assertions
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Erreur lors de la mise à jour du collaborateur : Erreur de mise à jour');
        
        // Appel de la méthode à tester
        $this->collaboratorService->updateCollaborator($id, $this->collaboratorMock);
    }

    public function testDeleteCollaborator(): void
    {
        // Préparation
        $id = 1;
        $expectedResult = true;
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($this->collaboratorMock);
            
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn($expectedResult);
        
        // Appel de la méthode à tester
        $result = $this->collaboratorService->deleteCollaborator($id);
        
        // Assertions
        $this->assertSame($expectedResult, $result);
    }

    public function testDeleteCollaboratorWithNonExistentId(): void
    {
        // Préparation
        $id = 1;
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn(null);
        
        // Assertions
        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage("Collaborateur avec l'ID 1 non trouvé pour la suppression.");
        
        // Appel de la méthode à tester
        $this->collaboratorService->deleteCollaborator($id);
    }

    public function testDeleteCollaboratorThrowsException(): void
    {
        // Préparation
        $id = 1;
        
        // Configuration du mock
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('findById')
            ->with($id)
            ->willReturn($this->collaboratorMock);
            
        $this->collaboratorRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willThrowException(new Exception('Erreur de suppression'));
        
        // Assertions
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Erreur lors de la suppression du collaborateur : Erreur de suppression');
        
        // Appel de la méthode à tester
        $this->collaboratorService->deleteCollaborator($id);
    }
}