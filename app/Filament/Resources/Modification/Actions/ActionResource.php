<?php

namespace App\Filament\Resources\Modification\Actions;

use App\Filament\Resources\Modification\Actions\Pages\CreateAction;
use App\Filament\Resources\Modification\Actions\Pages\EditAction;
use App\Filament\Resources\Modification\Actions\Pages\ListActions;
use App\Filament\Resources\Modification\Actions\Schemas\ActionForm;
use App\Filament\Resources\Modification\Actions\Tables\ActionsTable;
use App\Models\Modification\Action;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Lookup data the modification forms pick from. Rows are added and retired
 * here rather than in a migration, because they change with the business.
 */
class ActionResource extends Resource
{
    protected static ?string $model = Action::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Actions';

    protected static string|\UnitEnum|null $navigationGroup = 'Modification lookups';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ActionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActions::route('/'),
            'create' => CreateAction::route('/create'),
            'edit' => EditAction::route('/{record}/edit'),
        ];
    }

    /** Retiring an entry is a toggle; deleting one would cascade. */
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
