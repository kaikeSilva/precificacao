<?php

namespace App\Filament\Schemas\Components;

use Closure;
use Filament\Schemas\Components\Component;

class RowCard extends Component
{
    protected string $view = 'filament.schemas.components.row-card';

    protected string|Closure|null $cardClass = null;
    protected int|Closure $gridGap = 4; // renomeado para evitar conflito com Component::gap()
    protected string $nameProperty = 'name';
    protected string $metaProperty = 'meta';
    
    public static function make(): static
    {
        return app(static::class);
    }

    public function cardClass(string|Closure|null $classes): static
    {
        $this->cardClass = $classes;
        return $this;
    }

    /** Renomeado de gap() -> gridGap() */
    public function gridGap(int|Closure $gap): static
    {
        $this->gridGap = $gap;
        return $this;
    }

    public function nameProperty(string $name): static
    {
        $this->nameProperty = $name;
        return $this;
    }

    public function metaProperty(string $meta): static
    {
        $this->metaProperty = $meta;
        return $this;
    }
    
    public function getCardClass(): string
    {
        return $this->evaluate($this->cardClass)
            ?? 'mb-3 rounded-xl border border-gray-200 p-4 bg-white hover:bg-gray-50 transition-colors
                dark:border-white/10 dark:bg-gray-900 dark:hover:bg-gray-800';
    }

    /** Getter correspondente ao novo nome */
    public function getGridGap(): int
    {
        return (int) $this->evaluate($this->gridGap);
    }

    public function getNameProperty(): string
    {
        return $this->evaluate($this->nameProperty);
    }

    public function getMetaProperty(): string
    {
        return $this->evaluate($this->metaProperty);
    }
}
