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

use App\Middleware\CheckDataMiddleware;
use App\Middleware\CheckUserMiddleware;
use App\Services\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * UsersController class.
 * @Controller(prefix="users")
 * @Middlewares({
 *     @Middleware(CheckUserMiddleware::class),
 *     @Middleware(CheckDataMiddleware::class),
 * })
 */
class UsersController extends AbstractController
{
    /**
     * @Inject
     * @var UserService
     */
    private $userService;

    /**
     * @RequestMapping(path="{uid:\d+}", methods="get,post")
     *
     * @param mixed $uid
     */
    public function userInfo(RequestInterface $request, $uid)
    {
        var_dump($request->getQueryParams());
        var_dump($request->all());
        $userService = $this->userService->getUsername((int) $uid);
        return ['id' => $uid, 'UserName' => $userService];
    }
}
