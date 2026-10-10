<?php

namespace App\Filament\Resources\Modification\Subcontractors;

use App\Filament\Resources\Modification\Subcontractors\Pages\CreateSubcontractor;
use App\Filament\Resources\Modification\Subcontractors\Pages\EditSubcontractor;
use App\Filament\Resources\Modification\Subcontractors\Pages\ListSubcontractors;
use App\Filament\Resources\Modification\Subcontractors\Schemas\SubcontractorForm;
use App\Filament\Resources\Modification\Subcontractors\Tables\SubcontractorsTable;
use App\Models\Modification\Subcontractor;
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
class SubcontractorResource extends Resource
{
    protected static ?string $model = Subcontractor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Subcontractors';

    protected static string|\UnitEnum|null $navigationGroup = 'Modification lookups';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SubcontractorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubcontractorsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubcontractors::route('/'),
            'create' => CreateSubcontractor::route('/create'),
            'edit' => EditSubcontractor::route('/{record}/edit'),
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
