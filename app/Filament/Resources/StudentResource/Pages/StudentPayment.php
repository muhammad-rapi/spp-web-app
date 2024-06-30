<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Infolists\Components;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentPayment extends ManageRelatedRecords
{
    protected static string $resource = StudentResource::class;

    protected static string $relationship = 'payment';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function getNavigationLabel(): string
    {
        return 'Pembayaran Siswa';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_number'),
                Tables\Columns\TextColumn::make('amount_of_payment'),
                Tables\Columns\TextColumn::make('createdBy.name'),
                Tables\Columns\TextColumn::make('month'),
                Tables\Columns\TextColumn::make('year'),
                Tables\Columns\TextColumn::make('description')
                    ->default('Tidak ada Deskripsi'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color('success'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             Components\Section::make('Siswa')
    //                 ->schema([
    //                     Components\Split::make([
    //                         Components\Grid::make(2)
    //                             ->schema([
    //                                 Components\Group::make([
    //                                     TextEntry::make('name'),
    //                                     TextEntry::make('classes.name'),
    //                                     TextEntry::make('birth_date'),
    //                                     TextEntry::make('gender')
    //                                 ]),
    //                                 Components\Group::make([
    //                                     IconEntry::make('status')
    //                                         ->boolean()
    //                                         ->label('Status'),
    //                                     TextEntry::make('nisn'),
    //                                     TextEntry::make('nis'),
    //                                 ]),
    //                             ]),
    //                         Components\ImageEntry::make('image')
    //                             ->hiddenLabel()
    //                             ->grow(false),
    //                     ])->from('xl'),
    //                 ])
    //         ]);
    // }
    public function isReadOnly(): bool
    {
        return true;
    }
}
