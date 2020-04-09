<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use App\Middleware\CheckUserMiddleware;
use App\Middleware\CheckDataMiddleware;

/**
 * UsersController class
 * @package App\Controller
 * @Controller(prefix="users")
 * @Middlewares({
        @Middleware(CheckUserMiddleware::class),
 *      @Middleware(CheckDataMiddleware::class),
 *     })
 */
class UsersController extends AbstractController
{
    /**
     * @Inject()
     * @var UserService
     */
    private $userService;

    /**
     * @RequestMapping(path="{uid:\d+}",methods="get,post")
     *
     * @return void
     */
    public function userInfo(RequestInterface $request,$uid)
    {

        $userService = $this->userService->getUsername((int) $uid);
        return ['id' => $uid, 'UserName' => $userService];
        
    }
}
