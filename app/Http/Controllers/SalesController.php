<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\DetailsSale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailsSales;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use PDF;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    // Método para mostrar todas las ventas
    public function index()
    {
        // Obtener todas las ventas de la tabla sales
        $sales = Sale::all();
        $sales = Sale::orderBy('created_at', 'desc')->get();
        // Obtener el usuario autenticado desde la base de datos
        $user = User::find(Auth::id());

        // Retornar la vista 'sales' y pasarle las ventas
        return view('sales', compact('sales','user'));
    }
    public function destroy($id)
    {
        // Buscar la venta por ID y eliminarla
        $sale = Sale::findOrFail($id);
        $sale->delete();

        // Redirigir con mensaje de éxito
        return redirect()->route('sales.index')->with('success', 'Venta eliminada con éxito.');
    }


    public function generatePDF($id)
    {
        // Obtener la venta por su ID
        $sale = Sale::findOrFail($id);
    
        // Obtener los detalles de la venta
        $details = DetailsSales::where('account_identifier', $id)->get();
    
        // Preparar los datos para la vista del PDF
        $data = [
            'sale' => $sale,
            'details' => $details,
        ];
    
        // Cargar la vista y pasarle los datos
        $pdf = FacadePdf::loadView('pdf.sale', $data);
    
        // Define la ruta donde se guardará el PDF
        $path = storage_path("app/pdfs/venta_{$id}.pdf");
    
        // Asegúrate de que el directorio exista
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
    
        // Guardar el PDF en la ruta definida
        $pdf->save($path);
    
        // Retornar el PDF para mostrarlo en una nueva pestaña
        return response()->file($path);
    }
    public function showPdf($id)
    {
        // Define la ruta del PDF
        $path = storage_path("app/pdfs/venta_{$id}.pdf");
    
        // Verifica si el archivo existe
        if (!file_exists($path)) {
            return redirect()->route('sales.index')->with('error', 'El PDF no se encontró.');
        }
    
        // Retornar el archivo PDF para mostrarlo en el navegador
        return response()->file($path);
    }
}
