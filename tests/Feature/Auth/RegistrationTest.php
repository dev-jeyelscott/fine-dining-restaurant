<?php

test('registration screen is not available', function () {
    $response = $this->get('/register');

    $response->assertNotFound();
});

test('public users can not register', function () {
    $response = $this->post('/register', [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertNotFound();

    $this->assertGuest();
});
