<?php

use App\Rules\SouthAfricanIdNumber;

test('south african id rule accepts a valid id number', function () {
    $rule = new SouthAfricanIdNumber();

    expect($rule->passes('sa_id_number', '8001015009087'))->toBeTrue();
});

test('south african id rule rejects an invalid checksum', function () {
    $rule = new SouthAfricanIdNumber();

    expect($rule->passes('sa_id_number', '8001015009086'))->toBeFalse();
});

test('south african id rule rejects malformed values', function (string $value) {
    $rule = new SouthAfricanIdNumber();

    expect($rule->passes('sa_id_number', $value))->toBeFalse();
})->with([
    'too short' => '800101500908',
    'too long' => '80010150090870',
    'contains letters' => '80010150090AB',
    'empty string' => '',
]);

test('south african id rule returns the expected message', function () {
    $rule = new SouthAfricanIdNumber();

    expect($rule->message())->toBe('The :attribute must be a valid South African ID number.');
});