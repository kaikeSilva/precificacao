<?php

namespace App\Filament\Resources\PriceScenarios\Pages;

use App\Filament\Resources\PriceScenarios\PriceScenarioResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\UnorderedList;
use Filament\Schemas\Components\Text;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use App\Models\RecipeItem;
use App\Models\RecipePackaging;
use App\Models\RecipeLaborRole;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class CreatePriceScenario extends CreateRecord
{
    protected static string $resource = PriceScenarioResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
    
    /** Persiste entre requests Livewire */
    public ?int $recipeId = null;

    /** Controla qual lista mostrar */
    public ?string $listaTipo = 'ingredients';

    /** Aqui ficarão os valores base digitados */
    public array $bases = [
        'ingredients' => [], // [ingredient_id => valor]
        'packagings'  => [], // [packaging_id  => valor]
        'labor'       => [], // [labor_role_id => valor]
    ];

    public function mount(): void
    {
        parent::mount();

        $this->recipeId = request()->has('recipe')
            ? (int) request()->query('recipe')
            : null;
    }

    public function content(Schema $schema): Schema
    {
        $recipeId = $this->recipeId;

        $ingredientRows = $packagingRows = $laborRows = [];

        if ($recipeId) {
            // Ingredientes: [id, name]
            $ingredients = RecipeItem::query()
                ->where('recipe_id', $recipeId)
                ->with('ingredient:id,name')
                ->get()
                ->map(fn ($ri) => [
                    'id'   => $ri->ingredient_id,
                    'name' => $ri->ingredient?->name,
                ])
                ->filter(fn ($i) => filled($i['id']) && filled($i['name']))
                ->values()
                ->all();

            foreach ($ingredients as $row) {
                $ingredientRows[] = Grid::make(12)->schema([
                    Text::make($row['name'])->columnSpan(8),
                    TextInput::make("bases.ingredients.{$row['id']}")
                        ->label('Valor base')
                        ->numeric()
                        ->step('0.0001')
                        ->placeholder('0,00')
                        ->live()
                        ->columnSpan(4),
                ]);
            }

            // Embalagens: [id, name]
            $packagings = RecipePackaging::query()
                ->where('recipe_id', $recipeId)
                ->with('packaging:id,name')
                ->get()
                ->map(fn ($rp) => [
                    'id'   => $rp->packaging_id,
                    'name' => $rp->packaging?->name,
                ])
                ->filter(fn ($i) => filled($i['id']) && filled($i['name']))
                ->values()
                ->all();

            foreach ($packagings as $row) {
                $packagingRows[] = Grid::make(12)->schema([
                    Text::make($row['name'])->columnSpan(8),
                    TextInput::make("bases.packagings.{$row['id']}")
                        ->label('Valor base')
                        ->numeric()
                        ->step('0.0001')
                        ->placeholder('0,00')
                        ->live()
                        ->columnSpan(4),
                ]);
            }

            // Mão de obra: [id, name]
            $laborRoles = RecipeLaborRole::query()
                ->where('recipe_id', $recipeId)
                ->with('laborRole:id,name')
                ->get()
                ->map(fn ($rlr) => [
                    'id'   => $rlr->labor_role_id,
                    'name' => $rlr->laborRole?->name,
                ])
                ->filter(fn ($i) => filled($i['id']) && filled($i['name']))
                ->values()
                ->all();

            foreach ($laborRoles as $row) {
                $laborRows[] = Grid::make(12)->schema([
                    Text::make($row['name'])->columnSpan(8),
                    TextInput::make("bases.labor.{$row['id']}")
                        ->label('Valor base')
                        ->numeric()
                        ->step('0.0001')
                        ->placeholder('0,00')
                        ->live()
                        ->columnSpan(4),
                ]);
            }
        }

        return $schema->components([
            Section::make('Visão da Receita')->schema([
                // Controla qual grupo ver
                Select::make('listaTipo')
                    ->statePath('listaTipo')
                    ->label('Mostrar')
                    ->options([
                        'ingredients' => 'Ingredientes',
                        'packagings'  => 'Embalagens',
                        'labor'       => 'Mão de obra',
                    ])
                    ->dehydrated(false)
                    ->live(),

                // Ingredientes
                Section::make(fn () => 'Ingredientes (' . count($ingredientRows) . ')')
                    ->schema(
                        ! empty($ingredientRows)
                            ? $ingredientRows
                            : [Text::make('Nenhum ingrediente cadastrado.')]
                    )
                    ->visible(fn (Get $get) => $get('listaTipo') === 'ingredients'),

                // Embalagens
                Section::make(fn () => 'Embalagens (' . count($packagingRows) . ')')
                    ->schema(
                        ! empty($packagingRows)
                            ? $packagingRows
                            : [Text::make('Nenhuma embalagem cadastrada.')]
                    )
                    ->visible(fn (Get $get) => $get('listaTipo') === 'packagings'),

                // Mão de obra
                Section::make(fn () => 'Mão de obra (' . count($laborRows) . ')')
                    ->schema(
                        ! empty($laborRows)
                            ? $laborRows
                            : [Text::make('Nenhum perfil de mão de obra cadastrado.')]
                    )
                    ->visible(fn (Get $get) => $get('listaTipo') === 'labor'),

                    // Barra de ações
                Grid::make()
                ->schema([
                    Action::make('salvar')
                        ->label('Salvar cenário')
                        ->icon(Heroicon::Check)
                        ->color('primary')
                        ->submit('form')           // 🔑 submete o form principal da página
                        ->keyBindings(['mod+s'])   // opcional: atalho de teclado
                        ->extraAttributes(['class' => 'ml-auto']), // empurra p/ direita
                ]),
            ]),
        ]);
    }

    /** Se quiser injetar o recipe_id no form principal */
    protected function beforeFill(): void
    {
        $this->form->fill([
            'recipe_id' => $this->recipeId,
        ]);
    }

    /** Aqui você consegue ler TODOS os "valor base" em $this->bases */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Exemplo: salvar no JSON do cenário
        $data['overrides_json'] = [
            'bases' => $this->bases,
        ];

        // Garante também a FK obrigatória:
        $data['recipe_id'] = $this->recipeId;
        dd($data);
        return $data;
    }

}
