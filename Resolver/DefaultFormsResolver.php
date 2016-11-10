<?php

namespace Toro\Bundle\CustomFormBundle\Resolver;

use Doctrine\ORM\NonUniqueResultException;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class DefaultFormsResolver implements FormsResolverInterface
{
    /**
     * @var RepositoryInterface
     */
    private $formsRepository;

    /**
     * @param RepositoryInterface $formsRepository
     */
    public function __construct(RepositoryInterface $formsRepository)
    {
        $this->formsRepository = $formsRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($schemaAlias, $namespace = null)
    {
        try {
            $criteria = ['schemaAlias' => $schemaAlias];

            if (null !== $namespace) {
                $criteria['namespace'] = $namespace;
            }

            return $this->formsRepository->findOneBy($criteria);
        } catch (NonUniqueResultException $exception) {
            throw new \LogicException(
                sprintf('Multiple schemas found for "%s". You should probably define a custom forms resolver for this schema.', $schemaAlias),
                0,
                $exception
            );
        }
    }
}
