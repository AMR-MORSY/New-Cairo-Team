<?php

namespace App\Filament\Resources\Modification\Requesters;

use App\Filament\Resources\Modification\Requesters\Pages\CreateRequester;
use App\Filament\Resources\Modification\Requesters\Pages\EditRequester;
use App\Filament\Resources\Modification\Requesters\Pages\ListRequesters;
use App\Filament\Resources\Modification\Requesters\Schemas\RequesterForm;
use App\Filament\Resources\Modification\Requesters\Tables\RequestersTable;
use App\Models\Modification\Requester;
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
class RequesterResource extends Resource
{
    protected static ?string $model = Requester::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Requesters';

    protected static string|\UnitEnum|null $navigationGroup = 'Modification lookups';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return RequesterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RequestersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRequesters::route('/'),
            'create' => CreateRequester::route('/create'),
            'edit' => EditRequester::route('/{record}/edit'),
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
