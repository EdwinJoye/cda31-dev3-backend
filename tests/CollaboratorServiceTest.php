<?php
namespace App\Tests;

use App\Entity\Collaborator;
use App\Repository\CollaboratorRepository;
use App\Service\CollaboratorService;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CollaboratorServiceTest extends TestCase
{
    private CollaboratorService $collaboratorService;
    private MockObject|CollaboratorRepository $collaboratorRepository;

    protected function setUp(): void
    {
        $this->collaboratorRepository = $this->createMock(CollaboratorRepository::class);
        $this->collaboratorService = new CollaboratorService($this->collaboratorRepository);
    }

    /**
     * @dataProvider provideCollaboratorMethods
     */
    public function testCollaboratorServiceMethods(string $method, $expectedResult, $mockMethod, $params = [], $exception = null): void
    {
        // Arrange
        $this->collaboratorRepository
            ->expects($this->once())
            ->method($mockMethod)
            ->willReturnCallback(fn() => $exception ? throw $exception : $expectedResult);

        if ($exception) {
            $this->expectException(get_class($exception));
            $this->expectExceptionMessage($exception->getMessage());
        }

        // Act
        $result = call_user_func_array([$this->collaboratorService, $method], $params);

        // Assert
        if (!$exception) {
            $this->assertSame($expectedResult, $result);
        }
    }

    public function provideCollaboratorMethods(): array
    {
        $collaborator = $this->createCollaborator(1, 'john@example.com');
        $exception = new Exception('Database error');

        return [
            ['getAll', [$collaborator], 'findAll'],
            ['getAll', null, 'findAll', [], $exception],
            ['getById', $collaborator, 'findById', [1]],
            ['getById', null, 'findById', [999], new EntityNotFoundException("Collaborateur avec l'ID 999 non trouvé.")],
            ['getByEmail', $collaborator, 'findByEmail', ['john@example.com']],
            ['getByEmail', null, 'findByEmail', ['invalid@example.com'], new EntityNotFoundException("Collaborateur avec l'email invalid@example.com non trouvé.")],
            ['getRandom', $collaborator, 'findRandom'],
            ['getRandom', null, 'findRandom', [], new EntityNotFoundException("Aucun collaborateur trouvé pour un tirage aléatoire.")]
        ];
    }

    public function testCreateAndUpdateCollaborators(): void
    {
        // Arrange: Creating a new collaborator
        $newCollaborator = $this->createCollaborator(null, 'new@example.com');
        $this->collaboratorRepository
            ->expects($this->once())
            ->method('save')
            ->with($newCollaborator);

        // Act & Assert: Testing the creation step
        $this->collaboratorService->createCollaborator($newCollaborator);

        // Arrange: Updating an existing collaborator
        $existingCollaborator = $this->createCollaborator(1, 'old@example.com');
        $updatedCollaborator = $this->createCollaborator(1, 'updated@example.com');

        $this->collaboratorRepository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($existingCollaborator);

        $this->collaboratorRepository
            ->expects($this->once())
            ->method('update')
            ->with(1, $updatedCollaborator);

        // Act & Assert: Testing the update step
        $this->collaboratorService->updateCollaborator(1, $updatedCollaborator);
    }

    public function testDeleteCollaborator(): void
    {
        $existingCollaborator = $this->createCollaborator(1, 'delete@example.com');
        $this->collaboratorRepository
            ->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($existingCollaborator);

        $this->collaboratorRepository
            ->expects($this->once())
            ->method('delete')
            ->with(1);

        $this->collaboratorService->deleteCollaborator(1);
    }

    private function createCollaborator(?int $id, string $email): MockObject|Collaborator
    {
        $collaborator = $this->createMock(Collaborator::class);
        if ($id !== null) {
            $collaborator->method('getId')->willReturn($id);
        }
        $collaborator->method('getEmail')->willReturn($email);

        return $collaborator;
    }
}
