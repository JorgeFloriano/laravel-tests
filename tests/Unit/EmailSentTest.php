<?php

namespace Tests\Unit;

use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailSentTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_email_sent(): void
    {
        Mail::fake();   

        $user = User::factory()->create();

        Mail::to($user->email)->send(mailable: new UserCreated($user));

        Mail::assertSent(mailable: UserCreated::class, callback: function ($mail) use ($user) {
            return $mail->hasTo($user->email) 
            && $mail->user->id == $user->id 
            && $mail->hasSubject(subject: 'User Created');
        });
    }

    public function test_if_email_not_sent(): void
    {
        Mail::fake();   

        $user = User::factory()->create();

        Mail::to($user->name)->send(mailable: new UserCreated($user));

        Mail::assertNotSent(mailable: UserCreated::class, callback: function ($mail) use ($user) {
            return $mail->hasTo($user->email) 
            && $mail->user->id == $user->id 
            && $mail->hasSubject(subject: 'User Created');
        });
    }

    public function test_if_email_on_queue(): void
    {
        Mail::fake();   

        $user = User::factory()->create();

        Mail::to($user->email)->queue(mailable: new UserCreated($user));

        Mail::assertQueued(mailable: UserCreated::class, callback: function ($mail) use ($user) {
            return $mail->hasTo($user->email) 
            && $mail->user->id == $user->id 
            && $mail->hasSubject(subject: 'User Created');
        });
    }

    public function test_email_html(): void
    { 
        $user = User::factory()->create();

        $mailable = new UserCreated($user);

        $mailable->assertSeeInHtml(string: 'Olá ' . $user->name . ', sua conta foi criada com sucesso!');
    }
}
