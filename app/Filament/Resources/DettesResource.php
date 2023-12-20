<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DettesResource\Pages;
use App\Filament\Resources\DettesResource\RelationManagers;
use App\Models\Dettes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DettesResource extends Resource
{
    protected static ?string $model = Dettes::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDettes::route('/'),
            'create' => Pages\CreateDettes::route('/create'),
            'edit' => Pages\EditDettes::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            DettesResource\Widgets\DetteWidget::class,
        ];
    }
}
