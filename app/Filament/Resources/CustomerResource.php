<?php

namespace App\Filament\Resources;

use App\Filament\Exports\CustomerExporter;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use App\Models\Post;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Carbon\Carbon;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Facades\Mail;
use Filament\Infolists\Infolist;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Exports\ProductExporter;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('contact')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TimePicker::make('time')
                    ->required(),
                Forms\Components\TextInput::make('npeople')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('message')
                    ->maxLength(50),
                Select::make('status')
                    ->required()
                    ->options([
                        'Available' => 'Available',
                        'Cancelled' => 'Cancelled',

                    ])
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('contact')
                    ->label('contact number')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact')
                    ->label('contact number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->format('g:i A')),
                Tables\Columns\TextColumn::make('npeople')
                    ->numeric()
                    ->label('# of people')
                    ->sortable(),
                Tables\Columns\TextColumn::make('message')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()

                    ->color(fn(?string $state): string => match ($state) {
                        'Available' => 'success',
                        'Cancelled' => 'danger',
                        default => 'secondary'
                    })

            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'cancelled' => 'Cancelled',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                DeleteAction::make(),

            ])

            ->headerActions([
                ExportAction::make()
                    ->exporter(CustomerExporter::class)
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    ExportBulkAction::make()
                    ->exporter(CustomerExporter::class)
                ]),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
