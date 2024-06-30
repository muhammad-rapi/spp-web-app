<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Components;
use Filament\Pages\SubNavigationPosition;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "Nama Wajib Diisi",
                    ])
                    ->placeholder('Nama Siswa...')
                    ->maxLength(255),
                Forms\Components\Select::make('class_id')
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "Kelas Wajib Dipilih",
                    ])
                    ->relationship('classes', 'class_code')
                    ->native(false),
                Forms\Components\DatePicker::make('birth_date')
                    ->native(false),
                Forms\Components\Radio::make('gender')
                    ->default('pria')
                    ->options([
                        'pria' => 'Pria',
                        'wanita' => 'Wanita',
                    ])
                    ->inline()
                    ->inlineLabel(false),
                Forms\Components\TextInput::make('nisn')
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "NISN Wajib Diisi",
                    ]),
                Forms\Components\TextInput::make('nis')
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "NIS Wajib Diisi",
                    ]),
                Forms\Components\FileUpload::make('image')
                    ->rules(['max:2048'])
                    ->acceptedFileTypes(['.jpg', '.jpeg', '.png'])
                    ->validationMessages([
                        'max' => "Ukuran Gambar Terlalu Besar, Maksimal 2 MB",
                    ])
                    ->image(),
                Forms\Components\Toggle::make('status')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->description(fn (Student $record): string => $record->classes->name),
                Tables\Columns\TextColumn::make('birth_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nis')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nisn')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->beforeStateUpdated(function (Student $record) {
                        Student::where('id', '==', $record->id)->update(['status' => true]);
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
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

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewStudent::class,
            Pages\EditStudent::class,
            Pages\StudentPayment::class,
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Siswa')
                    ->schema([
                        Components\Split::make([
                            Components\Grid::make(2)
                                ->schema([
                                    Components\Group::make([
                                        TextEntry::make('name'),
                                        TextEntry::make('classes.name'),
                                        TextEntry::make('birth_date'),
                                        TextEntry::make('gender')
                                    ]),
                                    Components\Group::make([
                                        IconEntry::make('status')
                                            ->boolean()
                                            ->label('Status'),
                                        TextEntry::make('nisn'),
                                        TextEntry::make('nis'),
                                    ]),
                                ]),
                            Components\ImageEntry::make('image')
                                ->hiddenLabel()
                                ->grow(false),
                        ])->from('xl'),
                    ])
            ]);
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'payment' => Pages\StudentPayment::route('/{record}/payment'),
        ];
    }
}
