<?php

namespace App\Tests;

use App\Entity\Collaborator;
use App\Repository\CollaboratorRepository;
use App\Service\AuthService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthServiceTest extends TestCase
{
    private $authService;
    private $collaboratorRepository;
    private $tokenStorage;
    private $userPasswordHasher;

    protected function setUp(): void
    {
        // Mock dependencies
        $this->collaboratorRepository = $this->createMock(CollaboratorRepository::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);

        // Instantiate AuthService with mocked dependencies
        $this->authService = new AuthService(
            $this->collaboratorRepository,
            $this->tokenStorage,
            $this->userPasswordHasher
        );
    }

    public function testCreateNewCollaborator(): void
    {
        // Arrange
        $collaborator = $this->createMock(Collaborator::class);
        
        $this->collaboratorRepository
            ->expects($this->once())
            ->method('save')
            ->with($collaborator);

        // Act & Assert (no exception should be thrown)
        $this->authService->createNewCollaborator($collaborator);
    }

    public function testLoginUserWithValidCredentials(): void
    {
        // Arrange
        $email = 'user@example.com';
        $password = 'validpassword';
        $collaborator = $this->createMock(Collaborator::class);

        $this->collaboratorRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($collaborator);

        $this->userPasswordHasher
            ->expects($this->once())
            ->method('isPasswordValid')
            ->with($collaborator, $password)
            ->willReturn(true);

        // Act
        $result = $this->authService->loginUser($email, $password);

        // Assert
        $this->assertSame($collaborator, $result);
    }

    public function testLoginUserWithInvalidPassword(): void
    {
        // Arrange
        $email = 'user@example.com';
        $password = 'invalidpassword';
        $collaborator = $this->createMock(Collaborator::class);

        $this->collaboratorRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($collaborator);

        $this->userPasswordHasher
            ->expects($this->once())
            ->method('isPasswordValid')
            ->with($collaborator, $password)
            ->willReturn(false);

        // Act
        $result = $this->authService->loginUser($email, $password);

        // Assert
        $this->assertNull($result);
    }

    public function testLoginUserWithNonexistentEmail(): void
    {
        // Arrange
        $email = 'nonexistent@example.com';
        $password = 'password';

        $this->collaboratorRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $this->userPasswordHasher
            ->expects($this->never())
            ->method('isPasswordValid');

        // Act
        $result = $this->authService->loginUser($email, $password);

        // Assert
        $this->assertNull($result);
    }
}
