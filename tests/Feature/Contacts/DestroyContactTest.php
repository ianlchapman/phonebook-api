<?php

namespace Tests\Feature\Contacts;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class DestroyContactTest extends BaseContactTest
{
    use RefreshDatabase;

    /**
     * Given I am a user, When I delete a contact, Then the contact is deleted
     */
    public function test_can_delete_own_contact() : void
    {
        $user = User::factory()
            ->has(Contact::factory()->count(1))
            ->create();

        $contact = $user->contacts[0];

        Sanctum::actingAs($user);

        $response = $this->delete("/api/contact/{$contact->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('contacts', $contact->toArray());
    }

    /**
     * Given I am a user, When I try to delete someone elses contact, Then I am returned an error
     */
    public function test_cannot_delete_others_contact() : void
    {
        $user = User::factory()
            ->has(Contact::factory()->count(1))
            ->create();

        $otherUser = User::factory()
            ->has(Contact::factory()->count(1))
            ->create();

        $contact = $otherUser->contacts[0];

        Sanctum::actingAs($user);

        $response = $this->delete("/api/contact/{$contact->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('contacts', $contact->toArray());
    }

    /**
     * Given I am a user, When I try to delete a contact which doesn't exist, Then I am returned an error
     */
    public function test_cannot_delete_non_existent_contact() : void
    {
        $user = User::factory()
            ->create();

        Sanctum::actingAs($user);

        $response = $this->delete('/api/contact/-1');

        $response->assertStatus(404);
    }

    /**
     * Given I am an admin, When I try to delete a contact on another user, Then I can delete it
     */
    public function test_admin_delete_any_contact() : void
    {
        $adminUser = User::factory()
            ->admin()
            ->create();

        $otherUser = User::factory()
            ->has(Contact::factory()->count(1))
            ->create();

        $contact = $otherUser->contacts[0];

        Sanctum::actingAs($adminUser);

        $response = $this->delete("/api/contact/{$contact->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('contacts', $contact->toArray());
    }
}
