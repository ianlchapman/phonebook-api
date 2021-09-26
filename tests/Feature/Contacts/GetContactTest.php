<?php

namespace Tests\Feature\Contacts;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class GetContactTest extends BaseContactTest
{
    use RefreshDatabase;

    /**
     * Given I am user, When I access a contact, Then I can read the contact
     */
    public function test_can_get_own_contact() : void
    {
        $user = User::factory()
            ->has(Contact::factory()->count(1))
            ->create();

        $contact = $user->contacts[0];

        Sanctum::actingAs($user);

        $response = $this->get("/api/contact/{$contact->id}");

        $response->assertStatus(200);
        $this->assertValidContactResponse($response, $contact->toArray());
    }

    /**
     * Given I am a user, When I try to read someone elses contact, Then I am returned an error
     */
    public function test_cannot_get_others_contact() : void
    {
        $user = User::factory()
            ->has(Contact::factory()->count(1))
            ->create();

        $otherUser = User::factory()
            ->has(Contact::factory()->count(1))
            ->create();

        $contact = $otherUser->contacts[0];

        Sanctum::actingAs($user);

        $response = $this->get("/api/contact/{$contact->id}");

        $response->assertStatus(403);
    }

    /**
     * Given I am a user, When I try to access a contact which doesn't exist, Then I am returned an error
     */
    public function test_cannot_get_non_existent_contact() : void
    {
        $user = User::factory()
            ->create();

        Sanctum::actingAs($user);

        $response = $this->get("/api/contact/-1");

        $response->assertStatus(404);
    }

    /**
     * Given I am a user, When I try to access a deleted contact, Then I am returned an error
     */
    public function test_cannot_get_deleted_contact() : void
    {
        $user = User::factory()
            ->has(Contact::factory()->deleted())
            ->create();
        $contact = $user->contacts()->withTrashed()->first();

        Sanctum::actingAs($user);

        $response = $this->get("/api/contact/{$contact->id}");

        $response->assertStatus(404);
    }

    /**
     * Given I am an admin, When I try to access a contact on another user, Then I can view the contact
     */
    public function test_admin_get_any_contact() : void
    {
        $adminUser = User::factory()
            ->admin()
            ->create();

        $otherUser = User::factory()
            ->has(Contact::factory()->count(1))
            ->create();

        $contact = $otherUser->contacts[0];

        Sanctum::actingAs($adminUser);

        $response = $this->get("/api/contact/{$contact->id}");

        $response->assertStatus(200);
        $this->assertValidContactResponse($response, $contact->toArray());
    }
}
