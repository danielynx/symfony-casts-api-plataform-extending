<?php 

namespace App\ApiPlatform;

use ApiPlatform\Core\Serializer\Filter\FilterInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

class DailyStatsDateFilter implements FilterInterface
{
    public const FROM_FILTER_CONTEXT = 'daily_stats_from';

    private $logger;
    private $throwOnInvalid;

    public function __construct(LoggerInterface $logger, bool $throwOnInvalid = false)
    {
        $this->logger = $logger;
        $this->throwOnInvalid = $throwOnInvalid;
    }

    public function apply(Request $request, bool $normalization, array $attributes, array &$context)
    {
      $from = $request->query->get('from');
      
      if (!$from) {
        return;
      }

      $fromDate = \DateTimeImmutable::createFromFormat('Y-m-d', $from);
      
      if (!$fromDate && $this->throwOnInvalid) {
        throw new BadRequestException('Invalid "from" date format');
      }
      
      if ($fromDate) {
        $this->logger->info(sprintf('Filter from date"%s"', $from));
        
        $fromDate = $fromDate->setTime(0, 0, 0);
        
        $context[self::FROM_FILTER_CONTEXT] = $fromDate;      
      }
    }

    public function getDescription(string $resourceClass): array
    {
      return [
        'from' => [
          'property' => null,
          'type' => 'string',
          'required' => false,
          'openapi' => [
            'description' => 'From date e.g. 2020-09-01',
          ],
        ],
      ];
    }
}