<?php

namespace App\Models;

use App\Models\Traits\HasLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Filament\Tables\Columns\TextColumn;

class Supplier extends Model
{
    use HasLocation;

    public static function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->maxLength(255),
            TextInput::make('phone_number')
                ->tel()
                ->required()
                ->maxLength(255),
            TextInput::make('address')
                ->maxLength(255),
            Select::make('province_id')
                ->label('Provinsi')
                ->searchable()
                ->live()
                ->options(Province::pluck('name', 'code'))
                ->afterStateUpdated(function (callable $set) {
                    $set('city_id', null);
                    $set('district_id', null);
                    $set('village_id', null);
                }),
            Select::make('city_id')
                ->label('Kota')
                ->searchable()
                ->live()
                ->preload()
                ->options(function (callable $get) {
                    return City::where('province_code', $get('province_id'))->pluck('name', 'code');
                })
                ->required(),
            Select::make('district_id')
                ->label('District')
                ->searchable()
                ->live()
                ->preload()
                ->options(function (callable $get) {
                    return District::where('city_code', $get('city_id'))->pluck('name', 'code');
                })
                ->live()
                ->afterStateUpdated(function (callable $set) {
                    $set('village_id', null);
                }),

            Select::make('village_id')
                ->label('Village')
                ->searchable()
                ->live()
                ->preload()
                ->options(function (callable $get) {
                    return Village::where('district_code', $get('district_id'))->pluck('name', 'code');
                }),
            TextInput::make('remarks')
                ->maxLength(255),
        ];
    }

    public static function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->searchable(),
            TextColumn::make('email')
                ->searchable(),
            TextColumn::make('phone_number')
                ->searchable(),
            TextColumn::make('address')
                ->searchable(),
            TextColumn::make('province.name')
                ->numeric()
                ->sortable(),
            TextColumn::make('city.name')
                ->numeric()
                ->sortable(),
            TextColumn::make('district.name')
                ->numeric()
                ->sortable(),
            TextColumn::make('village.name')
                ->numeric()
                ->sortable(),
            TextColumn::make('remarks')
                ->searchable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
