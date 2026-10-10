<?php

namespace App\Filament\Resources\Modification\Projects;

use App\Filament\Resources\Modification\Projects\Pages\CreateProject;
use App\Filament\Resources\Modification\Projects\Pages\EditProject;
use App\Filament\Resources\Modification\Projects\Pages\ListProjects;
use App\Filament\Resources\Modification\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Modification\Projects\Tables\ProjectsTable;
use App\Models\Modification\Project;
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
class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Projects';

    protected static string|\UnitEnum|null $navigationGroup = 'Modification lookups';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
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
