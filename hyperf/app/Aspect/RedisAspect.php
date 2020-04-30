<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */
namespace App\Aspect;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Redis\RedisProxy;
use Hyperf\Utils\Codec\Json;

/**
 * @Aspect
 */
class RedisAspect extends AbstractAspect
{
    public $classes = [
        RedisProxy::class . '::__call',
    ];

    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $this->logger->info(Json::encode($proceedingJoinPoint->getArguments()));
        return $proceedingJoinPoint->process();
    }
}
