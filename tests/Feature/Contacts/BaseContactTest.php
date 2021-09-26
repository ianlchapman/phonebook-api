<?php

namespace Tests\Feature\Contacts;

use App\Models\Contact;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

abstract class BaseContactTest extends TestCase
{
    /**
     * @param TestResponse $response
     * @param array $contact
     * @return TestResponse
     */
    protected function assertValidContactResponse(TestResponse $response, array $contact): TestResponse
    {
        $response->assertJson(static function (AssertableJson $json) use ($contact) {
            $json->has('data', function (AssertableJson $json) use ($contact) {
                if (isset($contact['id'])) {
                    $json->where('id', $contact['id']);
                }

                $json->whereAll([
                    'firstName' => $contact['first_name'],
                    'lastName' => $contact['last_name'],
                    'email' => $contact['email'],
                    'phoneHome' => $contact['phone_home'],
                    'phoneMobile' => $contact['phone_mobile']
                ])
                    ->etc();
            });
        });

        return $response;
    }
}
