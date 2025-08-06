<?php

namespace App\Filament\Auth;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Filament\Pages\Auth\Register as AuthRegister;

class Register extends AuthRegister 
{
    protected function getForms(): array
    {
        return[
            'form' => $this->form(
                $this->makeForm()
                        ->schema([
                            $this->getNameFormComponent(),
                            $this->getEmailFormComponent(),
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                            TextInput::make('phone')
                                ->tel()
                                ->required()
                                ->label('Phone Number')
                                ->placeholder('Enter Your Phone Number'),
                            FileUpload::make('iamge')
                                ->label('Profile Picture')
                                ->columnSpanfull()
                                ->required()
                                ->image()
                                ->placeholder('Upload Your Profile Picture'),
                            FileUpload::make('scanijazah')
                                ->label('Scan of Certificate')
                                ->columnSpanfull()
                                ->required()
                                ->image()
                                ->placeholder('Upload Your last certificat/ijazah terakhir'),
                        ])
                        ->statePath('data'),
            )
        ];
    }

    protected function submit(): void
    {
        $data = $this->form->getState();
        $user = User::Create([
            'name' => $data('name'),
            'email' => $data('email'),
            'password' => Hash::make($data('name')),
            'phone' => $data('phone'),
            'image' => $data('image') ?? null,
            'scanijazah' => $data('scanijazah') ?? null,
        ]);

        Auth::login($user);
    }
}