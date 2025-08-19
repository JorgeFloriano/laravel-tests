<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    public function test_if_user_can_access_route(): void
    {
        $response = $this->get(uri:'/login');

        $response->assertStatus(status:200);
    }

    public function test_if_user_can_see_login_form()
    {
        //$this->withoutExceptionHandling();

        $response = $this->get(uri:'/login');

        $response->assertStatus(status:200);
        $response->assertViewIs(value:'login.index');
        $response->assertSee(value:'Login Form');
        $response->assertDontSee(value:'Login Forms');
        $response->assertSee(value:'name="email"', escape:false);
        $response->assertSee(value:'name="password"', escape:false);
    }

    public function test_user_can_not_access_login_page_authenticated()
    {
        $user = User::factory()->create();
        $response = $this->actingAs(user:$user)->get(uri:'/login');

        $response->assertStatus(status:302);
        $response->assertRedirectToRoute(name:'home');
    }

    public function test_with_validate_errors()
    {
        $response = $this->from(url:'/login')->post(uri:'/login', data:[
            'email' => 'not-an-email',
            'password' => ''
        ]);

        $response->assertStatus(status:302);
        $response->assertRedirectToRoute(name:'login');
        $response->assertInvalid(errors:[
            'email' => 'The email field must be a valid email address.', 'password' => 'The password field is required.'
        ]);
    }

    public function test_login_successful()
    {
        $user = User::factory()->create([
            'email' => 'email@email.com.br',
            'password' => bcrypt(value:'password'),
        ]);

        $response = $this->post(uri:'/login', data:[
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(status:302);
        $response->assertRedirectToRoute(name:'home');
    }

    public function test_if_password_or_email_are_valid()
    {
        $response = $this->post(uri:'/login', data:[
            'email' => 'L8Bt2@example.com',
            'password' => 'p454555ddd'
        ]);

        $response->assertStatus(status:302);
        $response->assertSessionHas('message', 'The email or password is invalid');
        $response->assertRedirectToRoute(name:'login');
    }
}
