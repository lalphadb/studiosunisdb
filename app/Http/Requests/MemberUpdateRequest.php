<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('update', $this->route('member'));
    }

    public function rules(): array
    {
        $memberId = $this->route('member')->id ?? $this->route('member');
        
        return [
            'first_name' => ['sometimes', 'required', 'string', 'max:100'],
            'last_name' => ['sometimes', 'required', 'string', 'max:100'],
            'email' => ['sometimes', 'required', 'email', Rule::unique('members')->ignore($memberId)],
            'phone' => ['nullable', 'string', 'regex:/^[\d\s\-\(\)\+]+$/'],
            'birth_date' => ['sometimes', 'required', 'date', 'before:today'],
            'gender' => ['sometimes', 'required', Rule::in(['M', 'F', 'Other'])],
            
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'regex:/^[A-Z]\d[A-Z]\s?\d[A-Z]\d$/i'],
            'province' => ['nullable', 'string', 'max:2'],
            
            'emergency_contact_name' => ['nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['nullable', 'string'],
            'emergency_contact_relationship' => ['nullable', 'string', 'max:50'],
            
            'status' => ['sometimes', Rule::in(['active', 'inactive', 'suspended'])],
            'current_belt_id' => ['nullable', 'exists:belts,id'],
            
            'medical_notes' => ['nullable', 'string', 'max:1000'],
            'allergies' => ['nullable', 'array'],
            'allergies.*' => ['string', 'max:100'],
            'medications' => ['nullable', 'array'],
            'medications.*' => ['string', 'max:100'],
            
            'consent_photos' => ['boolean'],
            'consent_communications' => ['boolean'],
            
            'family_id' => ['nullable', 'exists:families,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required' => "L'adresse courriel est obligatoire.",
            'email.email' => "L'adresse courriel doit être valide.",
            'email.unique' => 'Cette adresse courriel est déjà utilisée.',
            'birth_date.required' => 'La date de naissance est obligatoire.',
            'birth_date.before' => 'La date de naissance doit être dans le passé.',
            'gender.required' => 'Le genre est obligatoire.',
            'postal_code.regex' => 'Le code postal doit être au format canadien.',
            'phone.regex' => 'Le numéro de téléphone contient des caractères invalides.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('postal_code')) {
            $this->merge([
                'postal_code' => strtoupper(str_replace(' ', '', $this->postal_code)),
            ]);
        }
        
        if ($this->has('consent_photos') || $this->has('consent_communications')) {
            $this->merge([
                'consent_date' => now(),
            ]);
        }
    }
}
