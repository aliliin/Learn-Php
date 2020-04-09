<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\UserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * UsersController class
 * @package App\Controller
 * @Controller(prefix="users")
 */
class UsersController extends AbstractController
{
    /**
     * @Inject()
     * @var UserService
     */
    private $userService;

   /**
    * @RequestMapping(path="test",methods="get")
    *
    * @return void
    */
    public function test(RequestInterface $request)
    {

        $uid = $request->query('uid',0);
        $userService = $this->userService->getUsername((int)$uid);
        return ['id'=> $uid,'UserName' => $userService];
        
    }
}
