<?php

namespace Tests\Feature\Contacts;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class ListContactsTest extends BaseContactTest
{
    use RefreshDatabase;

    /**
     * Given I am a user, When I view a list of contacts, Then a paginated list of contacts is returned
     */
    public function test_can_list_own_contacts(): void
    {
        $user = User::factory()
            ->has(Contact::factory()->count(5))
            ->create();

        $contact = $user->contacts()->orderBy('id')->first();

        Sanctum::actingAs($user);

        $response = $this->get('/api/contact');

        $response->assertStatus(200)
            ->assertJson(static function (AssertableJson $json) use ($contact) {
                $json
                    ->has('data', 5)
                    ->has('data.0', function (AssertableJson $json) use ($contact) {
                        $json->whereAll([
                            'id' => $contact['id'],
                            'firstName' => $contact['first_name'],
                            'lastName' => $contact['last_name'],
                            'email' => $contact['email'],
                            'phoneHome' => $contact['phone_home'],
                            'phoneMobile' => $contact['phone_mobile']
                        ]);
                    })
                    ->has('links', function (AssertableJson $json) {
                        $json->hasAll([
                            'first', 'last', 'prev', 'next'
                        ]);
                    })
                    ->has('meta', function (AssertableJson $json) {
                        $json->hasAll([
                            'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total'
                        ]);
                    })
                    ->etc();
            });
    }
}
