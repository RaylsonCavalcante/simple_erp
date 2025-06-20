<x-app-layout>

    <script>
        window.saleItemsFromBackend = @json($itemsFormatted);
        window.parcelsFromBackend = @json($parcelsFormatted);
    </script>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Venda') }}
        </h2>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Mensagens --}}
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <ul class="list-disc ps-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulário de venda para edição --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form id="form-sale-edit">
                    <input type="hidden" id="sale_id" value="{{ $sale->id }}">

                    <div class="mb-4">
                        <x-input-label for="client_id" :value="__('Cliente')" />
                        <select name="client_id" id="client_id" class="block mt-1 w-full border-gray-300 rounded">
                            <option value="">-- Selecione um cliente --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" data-name="{{ $client->name }}" data-cpf="{{ $client->cpf }}"
                                    {{ (old('client_id', $sale->client_id) == $client->id) ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                    </div>

                    {{-- Dados do cliente --}}
                    <div id="client-info" class="bg-gray-50 p-4 rounded border text-sm text-gray-800 {{ $sale->client ? '' : 'hidden' }}">
                        <p><strong>CLIENTE SELECIONADO</strong></p>
                        <p><strong>Nome:</strong> <span id="info-name">{{ $sale->client->name ?? '' }}</span></p>
                        <p><strong>CPF:</strong> <span id="info-cpf">{{ $sale->client->cpf ?? '' }}</span></p>
                    </div>

                    {{-- Itens --}}
                    <div class="mt-6">
                        <div id="sale-items-container" class="space-y-4">
                            <div class="flex gap-4 sale-item-row items-end">

                                <div class="flex-1">
                                    <x-input-label :value="__('Itens')" />
                                    <select name="products" id="products" class="product-select block w-full border-gray-300 rounded" required>
                                        <option value="">-- Selecione Produto --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label :value="__('Qtd')" />
                                    <input type="number" id="quantity" name="quantities" class="quantity-input block w-24 border-gray-300 rounded" min="1" value="1" required>
                                </div>

                                <div>
                                    <x-input-label :value="__('Valor Unitário')" />
                                    <input type="text" id="price" class="price-unit block w-32 border-gray-300 rounded bg-gray-100" readonly>
                                </div>

                                <div>
                                    <x-input-label :value="__('Subtotal')" />
                                    <input type="text" id="subtotal" class="subtotal block w-32 border-gray-300 rounded bg-gray-100" readonly>
                                </div>

                                <div>
                                    <x-input-label :value="__('Add')" />
                                    <button type="button" id="add-item" class="btn btn-primary px-3 py-2 text-white text-sm rounded" style="margin-top: 10%;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                          <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Itens Adicionados --}}
                            <div class="mt-6">
                                <h3 class="font-semibold mb-2">Itens adicionados</h3>
                                <table class="min-w-full border divide-y divide-gray-200" id="items-table" style="width:100%;">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Produto</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Qtd</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Valor Unitário</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Subtotal</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Forma de Pagamento e Parcelas --}}
                    <div class="mt-6">
                        <div class="flex flex-col lg:flex-row gap-8">

                            <div class="flex flex-wrap gap-4 items-end flex-1">

                                <div class="flex flex-col w-full sm:w-48">
                                    <x-input-label for="payment_method" :value="__('Forma de Pagamento')" />
                                    <select name="payment_method" id="payment_method" class="border-gray-300 rounded">  
                                        <option value=""> -- Seleciona Forma de Pagamento -- </option>
                                        <option value="personalizado" {{ (old('payment_method', $sale->payment_method) == 'personalizado') ? 'selected' : '' }}>Personalizado</option>
                                    </select>
                                </div>

                                <div class="flex flex-col w-full sm:w-32">
                                    <x-input-label for="installments" :value="__('Parcelas')" />
                                    <input type="number" name="installments" id="installments" min="1" max="12" value="{{ old('installments', count($sale->parcels)) }}" class="border-gray-300 rounded" />
                                </div>

                                <div class="flex flex-col w-full sm:w-44">
                                    <x-input-label for="due_date" :value="__('Data Vencimento')" />
                                    <input type="date" id="due_date" class="border-gray-300 rounded" value="{{ old('due_date', optional($sale->parcels->first()) ? \Carbon\Carbon::parse($sale->parcels->first()->vencimento)->format('Y-m-d') : '') }}" />
                                </div>

                                <div class="flex flex-col w-full sm:w-36">
                                    <x-input-label for="parcel_value" :value="__('Valor Parcela')" />
                                    <input type="text" id="parcel_value" class="bg-gray-100 border-gray-300 rounded" readonly />
                                </div>

                                <div class="flex flex-col">
                                    <button type="button" id="gerarParcelas" class="btn btn-secondary px-3 py-2 text-white text-sm rounded">
                                        Gerar Parcelas
                                    </button>
                                </div>
                            </div>

                            <div id="parcels-container" class="flex-1" style="margin-left: 4%;">
                                <x-input-label for="parcel_value" :value="__('Parcelas')" />
                                <table class="min-w-full border divide-y divide-gray-200" id="parcels-table" style="width:100%;">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Vencimento</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Valor</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <br>

                    <div class="flex justify-end" id="valorTotal"></div>

                    <br>

                    {{-- Botão Atualizar e Cancelar --}}
                    <div class="flex justify-end">
                        <button type="button" id="cancelarEditar" class="btn btn-danger px-3 py-2 text-white text-sm rounded" style="margin-right:2%;">
                            Cancelar
                        </button>
                        <button type="button" id="atualizaVenda" class="btn btn-primary px-3 py-2 text-white text-sm rounded">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="spinnerAtualizar"></span>
                            Atualizar
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>