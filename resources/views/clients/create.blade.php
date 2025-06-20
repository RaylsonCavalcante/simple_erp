<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clientes') }}
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

            {{-- Formulário de cadastro de cliente --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">{{ isset($client) ? 'Editar Cliente' : 'Cadastrar Cliente' }}</h2>

                <form method="POST" action="{{ isset($client) ? route('clients.update', $client) : route('clients.store') }}">
                    @csrf
                    @if(isset($client))
                        @method('PUT')
                    @endif

                    <!-- Nome -->
                    <div>
                        <x-input-label for="name" :value="__('Nome')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                      :value="old('name', $client->name ?? '')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- CPF -->
                    <div class="mt-4">
                        <x-input-label for="cpf" :value="__('CPF')" />
                        <x-text-input id="cpf" class="block mt-1 w-full" type="text" name="cpf"
                                      :value="old('cpf', $client->cpf ?? '')" required />
                        <x-input-error :messages="$errors->get('cpf')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-primary-button class="ms-4">
                            {{ isset($client) ? 'Atualizar' : 'Cadastrar' }}
                        </x-primary-button>
                    </div>
                </form>

            </div>

            {{-- Tabela de clientes cadastrados --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Lista de Clientes</h2>

                <table class="min-w-full divide-y divide-gray-200 border w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nome</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">CPF</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($clients as $client)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $client->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $client->cpf }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800 flex gap-2">
                                    {{-- Editar --}}
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning px-3 py-1 text-white text-sm rounded">
                                        Editar
                                    </a>

                                    {{-- Excluir --}}
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este cliente?');">
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
                                <td colspan="3" class="px-4 py-2 text-start text-gray-500">Nenhum cliente cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>