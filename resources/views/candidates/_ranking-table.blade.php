@props([
    'candidats',
    'showOffre' => false,
    'showCheckboxes' => false,
])

@php
    $rows = $candidats->map(fn ($c) => [
        'id' => $c->id,
        'name' => $c->nom,
        'url' => $showOffre
            ? route('offres.candidats.show', [$c->offre_id, $c])
            : route('offres.candidats.show', [$c->offre, $c]),
        'offreTitle' => $c->offre?->titre ?? '',
        'score' => $c->analyse?->matching_score,
        'rec' => $c->analyse?->recommandation?->value,
        'recOrder' => match ($c->analyse?->recommandation?->value) {
            'convoquer' => 1,
            'attente' => 2,
            'rejeter' => 3,
            default => 99,
        },
        'experience' => $c->analyse?->annees_experience,
    ])->values();
@endphp

<div
    x-data="{
        rows: {{ Js::from($rows) }},
        sortColumn: 'score',
        sortDirection: 'desc',
        search: '',
        filterRec: '',

        get sortedRows() {
            let result = [...this.rows];

            if (this.search) {
                const q = this.search.toLowerCase();
                result = result.filter(r => r.name.toLowerCase().includes(q));
            }

            if (this.filterRec) {
                result = result.filter(r => r.rec === this.filterRec);
            }

            const col = this.sortColumn;
            const dir = this.sortDirection === 'asc' ? 1 : -1;

            result.sort((a, b) => {
                let valA, valB;

                if (col === 'name') {
                    valA = a.name.toLowerCase();
                    valB = b.name.toLowerCase();
                    return dir * valA.localeCompare(valB);
                }

                valA = a[col] ?? (col === 'recOrder' ? 99 : -1);
                valB = b[col] ?? (col === 'recOrder' ? 99 : -1);

                return dir * (valA - valB);
            });

            return result;
        },

        sort(col) {
            if (this.sortColumn === col) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = col;
                this.sortDirection = col === 'name' ? 'asc' : 'desc';
            }
        },

        scoreColor(score) {
            if (score === null || score < 0) return 'bg-gray-200 dark:bg-gray-600';
            if (score >= 70) return 'bg-green-500';
            if (score >= 40) return 'bg-yellow-500';
            return 'bg-red-500';
        },

        recBadge(rec) {
            const map = {
                'convoquer': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'attente': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                'rejeter': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            };
            return map[rec] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
        },

        recLabel(rec) {
            const labels = {
                'convoquer': '{{ __('À convoquer') }}',
                'attente': '{{ __('En attente') }}',
                'rejeter': '{{ __('À rejeter') }}',
            };
            return labels[rec] || '—';
        },

        sortIcon(col) {
            if (this.sortColumn !== col) return '';
            return this.sortDirection === 'asc' ? '↑' : '↓';
        },
    }"
    class="space-y-4"
>
    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
        <div class="flex gap-2">
            <select x-model="filterRec" @change="filterRec = $event.target.value" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ __('Toutes les recommandations') }}</option>
                <option value="convoquer">{{ __('À convoquer') }}</option>
                <option value="attente">{{ __('En attente') }}</option>
                <option value="rejeter">{{ __('À rejeter') }}</option>
            </select>
        </div>
        <div>
            <input
                x-model="search"
                type="text"
                placeholder="{{ __('Rechercher un candidat...') }}"
                class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    @if ($showCheckboxes)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-10">
                            {{ __('Sél.') }}
                        </th>
                    @endif
                    <th @click="sort('recOrder')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-100">
                        {{ __('Rang') }} <span x-text="sortIcon('recOrder')" class="ml-1"></span>
                    </th>
                    <th @click="sort('name')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-100">
                        {{ __('Nom') }} <span x-text="sortIcon('name')" class="ml-1"></span>
                    </th>
                    @if ($showOffre)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Offre') }}
                        </th>
                    @endif
                    <th @click="sort('score')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-100">
                        {{ __('Score') }} <span x-text="sortIcon('score')" class="ml-1"></span>
                    </th>
                    <th @click="sort('recOrder')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-100">
                        {{ __('Recommandation') }} <span x-text="sortIcon('recOrder')" class="ml-1"></span>
                    </th>
                    <th @click="sort('experience')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-100">
                        {{ __('Expérience') }} <span x-text="sortIcon('experience')" class="ml-1"></span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <template x-for="(row, index) in sortedRows" :key="row.id">
                    <tr>
                        @if ($showCheckboxes)
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <input type="checkbox" :value="row.id" x-model="$parent.selected" class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="index + 1"></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a :href="row.url" class="text-indigo-600 dark:text-indigo-400 hover:underline" x-text="row.name"></a>
                        </td>
                        @if ($showOffre)
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="row.offreTitle"></td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full" :style="'width: ' + (row.score || 0) + '%'" :class="scoreColor(row.score)"></div>
                                </div>
                                <span class="text-xs font-medium" x-text="row.score != null ? row.score + '%' : '—'"></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="recBadge(row.rec)" x-text="recLabel(row.rec)"></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="row.experience != null ? row.experience + ' an(s)' : '—'"></td>
                    </tr>
                </template>
                <tr x-show="sortedRows.length === 0">
                    <td :colspan="{{ ($showCheckboxes ? 1 : 0) + ($showOffre ? 6 : 5) }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                        {{ __('Aucun candidat trouvé.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
