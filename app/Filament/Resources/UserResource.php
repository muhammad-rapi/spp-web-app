<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "Nama Wajib Diisi",
                    ])
                    ->placeholder('Nama User...')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->rules(['required', 'email', 'unique:users'])
                    ->validationMessages([
                        'required' => "Email Wajib Diisi",
                        'email' => "Email Tidak Valid",
                        'unique' => "Email Sudah Terdaftar",
                    ])
                    ->placeholder('Email User...')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->rules(['numeric', 'max:0000000000000'])
                    ->validationMessages([
                        'max' => 'No. HP Maksimal 13 Karakter',
                    ])
                    ->placeholder('No. HP User...'),
                Forms\Components\TextInput::make('password')
                    ->placeholder('Password User...')
                    ->password()
                    ->live()
                    ->rules(['required', 'min:8'])
                    ->validationMessages([
                        'min' => 'Password Minimal 8 Karakter',
                        'required' => "Password Wajib Diisi",
                    ]),
                Forms\Components\Select::make('role')
                    ->rules(['required', 'in:headmaster,operator,petugas tu'])
                    ->validationMessages([
                        'required' => "Role Wajib Dipilih",
                        'in' => "Role yang dipilih tidak valid",
                    ])
                    ->options([
                        'headmaster' => 'Headmaster',
                        'operator' => 'Operator',
                        'petugas tu' => 'Petugas TU',
                    ])
                    ->native(false),
                Forms\Components\Radio::make('gender')
                    ->options([
                        'pria' => 'Pria',
                        'wanita' => 'Wanita',
                    ])
                    ->inline()
                    ->inlineLabel(false),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->searchable()
                    ->color('info')
                    ->badge(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->default('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->beforeStateUpdated(function (User $record) {
                        User::where('id', '==', $record->id)->update(['is_active' => true]);
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('phone_number')
                    ->default('-'),
                TextEntry::make('role'),
                TextEntry::make('gender'),
                IconEntry::make('is_active')
                    ->boolean()
                    ->label('Status')
            ])
            ->columns(1)
            ->inlineLabel();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
