<?php 

namespace App\ApiPlatform;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class CheeseSearchFilter extends AbstractFilter
{
  private $useLike;

  public function __construct(ManagerRegistry $managerRegistry, bool $useLike = false, NameConverterInterface $nameConverter = null)
  {
      parent::__construct($managerRegistry, null, null, [], $nameConverter);

      $this->useLike = $useLike;
  }

  protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
  {
      if ($property !== 'search') {
        return;
      }

      list($alias) = $queryBuilder->getRootAliases();

      $queryBuilder
          ->andWhere(
              sprintf('%s.title LIKE :search OR %s.description LIKE :search', $alias, $alias)
          )
          ->setParameter('search', '%'.$value.'%')
      ;
  }
  
  public function getDescription(string $resourceClass): array
  {
      return [
        'search' => [
          'property' => null,
          'type' => 'string',
          'required' => false,
          'openapi' => [
            'description' => 'Search across multiple fields',
          ],
        ],
      ];
  }
}