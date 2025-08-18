<?php

test('home page loads successfully', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
