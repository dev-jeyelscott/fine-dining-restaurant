<?php

use App\Models\User;
use Filament\Auth\Pages\Login;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

test('it renders the branded admin login experience', function () {
    $this->get(route('filament.admin.auth.login'))
        ->assertOk()
        ->assertSee((string) config('app.name'))
        ->assertSee('Private administration')
        ->assertSee('Reservation Requests')
        ->assertSee('Order Inquiries')
        ->assertSee('Return to restaurant website')
        ->assertSee('Authorized restaurant staff only.')
        ->assertSee(
            'href="'.route('home').'"',
            escape: false,
        );
});

test('it keeps the admin panel protected from guests', function () {
    $this->get('/admin')
        ->assertRedirect(route('filament.admin.auth.login'));
});

test('it authenticates the authorized admin through the native filament flow', function () {
    $user = User::factory()->create([
        'email' => 'restaurant-admin@example.test',
        'password' => 'correct-password',
    ]);

    config()->set('admin.seed_user.email', $user->email);

    Livewire::test(Login::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'correct-password')
        ->set('data.remember', false)
        ->call('authenticate')
        ->assertHasNoErrors();

    $this->assertAuthenticatedAs($user);
});

test('it rejects invalid credentials with a safe validation error', function () {
    $user = User::factory()->create([
        'email' => 'restaurant-admin@example.test',
        'password' => 'correct-password',
    ]);

    config()->set('admin.seed_user.email', $user->email);

    Livewire::test(Login::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'incorrect-password')
        ->set('data.remember', false)
        ->call('authenticate')
        ->assertHasErrors([
            'data.email',
        ]);

    $this->assertGuest();
});

test('it rejects a valid user who is not authorized for the admin panel', function () {
    config()->set(
        'admin.seed_user.email',
        'authorized-admin@example.test',
    );

    $unauthorizedUser = User::factory()->create([
        'email' => 'unauthorized-user@example.test',
        'password' => 'correct-password',
    ]);

    Livewire::test(Login::class)
        ->set('data.email', $unauthorizedUser->email)
        ->set('data.password', 'correct-password')
        ->set('data.remember', false)
        ->call('authenticate')
        ->assertHasErrors([
            'data.email',
        ]);

    $this->assertGuest();
});
