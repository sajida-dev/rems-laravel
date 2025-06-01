<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {

        $isAgent = $user->role === 'agent';

        // Validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => [$isAgent ? 'nullable' : 'required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'contact' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:2024'],
        ];

        // Additional rules for agents
        if ($user->role === 'agent') {
            $rules = array_merge($rules, [
                'agency' => ['required', 'string', 'max:255'],
                'licence_no' => ['required', 'string', 'max:255'],
                'experience' => ['required', 'numeric', 'min:0'],
                'bio' => ['required', 'string', 'max:1000'],
                'categories' => ['required', 'array'],
                'categories.*' => ['integer', 'exists:categories,id'],
            ]);
        }

        Validator::make($input, $rules)->validateWithBag('updateProfileInformation');


        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }
        if ($user->role === 'agent') {
            $agent = $user->agent()->updateOrCreate([], [
                'agency' => $input['agency'] ?? null,
                'licence_no' => $input['licence_no'] ?? null,
                'experience' => $input['experience'] ?? null,
                'bio' => $input['bio'] ?? null,
            ]);
            $user->setRelation('agent', $agent);

            if (!empty($input['categories']) && is_array($input['categories'])) {
                $user->agent->categories()->sync($input['categories']);
            }
        }
        if (
            $input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $fields = [
                'name'    => $input['name'] ?? $user->name,
                'contact' => $input['contact'] ?? $user->contact,
                'email'   => $input['email'] ?? $user->email,
            ];
            if ($user->role !== 'agent' && isset($input['username'])) {
                $fields['username'] = $input['username'];
            }

            $user->forceFill($fields)->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'username' => $input['username'],
            'contact' => $input['contact'] ?? null,
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
