<?php

namespace DoctrineTestingTools\Infrastructure;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use DoctrineTestingTools\Domain\Exemple;
use DoctrineTestingTools\Domain\ExempleId;
use DoctrineTestingTools\Domain\ExempleNotFoundException;
use DoctrineTestingTools\Domain\ExempleRepositoryInterface;

/** @extends EntityRepository<Exemple> */
class ExempleRepositoryDoctrine extends EntityRepository implements ExempleRepositoryInterface
{
    public function __construct(EntityManagerInterface $em)
    {
        /** @var ClassMetadata<Exemple> $classMD */
        $classMD = $em->getClassMetadata(Exemple::class);
        parent::__construct($em, $classMD);
    }

    public function add(Exemple $exemple): void
    {
        $this->getEntityManager()->persist($exemple);
    }

    /** @throws ExempleNotFoundException */
    public function findById(ExempleId $exempleId): Exemple
    {
        $object = $this->getEntityManager()->find(Exemple::class, $exempleId);
        if ($object != null) {
            return $object;
        }
        throw new ExempleNotFoundException();
    }
}
