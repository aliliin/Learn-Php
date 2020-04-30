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
namespace App\Controller;

use App\Services\CacheService;
use App\Services\TestClass;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController(prefix="cache")
 */
class CacheController extends AbstractController
{
    /**
     * @Inject
     * @var CacheService
     */
    protected $service;

    public function index()
    {
        $res = $this->service->getCache('Aliliin');
        return $this->response->json([
            $res,
            $res,
            22,
        ]);
    }

    public function put()
    {
        $res = $this->service->putCache();
        return $this->response->json($res);
    }

    public function getTestClass()
    {
        $res = $this->service->getTest(new TestClass('', '1'));
        return $this->response->json($res);
    }
}
