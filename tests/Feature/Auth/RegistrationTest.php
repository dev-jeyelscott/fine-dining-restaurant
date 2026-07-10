<?php

test('registration screen is not available', function (): void {
    $this->get('/register')
        ->assertNotFound();
});

test('public users can not register', function (): void {
    $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();

    $this->assertGuest();
});
