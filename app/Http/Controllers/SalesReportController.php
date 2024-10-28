<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SalesReportController extends Controller
{
    public function index()
    {
        // Obtener el usuario autenticado desde la base de datos
        $user = User::find(Auth::id());

        return view('reporte',compact('user'));
    }

    public function generateReport(Request $request)
    {
        // Validar el tipo de reporte
        $request->validate([
            'tipo' => 'required|string|in:diario,semanal,mensual,anual',
        ]);

        $tipo = $request->tipo;

        // Calcular las fechas según el tipo de reporte
        switch ($tipo) {
            case 'diario':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'semanal':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'mensual':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'anual':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            default:
                return redirect()->back()->with('error', 'Tipo de reporte no válido.');
        }

        // Obtener las ventas dentro del rango de fechas
        $sales = Sale::whereBetween('created_at', [$startDate, $endDate])->get();
        $totalSales = $sales->sum('total');

        // Generar PDF
        $pdf = FacadePdf::loadView('pdf.sales_report', compact('sales', 'totalSales', 'tipo'));

        // Guardar el PDF en una ruta específica
        $pdfName = 'reporte_' . $tipo . '_' . now()->format('YmdHis') . '.pdf';
        $pdfPath = storage_path('app/pdfs/' . $pdfName);
        $pdf->save($pdfPath);

        // Retornar la vista con los datos y el link al PDF
        $pdfUrl = asset('storage/app/pdfs/' . $pdfName);

        // Guarda el nombre del PDF en la sesión (opcional)
        session(['pdf_name' => $pdfName]);

        return view('reporte', compact('sales', 'totalSales', 'pdfUrl', 'tipo'));

    }

    public function showPdf($tipo)
    {
        // Obtener el nombre del PDF guardado en la sesión
        $pdfName = session('pdf_name');

        // Verifica que haya un nombre de PDF en la sesión
        if (!$pdfName) {
            return redirect()->route('sales.index')->with('error', 'No se ha generado un PDF.');
        }

        $path = storage_path("app/pdfs/{$pdfName}");

        // Verifica si el archivo existe
        if (!file_exists($path)) {
            return redirect()->route('sales.index')->with('error', 'El PDF no se encontró.');
        }

        // Retornar el archivo PDF para mostrarlo en el navegador
        return response()->file($path);
    }

}
