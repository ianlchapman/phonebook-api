<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ContactPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool|null
     */
    public function before(User $user) : ?bool
    {
        if ($user->is_admin) {
            return true;
        }

        return null;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user) : bool
    {
        return true;
    }

    /**
     * @param User $user
     * @param Contact $contact
     * @return bool
     */
    public function view(User $user, Contact $contact) : bool
    {
        return $contact->user()->is($user);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user) : bool
    {
        return true;
    }

    /**
     * @param User $user
     * @param Contact $contact
     * @return bool
     */
    public function update(User $user, Contact $contact) : bool
    {
        return $contact->user()->is($user);
    }

    /**
     * @param User $user
     * @param Contact $contact
     * @return bool
     */
    public function delete(User $user, Contact $contact) : bool
    {
        return $contact->user()->is($user);
    }

    /**
     * @param User $user
     * @param Contact $contact
     * @return bool
     */
    public function restore(User $user, Contact $contact) : bool
    {
        return $contact->user()->is($user);
    }

    /**
     * @param User $user
     * @param Contact $contact
     * @return bool
     */
    public function forceDelete(User $user, Contact $contact) : bool
    {
        return false;
    }
}
