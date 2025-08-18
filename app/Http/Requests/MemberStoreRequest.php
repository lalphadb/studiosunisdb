<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create', \App\Models\Member::class);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:members,email'],
            'phone' => ['nullable', 'string', 'regex:/^[\d\s\-\(\)\+]+$/'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', Rule::in(['M', 'F', 'Other'])],
            
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'regex:/^[A-Z]\d[A-Z]\s?\d[A-Z]\d$/i'],
            'province' => ['nullable', 'string', 'max:2'],
            
            'emergency_contact_name' => ['required_if:birth_date,<,'.now()->subYears(18)->format('Y-m-d'), 'nullable', 'string', 'max:100'],
            'emergency_contact_phone' => ['required_with:emergency_contact_name', 'nullable', 'string'],
            'emergency_contact_relationship' => ['required_with:emergency_contact_name', 'nullable', 'string', 'max:50'],
            
            'status' => ['sometimes', Rule::in(['active', 'inactive', 'suspended'])],
            'current_belt_id' => ['nullable', 'exists:belts,id'],
            'registration_date' => ['nullable', 'date', 'before_or_equal:today'],
            
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
            'postal_code.regex' => 'Le code postal doit être au format canadien (ex: H1A 1A1).',
            'phone.regex' => 'Le numéro de téléphone contient des caractères invalides.',
            'emergency_contact_name.required_if' => 'Le contact d\'urgence est obligatoire pour les mineurs.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'postal_code' => strtoupper(str_replace(' ', '', $this->postal_code ?? '')),
            'registration_date' => $this->registration_date ?? now()->format('Y-m-d'),
            'consent_date' => ($this->consent_photos || $this->consent_communications) ? now() : null,
        ]);
    }
}
