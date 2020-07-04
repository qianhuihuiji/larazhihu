<?php

namespace Tests\Unit\Http\Middleware;

use App\Http\Middleware\MustVerifyEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\Testcase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MustVerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unverified_user_must_verify_email_before_do_something_not_allowed()
    {
        $this->signIn(create(User::class, [
            'email_verified_at' => null
        ]));

        $middleware = new MustVerifyEmail();

        // handle() 方法接收一个 Request 实例和一个 闭包
        // 如果闭包函数被执行，说明中间件未生效，测试失败
        $response = $middleware->handle(new Request, function ($request) {
            $this->fail("Next middleware was called.");
        });

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url('/email/verify'), $response->getTargetUrl());
    }

    /** @test */
    public function verified_user_can_continue()
    {
        $this->signIn(create(User::class, [
            'email_verified_at' => Carbon::now()
        ]));

        $request = new Request();

        // 当尝试以调用函数的方式调用一个对象时，__invoke() 方法会被自动调用
        // 非常适合用来测试闭包函数是否被调用
        $next = new class {
            public $called = false;

            public function __invoke($request)
            {
                $this->called = true;

                return $request;
            }
        };

        $middleware = new MustVerifyEmail();

        $response = $middleware->handle($request, $next);

        $this->assertTrue($next->called);
        $this->assertSame($request, $response);
    }

//    /** @test */
//    public function middleware_is_applied_to_routes_those_require_verified_email()
//    {
//        $routes = [
//            'questions.create',
//            'questions.store',
//            'answers.destroy',
//            'subscribe-questions.destroy',
//            'question-comments.store',
//            'answer-comments.store',
//            'comment-comments.store',
//            'answers.store',
//            'publishments.store',
//            'answer-up-votes.store',
//            'answer-up-votes.destroy',
//            'answer-down-votes.store',
//            'answer-down-votes.destroy',
//            'question-up-votes.store',
//            'question-up-votes.destroy',
//            'question-down-votes.store',
//            'question-down-votes.destroy',
//            'comment-up-votes.store',
//            'comment-up-votes.destroy',
//            'comment-down-votes.store',
//            'comment-down-votes.destroy',
//            'best-answers.store',
//            'api.user-avatar.store',
//            'user-notifications.destroy',
//        ];
//
//        foreach ($routes as $route) {
//            $this->assertContains(
//                'must-verify-email',
//                Route::getRoutes()->getByName($route)->gatherMiddleware()
//            );
//        }
//    }
}
