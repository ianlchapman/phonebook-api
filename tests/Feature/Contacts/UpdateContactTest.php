<?php

namespace Tests\Feature\Contacts;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class UpdateContactTest extends BaseContactTest
{
    use RefreshDatabase;

    /**
     * Given I am a user, When I update a contact, Then the contact is updated
     */
    public function test_can_update_own_contact() : void
    {
        $user = User::factory()
            ->has(Contact::factory()->count(1))
            ->create();

        $contact = $user->contacts[0];

        Sanctum::actingAs($user);

        $newContact = Contact::factory()->make();

        $response = $this->put("/api/contact/{$contact->id}", [
            'firstName' => $newContact->first_name,
            'lastName' => $newContact->last_name,
            'email' => $newContact->email,
            'phoneHome' => $newContact->phone_home,
            'phoneMobile' => $newContact->phone_mobile
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', $newContact->toArray());
        $this->assertValidContactResponse($response, $newContact->toArray());
    }

    /**
     * Given I am a user, When I try to update someone elses contact, Then I am returned an error
     */
    public function test_cannot_update_others_contact() : void
    {
        $user = User::factory()
            ->has(Contact::factory()->count(1))
            ->create();

        $otherUser = User::factory()
            ->has(Contact::factory()->count(1))
            ->create();

        $contact = $otherUser->contacts[0];

        Sanctum::actingAs($user);

        $newContact = Contact::factory()->make();

        $response = $this->put("/api/contact/{$contact->id}", [
            'firstName' => $newContact->first_name,
            'lastName' => $newContact->last_name,
            'email' => $newContact->email,
            'phoneHome' => $newContact->phone_home,
            'phoneMobile' => $newContact->phone_mobile
        ]);

        $response->assertStatus(403);
    }

    /**
     * Given I am a user, When I try to update a contact which doesn't exist, Then I am returned an error
     */
    public function test_cannot_update_non_existent_contact() : void
    {
        $user = User::factory()
            ->create();

        Sanctum::actingAs($user);

        $newContact = Contact::factory()->make();

        $response = $this->put("/api/contact/-1", [
            'firstName' => $newContact->first_name,
            'lastName' => $newContact->last_name,
            'email' => $newContact->email,
            'phoneHome' => $newContact->phone_home,
            'phoneMobile' => $newContact->phone_mobile
        ]);

        $response->assertStatus(404);
    }

    /**
     * Given I am a user, When I try to update a deleted contact, Then I am returned an error
     */
    public function test_cannot_update_deleted_contact() : void
    {
        $user = User::factory()
            ->has(Contact::factory()->deleted())
            ->create();

        $contact = $user->contacts()->withTrashed()->first();

        Sanctum::actingAs($user);

        $newContact = Contact::factory()->make();

        $response = $this->put("/api/contact/{$contact->id}", [
            'firstName' => $newContact->first_name,
            'lastName' => $newContact->last_name,
            'email' => $newContact->email,
            'phoneHome' => $newContact->phone_home,
            'phoneMobile' => $newContact->phone_mobile
        ]);

        $response->assertStatus(404);
    }

    /**
     * Given I am an admin, When I try to update a contact on another user, Then I can update it
     */
    public function test_admin_update_any_contact() : void
    {
        $adminUser = User::factory()
            ->admin()
            ->create();

        $otherUser = User::factory()
            ->has(Contact::factory()->count(1))
            ->create();

        $contact = $otherUser->contacts[0];

        Sanctum::actingAs($adminUser);

        $newContact = Contact::factory()->make();

        $response = $this->put("/api/contact/{$contact->id}", [
            'firstName' => $newContact->first_name,
            'lastName' => $newContact->last_name,
            'email' => $newContact->email,
            'phoneHome' => $newContact->phone_home,
            'phoneMobile' => $newContact->phone_mobile
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', $newContact->toArray());
        $this->assertValidContactResponse($response, $newContact->toArray());
    }
}
