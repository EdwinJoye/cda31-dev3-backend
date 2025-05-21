<?php

namespace App\Repository;

use App\Entity\Collaborator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @extends ServiceEntityRepository<Collaborator>
 */
class CollaboratorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collaborator::class);
    }

    /**
     * @return Collaborator[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    public function findById(int|string $id): ?Collaborator
    {
        return $this->find($id);
    }

    public function findByEmail(string $email): ?Collaborator
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findRandom(): ?Collaborator
    {
        $count = $this->count([]);
        if ($count === 0) {
            return null;
        }

        $offset = random_int(0, $count - 1);

        return $this->createQueryBuilder('c')
            ->setFirstResult($offset)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Collaborator[]
     */
    public function findByFiltersName(string $name): array
    {
        return $this->createQueryBuilder('c')
            ->where('LOWER(c.firstname) LIKE :name OR LOWER(c.lastname) LIKE :name')
            ->setParameter('name', '%' . strtolower($name) . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Collaborator[]
     */
    public function findByFiltersCategory(string $category): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche sur plusieurs champs texte (firstname, lastname, city, country, email, category)
     * @param array $text (ex: ['text' => 'motclé'])
     * @return Collaborator[]
     */
    public function findByFiltersText(array $text): array
    {
        $search = isset($text['text']) ? strtolower($text['text']) : '';
        if (empty($search)) {
            return [];
        }

        return $this->createQueryBuilder('c')
            ->where('LOWER(c.firstname) LIKE :search')
            ->orWhere('LOWER(c.lastname) LIKE :search')
            ->orWhere('LOWER(c.city) LIKE :search')
            ->orWhere('LOWER(c.country) LIKE :search')
            ->orWhere('LOWER(c.email) LIKE :search')
            ->orWhere('LOWER(c.category) LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->getQuery()
            ->getResult();
    }

    public function save(Collaborator $collaborator): void
    {
        $this->_em->persist($collaborator);
        $this->_em->flush();
    }

    public function update(int|string $id, Collaborator $collaborator): void
    {
        // Doctrine gère l'update automatiquement si l'entité est déjà managed.
        // Sinon, il faut merger et flush.
        $existing = $this->find($id);
        if ($existing) {
            // Copier les propriétés si besoin, ou juste flush si déjà modifiées
            $this->_em->flush();
        }
    }

    public function delete(int|string $id): void
    {
        $collaborator = $this->find($id);
        if ($collaborator) {
            $this->_em->remove($collaborator);
            $this->_em->flush();
        }
    }
}