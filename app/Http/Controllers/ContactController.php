<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListContactsRequest;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactController extends Controller
{
    /**
     * @param ListContactsRequest $request
     * @return AnonymousResourceCollection
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(ListContactsRequest $request) : AnonymousResourceCollection
    {
        $this->authorize('viewAny', Contact::class);

        $contacts = $request
            ->user()
            ->contacts()
            ->orderBy($request->input('sort', 'id'))
            ->paginate();

        return ContactResource::collection($contacts);
    }

    /**
     * @param StoreContactRequest $request
     * @return JsonResource
     */
    public function store(StoreContactRequest $request) : JsonResource
    {
        // Authorisation performed in the request

        // Create and store the contact
        $contact = new Contact();
        $contact->first_name = $request->input('firstName');
        $contact->last_name = $request->input('lastName');
        $contact->email = $request->input('email', null);
        $contact->phone_home = $request->input('phoneHome', null);
        $contact->phone_mobile = $request->input('phoneMobile', null);
        $contact->user()->associate($request->user());
        $contact->save();

        return new ContactResource($contact);
    }

    /**
     * @param Contact $contact
     * @return JsonResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Contact $contact) : JsonResource
    {
        $this->authorize('view', $contact);

        return new ContactResource($contact);
    }

    /**
     * @param UpdateContactRequest $request
     * @param Contact $contact
     * @return JsonResource
     */
    public function update(UpdateContactRequest $request, Contact $contact) : JsonResource
    {
        // Authorisation performed in the request

        // Update the contact
        $contact->first_name = $request->input('firstName', $contact->first_name);
        $contact->last_name = $request->input('lastName', $contact->last_name);
        $contact->email = $request->input('email', $contact->email ?? null);
        $contact->phone_home = $request->input('phoneHome', $contact->phone_home ?? null);
        $contact->phone_mobile = $request->input('phoneMobile', $contact->phone_mobile ?? null);
        $contact->save();

        return new ContactResource($contact);
    }

    /**
     * @param Contact $contact
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Contact $contact) : void
    {
        $this->authorize('delete', $contact);

        $contact->delete();
    }
}
