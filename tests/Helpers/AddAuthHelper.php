<?php
namespace  Tests\Helpers;

trait AddAuthHelper {

    protected function makeHeader(string $bearerToken): array
    {
        return ['accept' => 'application/json', 'Authorization' => "Bearer ${bearerToken}"];
    }
}