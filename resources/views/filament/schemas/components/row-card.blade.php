{{-- resources/views/filament/schemas/components/row-card.blade.php --}}
@php
    $classes = $getCardClass();
    $gap     = $getGridGap();  // renomeado
    $name    = $getNameProperty();
    $meta    = $getMetaProperty();
@endphp

<div class="{{ $classes }}">
    <div style="display: flex; justify-content: space-between; border: 1px solid #cccccc71; padding: 1rem; border-radius: 0.5rem;" >
        <div style="display: flex; flex-direction: column">
            <span><strong>{{ $name }}</strong></span>
            <span>Preço base de 1 {{ $meta }}</span>
        </div>
        {{-- Renderiza o schema oficial passado em ->schema([...]) --}}
        {{ $getChildSchema() }}
    </div>
</div>
