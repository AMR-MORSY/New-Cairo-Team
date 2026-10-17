<?php

namespace App\Filament\Resources\Site\SiteNoticeTypes;

use App\Filament\Resources\Site\SiteNoticeTypes\Pages\CreateSiteNoticeType;
use App\Filament\Resources\Site\SiteNoticeTypes\Pages\EditSiteNoticeType;
use App\Filament\Resources\Site\SiteNoticeTypes\Pages\ListSiteNoticeTypes;
use App\Filament\Resources\Site\SiteNoticeTypes\Schemas\SiteNoticeTypeForm;
use App\Filament\Resources\Site\SiteNoticeTypes\Tables\SiteNoticeTypesTable;
use App\Models\Site\SiteNoticeType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * The categories a site note can be filed under. Rows are added and retired
 * here rather than in a migration, because they change with the business.
 */
class SiteNoticeTypeResource extends Resource
{
    protected static ?string $model = SiteNoticeType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Notice types';

    protected static ?string $modelLabel = 'notice type';

    protected static string|\UnitEnum|null $navigationGroup = 'Site lookups';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SiteNoticeTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteNoticeTypesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteNoticeTypes::route('/'),
            'create' => CreateSiteNoticeType::route('/create'),
            'edit' => EditSiteNoticeType::route('/{record}/edit'),
        ];
    }

    /**
     * Retiring an entry is a toggle. site_notices.site_notice_type_id is a
     * cascading foreign key, so deleting a type would delete every notice
     * filed under it.
     */
    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
