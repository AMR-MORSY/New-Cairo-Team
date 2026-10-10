<?php

namespace App\Enums;

/**
 * The modification workflow. A closed set the application branches on, so it
 * lives in code rather than in a table - unlike subcontractors, projects,
 * requesters and actions, which users maintain.
 *
 * Adding a case means adding a match arm below; the match is exhaustive, so
 * a missing arm fails loudly instead of silently mis-classifying work.
 */
enum ModificationStatus: string
{
    case Done = 'Done';
    case IN_PROGRESS = 'In Progress';
    case WAITING_D6 = 'Waiting D6';
    case CANCELLED = 'Cancelled';

    /**
     * True once a quotation has been submitted.
     *
     * This is what the old `status_id == 1 || status_id == 3` comparisons
     * meant - see the message in CreateModification: "considered done or
     * waiting D6 after quotation submission".
     */
    public function isQuoted(): bool
    {
        return match ($this) {
            self::Done, self::WAITING_D6 => true,
            self::IN_PROGRESS, self::CANCELLED => false,
        };
    }

    public function isInProgress(): bool
    {
        return $this === self::IN_PROGRESS;
    }

    public function isDone(): bool
    {
        return $this === self::Done;
    }

    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    /** Statuses that carry a real final cost. */
    public static function quoted(): array
    {
        return array_values(array_filter(self::cases(), fn (self $s) => $s->isQuoted()));
    }

    /** @return array<int, string> backing values of the quoted statuses */
    public static function quotedValues(): array
    {
        return array_column(self::quoted(), 'value');
    }

    /**
     * Option list for selects, shaped like the lookup tables the other
     * dropdowns use so the same blade component works.
     *
     * @return array<int, array{id: string, name: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $s) => ['id' => $s->value, 'name' => $s->value],
            self::cases()
        );
    }
}
