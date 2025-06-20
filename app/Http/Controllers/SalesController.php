<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\Parcel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with('client')->get();
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $products = Product::all();
        return view('dashboard', compact('clients', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $clientId = $request->input('client_id');
        $items = $request->input('items');
        $parcels = $request->input('parcels');

        if (!$clientId || empty($items) || empty($parcels)) {
            return response()->json(['success' => false, 'message' => 'Dados incompletos'], 422);
        }

        //CALCULA VALOR TOTAL
        $total = 0;
        foreach ($items as $item) {
            $total += floatval($item['subtotal'] ?? 0);
        }

        //SALVA A VENDA
        $sale = Sale::create([
            'client_id' => $clientId,
            'payment_method' => 'cartao',
            'total' => $total,
        ]);

        //SALVA ITEMS
        foreach ($items as $item) {
            $price = floatval(str_replace(',', '.', $item['price']));
            $sale->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $price,
            ]);
        }

        //SALVA PARCELAS
        foreach ($parcels as $parcel) {
            $valor = floatval(str_replace(',', '.', $parcel['valor']));
            $sale->parcels()->create([
                'numero' => $parcel['numero'],
                'vencimento' => $parcel['vencimento'],
                'valor' => $valor,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Venda salva com sucesso']);
    }


    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $sale = Sale::with(['client', 'items', 'parcels'])->findOrFail($sale->id);
        $clients = Client::all();
        $products = Product::all();

        $itemsFormatted = $sale->items->map(function($item) {
            return [
                'id' => $item->product_id,
                'name' => $item->product->name,
                'price' => number_format($item->price, 2, ',', ''),
                'quantity' => $item->quantity,
                'subtotal' => number_format($item->price * $item->quantity, 2, ',', ''),
            ];
        });

        $parcelsFormatted = $sale->parcels->map(function($parcel) {
            return [
                'numero' => $parcel->numero,
                'vencimento' => $parcel->vencimento,
                'valor' => number_format($parcel->valor, 2, ',', ''),
            ];
        });


        return view('sales.edit', compact('sale', 'clients', 'products', 'itemsFormatted', 'parcelsFormatted'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $clientId = $request->input('client_id');
        $items = $request->input('items');
        $parcels = $request->input('parcels');

        if (!$clientId || empty($items) || empty($parcels)) {
            return response()->json(['success' => false, 'message' => 'Dados incompletos'], 422);
        }

        //CALCULA O TOTAL
        $total = 0;
        foreach ($items as $item) {
            $total += floatval(str_replace(',', '.', $item['subtotal'] ?? 0));
        }

        //ATUALIZA A VENDA
        $sale->update([
            'client_id' => $clientId,
            'payment_method' => 'cartao',
            'total' => $total,
        ]);

        //REMOVE ITENS ANTIGOS
        $sale->items()->delete();

        // ADICIONA NOVOS ITENS
        foreach ($items as $item) {
            $price = floatval(str_replace(',', '.', $item['price']));
            $sale->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $price,
            ]);
        }

        //REMOVE PARCELAS ANTIGAS
        $sale->parcels()->delete();

        //ADICIONA NOVAS PARCELAS
        foreach ($parcels as $parcel) {
            $valor = floatval(str_replace(',', '.', $parcel['valor']));
            $sale->parcels()->create([
                'numero' => $parcel['numero'],
                'vencimento' => $parcel['vencimento'],
                'valor' => $valor,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Venda atualizada com sucesso']);
    }

    //DELETA VENDA
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Venda excluída com sucesso!');
    }

    // PDF
    public function generatePdf(Sale $sale)
    {
        $sale->load(['client', 'items.product', 'parcels']);

        $items = $sale->items->map(function ($item) {
            return [
                'name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => number_format($item->price, 2, ',', ''),
                'subtotal' => number_format($item->price * $item->quantity, 2, ',', ''),
            ];
        });
        $parcels = $sale->parcels->map(function ($parcel) {
            return [
                'numero' => $parcel->numero,
                'vencimento' => \Carbon\Carbon::parse($parcel->vencimento)->format('d/m/Y'),
                'valor' => number_format($parcel->valor, 2, ',', ''),
            ];
        });

        $pdf = Pdf::loadView('sales.pdf', [
            'sale' => $sale,
            'client' => $sale->client,
            'items' => $items,
            'parcels' => $parcels
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("venda-{$sale->id}.pdf");
        
        // Ou para forçar download:
        // return $pdf->download("venda-{$sale->id}.pdf");
    }
}
