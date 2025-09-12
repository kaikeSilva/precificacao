{{-- resources/views/filament/resources/price-scenarios/pages/view-price-scenario.blade.php --}}
<x-filament-panels::page>
    {{-- Header actions (Edit etc.) --}}
    <x-slot name="headerActions">
        @foreach ($this->getHeaderActions() as $action)
            {{ $action }}
        @endforeach
    </x-slot>

    @php
        $fmt2 = fn ($v) => 'R$ ' . number_format((float) $v, 2, ',', '.');
        $fmt4 = fn ($v) => 'R$ ' . number_format((float) $v, 4, ',', '.');

        $tot   = $calc['totals']    ?? [];
        $prc   = $calc['pricing']   ?? [];
        $yield = $calc['yield']     ?? [];
        $bd    = $calc['breakdowns']?? [];
        $meta  = $calc['meta']      ?? [];

        $round = $prc['rounding'] ?? [];
    @endphp

    {{-- Cabeçalho / Resumo --}}
    <div class="mb-6 grid gap-4 md:grid-cols-3">
        <x-filament::section>
            <x-slot name="heading">{{ $scenario->name ?? 'Cenário' }}</x-slot>
            <div class="text-sm text-gray-600 dark:text-gray-300">
                Receita: <span class="font-medium">{{ $meta['recipe_name'] ?? $scenario->recipe?->name ?? '—' }}</span><br>
                Empresa: <span class="font-medium">{{ $meta['company_name'] ?? $scenario->company?->name ?? '—' }}</span><br>
                Margem:  <span class="font-medium">{{ number_format((float)($tot['margin_pct'] ?? 0), 2, ',', '.') }}%</span>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Preço total</x-slot>
            <div class="text-2xl font-semibold">{{ $fmt2($prc['price_total'] ?? 0) }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                (Antes do arredondamento: {{ $fmt2($prc['price_total_raw'] ?? 0) }})
                @php $d = (float)($round['total_delta'] ?? 0); @endphp
                <span class="{{ $d > 0 ? 'text-emerald-600' : ($d < 0 ? 'text-rose-600' : '') }}">
                    {{ $d === 0.0 ? '' : ' • Δ ' . $fmt2($d) }}
                </span>
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Preço por unidade</x-slot>
            <div class="text-2xl font-semibold">{{ $fmt2($prc['price_per_unit'] ?? 0) }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                (Antes do arredondamento: {{ $fmt2($prc['price_per_unit_raw'] ?? 0) }})
                @php $dppu = (float)($round['ppu_delta'] ?? 0); @endphp
                <span class="{{ $dppu > 0 ? 'text-emerald-600' : ($dppu < 0 ? 'text-rose-600' : '') }}">
                    {{ $dppu === 0.0 ? '' : ' • Δ ' . $fmt2($dppu) }}
                </span><br>
                Rendimento: <span class="font-medium">{{ $yield['qty'] ?? 1 }}</span> {{ $yield['unit'] ?? '' }}
            </div>
        </x-filament::section>
    </div>

    {{-- Composição dos custos (cards) --}}
    <x-filament::section>
        <x-slot name="heading">Composição de custos</x-slot>
        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-xl border p-4 dark:border-white/10">
                <div class="text-sm text-gray-500 dark:text-gray-400">Ingredientes</div>
                <div class="text-lg font-medium">{{ $fmt4($tot['ingredients'] ?? 0) }}</div>
            </div>
            <div class="rounded-xl border p-4 dark:border-white/10">
                <div class="text-sm text-gray-500 dark:text-gray-400">Embalagens</div>
                <div class="text-lg font-medium">{{ $fmt4($tot['packagings'] ?? 0) }}</div>
            </div>
            <div class="rounded-xl border p-4 dark:border-white/10">
                <div class="text-sm text-gray-500 dark:text-gray-400">Mão de obra</div>
                <div class="text-lg font-medium">{{ $fmt4($tot['labor'] ?? 0) }}</div>
            </div>
            <div class="rounded-xl border p-4 dark:border-white/10">
                <div class="text-sm text-gray-500 dark:text-gray-400">Rateios alocados</div>
                <div class="text-lg font-medium">{{ $fmt4($tot['allocated'] ?? 0) }}</div>
            </div>
        </div>
        <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">
            Subtotal: <span class="font-medium">{{ $fmt4($tot['subtotal'] ?? 0) }}</span>
        </div>
    </x-filament::section>

    {{-- Arredondamento (detalhes) --}}
    <div class="mt-6 grid gap-4 md:grid-cols-3">
        <x-filament::section>
            <x-slot name="heading">Arredondamento</x-slot>
            <div class="text-sm text-gray-600 dark:text-gray-300">
                Modo: <span class="font-medium">{{ $round['mode'] ?? '—' }}</span><br>
                Step: <span class="font-medium">{{ number_format((float)($round['step'] ?? 0.01), 2, ',', '.') }}</span><br>
                Total (orig → arred): <span class="font-medium">
                    {{ $fmt2($round['total_meta']['original'] ?? ($prc['price_total_raw'] ?? 0)) }}
                    →
                    {{ $fmt2($prc['price_total'] ?? 0) }}
                </span><br>
                PPU (orig → arred): <span class="font-medium">
                    {{ $fmt2($round['ppu_meta']['original'] ?? ($prc['price_per_unit_raw'] ?? 0)) }}
                    →
                    {{ $fmt2($prc['price_per_unit'] ?? 0) }}
                </span>
            </div>
        </x-filament::section>
    </div>

    {{-- Tabelas detalhadas --}}
    <div class="mt-6 grid gap-6">
        {{-- Ingredientes --}}
        <x-filament::section>
            <x-slot name="heading">Ingredientes ({{ count($bd['ingredients']['items'] ?? []) }})</x-slot>

            <div class="overflow-x-auto rounded-xl border dark:border-white/10">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th class="px-3 py-2 text-left">Ingrediente</th>
                            <th class="px-3 py-2 text-left">Unidade</th>
                            <th class="px-3 py-2 text-right">Qtd</th>
                            <th class="px-3 py-2 text-right">Perda (%)</th>
                            <th class="px-3 py-2 text-right">Fator</th>
                            <th class="px-3 py-2 text-right">Preço (fonte)</th>
                            <th class="px-3 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($bd['ingredients']['items'] ?? []) as $it)
                            @php
                                $price = $it['price'] ?? [];
                                $src   = $price['source'] ?? '—';
                                $srcLabel = ['override'=>'override','cached'=>'cached','zero'=>'zero'][$src] ?? $src;
                            @endphp
                            <tr class="border-t dark:border-white/10">
                                <td class="px-3 py-2">{{ $it['name'] }}</td>
                                <td class="px-3 py-2">{{ $it['unit'] ?? '—' }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format((float)($it['qty'] ?? 0), 4, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format((float)($it['loss_pct'] ?? 0), 2, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format((float)($it['factor'] ?? 1), 4, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right">
                                    {{ $fmt4($price['effective'] ?? 0) }}
                                    <span class="text-xs text-gray-500">({{ $srcLabel }})</span>
                                </td>
                                <td class="px-3 py-2 text-right">{{ $fmt4($it['line_total'] ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th colspan="6" class="px-3 py-2 text-right">Total</th>
                            <th class="px-3 py-2 text-right">{{ $fmt4($bd['ingredients']['total'] ?? 0) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-filament::section>

        {{-- Embalagens --}}
        <x-filament::section>
            <x-slot name="heading">Embalagens ({{ count($bd['packagings']['items'] ?? []) }})</x-slot>

            <div class="overflow-x-auto rounded-xl border dark:border-white/10">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th class="px-3 py-2 text-left">Embalagem</th>
                            <th class="px-3 py-2 text-left">Unidade</th>
                            <th class="px-3 py-2 text-right">Qtd</th>
                            <th class="px-3 py-2 text-right">Preço (fonte)</th>
                            <th class="px-3 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($bd['packagings']['items'] ?? []) as $it)
                            @php
                                $price = $it['price'] ?? [];
                                $src   = $price['source'] ?? '—';
                                $srcLabel = ['override'=>'override','cached'=>'cached','zero'=>'zero'][$src] ?? $src;
                            @endphp
                            <tr class="border-t dark:border-white/10">
                                <td class="px-3 py-2">{{ $it['name'] }}</td>
                                <td class="px-3 py-2">{{ $it['unit'] ?? '—' }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format((float)($it['qty'] ?? 0), 4, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right">
                                    {{ $fmt4($price['effective'] ?? 0) }}
                                    <span class="text-xs text-gray-500">({{ $srcLabel }})</span>
                                </td>
                                <td class="px-3 py-2 text-right">{{ $fmt4($it['line_total'] ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th colspan="4" class="px-3 py-2 text-right">Total</th>
                            <th class="px-3 py-2 text-right">{{ $fmt4($bd['packagings']['total'] ?? 0) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-filament::section>

        {{-- Mão de obra --}}
        <x-filament::section>
            <x-slot name="heading">Mão de obra ({{ count($bd['labor']['roles'] ?? []) }})</x-slot>

            <div class="mb-3 text-sm text-gray-600 dark:text-gray-300">
                Minutos considerados: <span class="font-medium">{{ $bd['labor']['minutes'] ?? 0 }}</span> •
                Σ custo/min: <span class="font-medium">{{ number_format((float)($bd['labor']['cost_per_min_sum'] ?? 0), 6, ',', '.') }}</span>
            </div>

            <div class="overflow-x-auto rounded-xl border dark:border-white/10">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th class="px-3 py-2 text-left">Papel</th>
                            <th class="px-3 py-2 text-right">Custo/h (fonte)</th>
                            <th class="px-3 py-2 text-right">Custo/min</th>
                            <th class="px-3 py-2 text-right">Minutos</th>
                            <th class="px-3 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (($bd['labor']['roles'] ?? []) as $it)
                            @php
                                $hc  = $it['hour_cost'] ?? [];
                                $src = $hc['source'] ?? '—';
                                $srcLabel = ['override'=>'override','cached'=>'cached','zero'=>'zero'][$src] ?? $src;
                            @endphp
                            <tr class="border-t dark:border-white/10">
                                <td class="px-3 py-2">{{ $it['name'] }}</td>
                                <td class="px-3 py-2 text-right">
                                    {{ $fmt4($hc['effective'] ?? 0) }}
                                    <span class="text-xs text-gray-500">({{ $srcLabel }})</span>
                                </td>
                                <td class="px-3 py-2 text-right">{{ number_format((float)($it['per_minute'] ?? 0), 6, ',', '.') }}</td>
                                <td class="px-3 py-2 text-right">{{ (int)($it['minutes_used'] ?? 0) }}</td>
                                <td class="px-3 py-2 text-right">{{ $fmt4($it['line_total'] ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-white/5">
                        <tr>
                            <th colspan="4" class="px-3 py-2 text-right">Total</th>
                            <th class="px-3 py-2 text-right">{{ $fmt4($bd['labor']['total'] ?? 0) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-filament::section>

        {{-- Rateios alocados --}}
        <x-filament::section>
            <x-slot name="heading">Rateios alocados (mês atual)</x-slot>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-xl border p-4 dark:border-white/10">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Período</div>
                    <div class="font-medium">
                        {{ $bd['allocated']['period']['start'] ?? '—' }} — {{ $bd['allocated']['period']['end'] ?? '—' }}
                    </div>
                </div>
                <div class="rounded-xl border p-4 dark:border-white/10">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Minutos / mês (config)</div>
                    <div class="font-medium">{{ $bd['allocated']['work_minutes_month'] ?? 0 }}</div>
                </div>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-3">
                <div class="rounded-xl border p-4 dark:border-white/10">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Fixos (mês)</div>
                    <div class="text-lg font-medium">{{ $fmt2($bd['allocated']['fixed_monthly'] ?? 0) }}</div>
                </div>
                <div class="rounded-xl border p-4 dark:border-white/10">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Variáveis (mês)</div>
                    <div class="text-lg font-medium">{{ $fmt2($bd['allocated']['variable_monthly'] ?? 0) }}</div>
                </div>
                <div class="rounded-xl border p-4 dark:border-white/10">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total (mês)</div>
                    <div class="text-lg font-medium">{{ $fmt2($bd['allocated']['total_monthly'] ?? 0) }}</div>
                </div>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-3">
                <div class="rounded-xl border p-4 dark:border-white/10">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Minutos da receita</div>
                    <div class="text-lg font-medium">{{ $bd['allocated']['minutes_recipe'] ?? 0 }}</div>
                </div>
                <div class="rounded-xl border p-4 dark:border-white/10">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Custo por minuto</div>
                    <div class="text-lg font-medium">
                        {{ number_format((float)($bd['allocated']['per_minute_rate'] ?? 0), 6, ',', '.') }}
                    </div>
                </div>
                <div class="rounded-xl border p-4 dark:border-white/10">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total alocado</div>
                    <div class="text-lg font-medium">{{ $fmt4($bd['allocated']['total'] ?? 0) }}</div>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
