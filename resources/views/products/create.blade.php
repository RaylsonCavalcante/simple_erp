<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Produtos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Mensagens de sucesso ou erro --}}
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

            {{-- Formulário de produto --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">{{ isset($product) ? 'Editar Produto' : 'Cadastrar Produto' }}</h2>

                <form method="POST" action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}">
                    @csrf
                    @if(isset($product))
                        @method('PUT')
                    @endif

                    <!-- Nome do produto -->
                    <div>
                        <x-input-label for="name" :value="__('Nome')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                      :value="old('name', $product->name ?? '')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Preço -->
                    <div class="mt-4">
                        <x-input-label for="price" :value="__('Preço')" />
                        <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price"
                                      :value="old('price', $product->price ?? '')" required />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button class="ms-4">
                            {{ isset($product) ? 'Atualizar' : 'Cadastrar' }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Lista de produtos --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Lista de Produtos</h2>

                <table class="min-w-full divide-y divide-gray-200 border w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nome</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Preço</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Data de Cadastro</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($products as $product)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $product->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">
								    {{ $product->created_at->format('d/m/Y H:i') }}
								</td>
                                <td class="px-4 py-2 text-sm text-gray-800 flex gap-2">
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning px-3 py-1 text-white text-sm rounded">
                                        Editar
                                    </a>

                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger px-3 py-1 text-white text-sm rounded">
                                            Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-start text-gray-500">Nenhum produto cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>