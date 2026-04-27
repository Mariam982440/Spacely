@extends('layouts.architect')
@section('title', 'Créer un devis')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-serif text-2xl font-semibold text-stone-900">Nouveau devis</h1>
            <p class="text-stone-400 text-sm mt-1">
                Pour {{ $booking->clientProfile->user->name }}
                — {{ $booking->timeSlot->start_at->format('d/m/Y H:i') }}
            </p>
        </div>
        <a href="{{ route('architect.quotes.index') }}"
           class="flex items-center gap-2 px-4 py-2 border border-stone-200
                  text-stone-600 text-sm rounded-xl hover:bg-stone-50 transition">
            ← Retour
        </a>
    </div>

    <form method="POST" action="{{ route('architect.quotes.store', $booking) }}" id="quote-form">
        @csrf

        <div class="grid grid-cols-3 gap-6 items-start">

            {{-- ── Gauche : lignes du devis ── --}}
            <div class="col-span-2 flex flex-col gap-5">

                <div class="bg-white border border-stone-200 rounded-2xl p-6 shadow-sm">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-5">
                        Lignes du devis
                    </p>

                    {{-- En-tête colonnes --}}
                    <div class="grid grid-cols-12 gap-3 mb-3 px-1">
                        <p class="col-span-6 text-xs text-stone-400">Description</p>
                        <p class="col-span-2 text-xs text-stone-400">Qté</p>
                        <p class="col-span-2 text-xs text-stone-400">Prix unit. (MAD)</p>
                        <p class="col-span-2 text-xs text-stone-400">Total</p>
                    </div>

                    {{-- Lignes --}}
                    <div id="items-container" class="flex flex-col gap-3 mb-4">
                        {{-- Ligne 1 par défaut --}}
                        <div class="item-row grid grid-cols-12 gap-3 items-center">
                            <input type="text"
                                   name="items[0][description]"
                                   placeholder="ex: Consultation design"
                                   class="col-span-6 px-3 py-2 bg-stone-50 border border-stone-200
                                          rounded-xl text-sm outline-none focus:ring-2
                                          focus:ring-green-200 focus:border-green-400">
                            <input type="number"
                                   name="items[0][quantity]"
                                   value="1" min="1"
                                   oninput="updateTotal(this)"
                                   class="col-span-2 px-3 py-2 bg-stone-50 border border-stone-200
                                          rounded-xl text-sm outline-none focus:ring-2
                                          focus:ring-green-200 focus:border-green-400 text-center">
                            <input type="number"
                                   name="items[0][unit_price]"
                                   value="0" min="0" step="0.01"
                                   oninput="updateTotal(this)"
                                   class="col-span-2 px-3 py-2 bg-stone-50 border border-stone-200
                                          rounded-xl text-sm outline-none focus:ring-2
                                          focus:ring-green-200 focus:border-green-400 text-right">
                            <p class="col-span-2 text-sm font-medium text-stone-700 text-right
                                      row-total px-1">0.00</p>
                        </div>
                    </div>

                    {{-- Ajouter une ligne --}}
                    <button type="button"
                            onclick="addItem()"
                            class="flex items-center gap-2 text-sm text-green-700
                                   hover:text-green-900 font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Ajouter une ligne
                    </button>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3">
                    <button type="submit"
                            class="flex items-center gap-2 px-5 py-2.5 bg-green-700
                                   text-white text-sm font-medium rounded-xl
                                   hover:bg-green-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path d="M22 2L11 13M22 2L15 22l-4-9-9-4 20-7z"/>
                        </svg>
                        Envoyer le devis au client
                    </button>
                    <a href="{{ route('architect.quotes.index') }}"
                       class="px-5 py-2.5 border border-stone-200 text-stone-600
                              text-sm rounded-xl hover:bg-stone-50 transition">
                        Annuler
                    </a>
                </div>

            </div>

            {{-- ── Droite : récapitulatif ── --}}
            <div class="flex flex-col gap-4">

                {{-- Infos réservation --}}
                <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                        Réservation
                    </p>
                    <div class="flex flex-col gap-2.5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-stone-400">Client</span>
                            <span class="font-medium text-stone-700">
                                {{ $booking->clientProfile->user->name }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-stone-400">Date</span>
                            <span class="font-medium text-stone-700">
                                {{ $booking->timeSlot->start_at->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-stone-400">Horaire</span>
                            <span class="font-medium text-stone-700">
                                {{ $booking->timeSlot->start_at->format('H:i') }}
                                → {{ $booking->timeSlot->end_at->format('H:i') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-stone-400">Sujet</span>
                            <span class="font-medium text-stone-700 text-right max-w-[140px] truncate">
                                {{ $booking->subject }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Totaux --}}
                <div class="bg-white border border-stone-200 rounded-2xl p-5 shadow-sm">
                    <p class="text-xs font-medium tracking-widest text-stone-400 uppercase mb-4">
                        Récapitulatif
                    </p>

                    <div class="flex flex-col gap-2 text-sm mb-4">
                        <div class="flex justify-between">
                            <span class="text-stone-400">Total HT</span>
                            <span class="font-medium text-stone-700" id="summary-ht">0.00 MAD</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-stone-400">TVA (%)</span>
                            <input type="number"
                                   name="tva"
                                   value="{{ old('tva', 20) }}"
                                   min="0" max="100" step="0.1"
                                   oninput="recalculate()"
                                   class="w-20 px-2 py-1 bg-stone-50 border border-stone-200
                                          rounded-lg text-sm text-right outline-none
                                          focus:ring-2 focus:ring-green-200 focus:border-green-400">
                        </div>
                        <div class="flex justify-between">
                            <span class="text-stone-400">Montant TVA</span>
                            <span class="text-stone-500" id="summary-tva-amount">0.00 MAD</span>
                        </div>
                    </div>

                    <hr class="border-stone-100 mb-3">

                    <div class="flex justify-between items-center">
                        <span class="font-medium text-stone-800">Total TTC</span>
                        <span class="font-serif text-xl font-semibold text-green-800"
                              id="summary-ttc">0.00 MAD</span>
                    </div>
                </div>

            </div>
        </div>
    </form>

@endsection

@push('scripts')
<script>
    let itemCount = 1;

    function addItem() {
        const container = document.getElementById('items-container');
        const index = itemCount++;
        const div = document.createElement('div');
        div.className = 'item-row grid grid-cols-12 gap-3 items-center';
        div.innerHTML = `
            <input type="text" name="items[${index}][description]"
                   placeholder="ex: Fournitures"
                   class="col-span-6 px-3 py-2 bg-stone-50 border border-stone-200
                          rounded-xl text-sm outline-none focus:ring-2
                          focus:ring-green-200 focus:border-green-400">
            <input type="number" name="items[${index}][quantity]"
                   value="1" min="1" oninput="updateTotal(this)"
                   class="col-span-2 px-3 py-2 bg-stone-50 border border-stone-200
                          rounded-xl text-sm outline-none text-center focus:ring-2
                          focus:ring-green-200 focus:border-green-400">
            <input type="number" name="items[${index}][unit_price]"
                   value="0" min="0" step="0.01" oninput="updateTotal(this)"
                   class="col-span-2 px-3 py-2 bg-stone-50 border border-stone-200
                          rounded-xl text-sm outline-none text-right focus:ring-2
                          focus:ring-green-200 focus:border-green-400">
            <div class="col-span-2 flex items-center justify-between px-1">
                <span class="text-sm font-medium text-stone-700 row-total">0.00</span>
                <button type="button" onclick="removeItem(this)"
                        class="text-stone-300 hover:text-red-400 transition ml-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;
        container.appendChild(div);
    }

    function removeItem(btn) {
        btn.closest('.item-row').remove();
        recalculate();
    }

    function updateTotal(input) {
        const row   = input.closest('.item-row');
        const qty   = parseFloat(row.querySelector('[name*="quantity"]').value) || 0;
        const price = parseFloat(row.querySelector('[name*="unit_price"]').value) || 0;
        row.querySelector('.row-total').textContent = (qty * price).toFixed(2);
        recalculate();
    }

    function recalculate() {
        const rows  = document.querySelectorAll('.item-row');
        const tva   = parseFloat(document.querySelector('[name="tva"]').value) || 0;
        let totalHt = 0;

        rows.forEach(row => {
            const qty   = parseFloat(row.querySelector('[name*="quantity"]').value) || 0;
            const price = parseFloat(row.querySelector('[name*="unit_price"]').value) || 0;
            totalHt += qty * price;
        });

        const tvaAmount = totalHt * (tva / 100);
        const totalTtc  = totalHt + tvaAmount;

        document.getElementById('summary-ht').textContent         = totalHt.toFixed(2) + ' MAD';
        document.getElementById('summary-tva-amount').textContent = tvaAmount.toFixed(2) + ' MAD';
        document.getElementById('summary-ttc').textContent        = totalTtc.toFixed(2) + ' MAD';
    }
</script>
@endpush