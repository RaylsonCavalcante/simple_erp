<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Minhas Vendas') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Lista de vendas --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Lista de Vendas</h2>

                <table class="min-w-full divide-y divide-gray-200 border w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">#</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Código</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Cliente</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Data de Registro</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($sales as $sale)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $sale->client->id }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $sale->client->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800">{{ $sale->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 text-sm text-gray-800 flex gap-2">
                                    {{-- Editar --}}
                                    <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning px-3 py-1 text-white text-sm rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                          <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                          <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </a>

                                    {{-- Excluir --}}
                                    <form action="{{ route('sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta venda?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger px-3 py-1 text-white text-sm rounded" style="align-items: center;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 20 20">
                                              <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                            </svg>
                                        </button>
                                    </form>
                                    {{-- PDF --}}
                                    <a href="{{ route('sales.pdf', $sale->id) }}" target="_blank" class="btn btn-info px-3 py-1 text-white text-sm rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                                          <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1"/>
                                          <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-start text-gray-500">Nenhuma venda ainda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>