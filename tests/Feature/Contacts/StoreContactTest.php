<?php

namespace Tests\Feature\Contacts;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class StoreContactTest extends BaseContactTest
{
    use RefreshDatabase;

    /**
     * Given I am a user, When I store a contact, Then the contact is stored
     */
    public function test_can_store_own_contact() : void
    {
        $user = User::factory()
            ->create();

        Sanctum::actingAs($user);

        $contact = Contact::factory()->make();

        $response = $this->post('/api/contact', [
            'firstName' => $contact->first_name,
            'lastName' => $contact->last_name,
            'email' => $contact->email,
            'phoneHome' => $contact->phone_home,
            'phoneMobile' => $contact->phone_mobile
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contacts', $contact->toArray());
        $this->assertValidContactResponse($response, $contact->toArray());
    }
}
