<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Validation\Rules\Password;

class Register extends BaseRegister
{
    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Full Name')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Username')
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel())
            ->alphaDash();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email address')
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(Password::default())
            ->showAllValidationMessages()
            ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
            ->same('passwordConfirmation')
            ->validationAttribute('password');
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label('Confirm password')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }

    protected function getPlaceOfBirthFormComponent(): Component
    {
        return TextInput::make('place_of_birth')
            ->label('Tempat Lahir')
            ->maxLength(255)
            ->required();
    }

    protected function getCityFormComponent(): Component
    {
        return TextInput::make('city')
            ->label('Kota')
            ->maxLength(255)
            ->required();
    }

    protected function getDateOfBirthFormComponent(): Component
    {
        return DatePicker::make('date_of_birth')
            ->label('Tanggal Lahir')
            ->native(false)
            ->displayFormat('d/m/Y')
            ->required();
    }

    protected function getPhoneNumberFormComponent(): Component
    {
        return TextInput::make('phone_number')
            ->label('No. Handphone / WhatsApp')
            ->tel()
            ->maxLength(20);
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getUsernameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getPlaceOfBirthFormComponent(),
                $this->getCityFormComponent(),
                $this->getDateOfBirthFormComponent(),
                $this->getPhoneNumberFormComponent(),
            ]);
    }

    protected function mutateFormDataBeforeRegister(array $data): array
    {
        // Remove password confirmation before creating user
        unset($data['passwordConfirmation']);

        return $data;
    }
}
