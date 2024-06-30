<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use App\Models\PaymentHistoryLogs;
use App\Models\Student;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Infolists\Components;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        $months = [
            'januari' => 'Januari',
            'februari' => 'Februari',
            'maret' => 'Maret',
            'april' => 'April',
            'mei' => 'Mei',
            'juni' => 'Juni',
            'juli' => 'Juli',
            'agustus' => 'Agustus',
            'september' => 'September',
            'oktober' => 'Oktober',
            'november' => 'November',
            'desember' => 'Desember',
        ];

        $currentYear = Carbon::now()->year;
        $years = array_combine(
            $yearRange = range($currentYear, $currentYear + 2),
            $yearRange
        );

        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'nis')
                    ->placeholder('Ketikkan NIS')
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "Siswa Wajib Diisi",
                    ])
                    ->searchable()
                    ->native(false),
                Forms\Components\TextInput::make('amount_of_payment')
                    ->default(75000)
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "Nominal Pembayaran Wajib Diisi",
                    ])
                    ->numeric(),
                Forms\Components\Select::make('month')
                    ->label('Bulan')
                    ->multiple()
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "Bulan Wajib Diisi",
                    ])
                    ->options($months)
                    ->native(false),
                Forms\Components\Select::make('year')
                    ->label('Tahun')
                    ->rules(['required'])
                    ->validationMessages([
                        'required' => "Tahun Wajib Diisi",
                    ])
                    ->options($years)
                    ->native(false),
                Forms\Components\TextArea::make('description')
                    ->maxLength(255),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_of_payment')
                    ->money('IDR', locale: 'id')
                    // ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('month')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'lunas' => 'success',
                        'unpaid' => 'warning',
                    })
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->default('Tidak Ada Deskripsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Pembayaran')
                    ->schema([
                        Components\Split::make([
                            Components\Grid::make(2)
                                ->schema([
                                    Components\Group::make([
                                        TextEntry::make('payment_number'),
                                        TextEntry::make('amount_of_payment')
                                            ->money('IDR', locale: 'id'),
                                        TextEntry::make('createdBy.name'),
                                    ]),
                                    Components\Group::make([
                                        TextEntry::make('month'),

                                        TextEntry::make('year'),
                                        TextEntry::make('status')
                                            ->badge()
                                            ->color('success')
                                            ->label('Status'),
                                    ]),
                                ]),
                        ])->from('xl'),
                    ]),
                Components\Section::make('History Pembayaran')
                    ->schema([
                        Components\Split::make([
                            TextEntry::make('paymentHistoryLogs.note')
                                ->label('')
                                ->helperText(
                                    fn (Payment $record): string => 'Dibuat Oleh ' . $record->createdBy->name . ' | ' . $record->created_at . ' WIB'
                                ),
                        ])->from('xl'),
                    ]),
                Components\Section::make('Siswa')
                    ->schema([
                        Components\Grid::make(2)
                            ->schema([
                                // Components\Group::make([
                                //     Components\ImageEntry::make('student.image')
                                //         ->hiddenLabel()
                                //         ->size(100)
                                //         ->circular()
                                //         ->grow(false),
                                // ]),
                                Components\Group::make([
                                    TextEntry::make('student.name'),
                                    TextEntry::make('student.nisn')
                                        ->label('NISN Siswa'),
                                    TextEntry::make('student.nis')
                                        ->label('NIS Siswa'),
                                ]),
                            ])
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }
}
