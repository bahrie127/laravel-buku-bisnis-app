<?php

namespace App\Filament\Admin\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('contoh: Makanan & Minuman, Transportasi'),

                Select::make('type')
                    ->label('Tipe Kategori')
                    ->options([
                        'income' => 'Pemasukan',
                        'expense' => 'Pengeluaran',
                    ])
                    ->required()
                    ->native(false),

                Select::make('parent_id')
                    ->label('Kategori Induk')
                    ->options(function () {
                        return Category::where('user_id', Auth::id())
                            ->orderBy('name')
                            ->pluck('name', 'id');
                    })
                    ->placeholder('Pilih kategori induk (opsional)')
                    ->searchable()
                    ->preload()
                    ->nullable(),
            ]);
    }
}
