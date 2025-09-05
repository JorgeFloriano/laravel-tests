<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\UserController;
use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\MockObject\Rule\MethodName;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_can_access_route(): void
    {
        $response = $this->get('/user/create');

        $response->assertStatus(200);
    }

    public function test_if_user_can_see_create_user_form()
    {
        $this->withoutExceptionHandling();

        $response = $this->get(uri: '/user/create');

        $response->assertStatus(status: 200);
        $response->assertViewIs(value: 'user.create');
        $response->assertSee(value: 'Create User');
        $response->assertSee(value: 'name="name"', escape: false);
        $response->assertSee(value: 'name="email"', escape: false);
        $response->assertSee(value: 'name="password"', escape: false);
    }

    public function test_validate_create_user()
    {
        $user = [
            'name' => '',
            'email' => 'email-email.com.br',
            'password' => bcrypt(value: '123'),
        ];

        $response = $this->from(url: '/user/create')->post(uri: '/user/store', data: $user);

        $response->assertStatus(status: 302);
        $response->assertRedirectToRoute(name: 'user.create');
        $response->assertInvalid(errors: [
            'name' => 'The name field is required.',
            'email' => 'The email field must be a valid email address.',
        ]);
    }

    // public function test_can_not_access_authenticated_user()
    // {
    //     $user = User::factory()->create();
    //     $response = $this->actingAs(user:$user)->get(uri:'/user/create');

    //     $response->assertStatus(status:302);
    //     $response->assertRedirectToRoute(name:'home');
    // }

    #[RunInSeparateProcess] public function test_error_create_user()
    {
        $user = [
            'name' => 'Jorge',
            'email' => 'email@email.com.br',
            'password' => bcrypt(value: '123'),
        ];

        // Mock way 01------------------------------------------------------------------------------

        // $this->instance(
        //     abstract: UserController::class,
        //     instance: Mockery::mock(UserController::class, function (MockInterface $mock) {
        //         $mock->shouldReceive('store')->once()->andReturn(
        //             redirect()->route(route: 'user.create')->with('error', 'User not created')
        //         );
        //     })
        // );

        // Mock way 02------------------------------------------------------------------------------

        // $this->mock(UserController::class, function (MockInterface $mock) {
        //     $mock->shouldReceive('store')->once()->andReturn(
        //         redirect()->route(route: 'user.create')->with('error', 'User not created')
        //     );
        // });

        // Mock way 03------------------------------------------------------------------------------

        $this->mock(abstract: 'alias:' . User::class, mock: function (MockInterface $mock) {
            $mock->shouldReceive('create')->once()->andReturnFalse();
        });

        $response = $this->from(url: '/user/create')->post(uri: '/user/store', data: $user);

        $response->assertStatus(status: 302);
        $response->assertRedirectToRoute(name: 'user.create');
        $response->assertSessionHas('error', 'User not created');
    }

    public function test_user_create_sucessfully()
    {
        $user = [
            'name' => 'Jorge',
            'email' => 'email@email.com.br',
            'password' => bcrypt(value: '123'),
        ];

        $this->instance(
            abstract: UserController::class,
            instance: Mockery::mock(UserController::class, function (MockInterface $mock) {
                $mock->shouldReceive('store')->once()->andReturn(
                    redirect()->route(route: 'user.create')->with('success', 'User created')
                );
            })
        );

        $response = $this->post(uri: '/user/store', data: $user);

        $response->assertStatus(status: 302);
        $response->assertRedirectToRoute(name: 'user.create');
        $response->assertSessionHas('success', 'User created');
    }

    public function test_if_email_sent()
    {
        Mail::fake();

        $user = [
            'name' => 'Jorge',
            'email' => 'email@email.com.br',
            'password' => bcrypt(value: '123'),
        ];

        $response = $this->from(url: '/user/create')->post(uri: '/user/store', data: $user);

        $user = User::latest()->first();

        Mail::assertSent(mailable: UserCreated::class, callback: function ($mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->user->id == $user->id;
        });
    }

    public function test_html_mailable()
    {
        $user = [
            'name' => 'Jorge',
            'email' => 'email@email.com.br',
            'password' => bcrypt(value: '123'),
        ];

        $response = $this->from(url: '/user/create')->post(uri: '/user/store', data: $user);

        $user = User::latest()->first();

        $mailable = new UserCreated(user: $user);
        $mailable->assertSeeInHtml(string: 'Olá Jorge, sua conta foi criada com sucesso!');
    }

    public function test_mock_mail()
    {
        Mail::fake(); // Mock

        Mail::shouldReceive('send')->andThrow(new \Exception('error'));

        $user = [
            'name' => 'Jorge',
            'email' => 'email@email.com.br',
            'password' => bcrypt(value: '123'),
        ];

        $response = $this->from(url: '/user/create')->post(uri: '/user/store', data: $user);

        $response->assertStatus(status: 500);
    }
}
