<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\FOrms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Biodata extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.biodata';

    public $user;

    public ?array $data = [];

    public function mount(): void
    {
        $this->user = Auth::user();

        //inisiasi form dengan current data
        $this->form->fill([
            'name' =>$this->user->name,
            'email' =>$this->user->email,
            'phone' =>$this->user->phone,
            'image' =>$this->user->image,
            'scanijazah' =>$this->user->scanijazah,
        ]);
    }

    public function form(Form $form): form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('email')->required()->email(),
                        TextInput::make('password')
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->nullable(),
                        TextInput::make('phone')->required(),
                        FileUpload::make('image')->image()->columnSpanFull(),
                        FileUpload::make('scanijazah')->required()->columnSpanFull(),
                    ])
            ])->statePath('data');
    }

    public function edit(): void
    {
        //validasi form data
        $validatedData = $this->form->getState();

        // update the user's detail
        // name/email/phone
        $this->user->name = $validatedData['name']; 
        $this->user->email = $validatedData['email']; 
        $this->user->phone = $validatedData['phone']; 

        if(empty($validatedData['password'])){
            $this->user->password = Hash::make($validatedData['password']);
        }

        if(isset($validatedData['image'])){
            if($this->user->image){
                Storage::delete($this->user->image);
            }
            $this->user->image = $validatedData['image'];
        }

        if(isset($validatedData['scanijazah'])){
            if($this->user->scanijazah){
                Storage::delete($this->user->scanijazah);
            }
            $this->user->scanijazah = $validatedData['scanijazah'];
        }
        $this->user->save();

        Notification::make()
            ->title('Biodata Update')
            ->success()
            ->body("Your Biodata gas been succesfully updated")
            ->send();
    }
}
