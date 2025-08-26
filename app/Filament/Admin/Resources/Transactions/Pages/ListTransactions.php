<?php

namespace App\Filament\Admin\Resources\Transactions\Pages;

use App\Filament\Admin\Resources\Transactions\TransactionResource;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('downloadReport')
                ->label('Unduh Laporan')
                ->icon(Heroicon::DocumentArrowDown)
                ->color('success')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('date_from')
                        ->label('Dari Tanggal'),
                    \Filament\Forms\Components\DatePicker::make('date_to')
                        ->label('Sampai Tanggal'),
                ])
                ->action('downloadPdfReport'),
        ];
    }

    public function downloadPdfReport(array $data): \Symfony\Component\HttpFoundation\Response
    {
        // Get date filters from action form
        $dateFrom = $data['date_from'] ?? null;
        $dateTo = $data['date_to'] ?? null;

        // Get all transactions for current user with date filters
        $query = Transaction::where('user_id', Auth::id())
            ->with(['account', 'category'])
            ->orderBy('date', 'desc');

        // Apply date filters if they exist
        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $transactions = $query->get();

        // Calculate summary data
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = abs($transactions->where('type', 'expense')->sum('amount'));
        $netBalance = $totalIncome - $totalExpenses;

        // Get date range for display
        if ($dateFrom && $dateTo) {
            $startDate = \Carbon\Carbon::parse($dateFrom)->format('d M Y');
            $endDate = \Carbon\Carbon::parse($dateTo)->format('d M Y');
        } elseif ($dateFrom) {
            $startDate = \Carbon\Carbon::parse($dateFrom)->format('d M Y');
            $endDate = $transactions->max('date')?->format('d M Y') ?? 'Hari ini';
        } elseif ($dateTo) {
            $startDate = $transactions->min('date')?->format('d M Y') ?? 'Awal';
            $endDate = \Carbon\Carbon::parse($dateTo)->format('d M Y');
        } else {
            $startDate = $transactions->min('date')?->format('d M Y') ?? 'Tidak ada transaksi';
            $endDate = $transactions->max('date')?->format('d M Y') ?? 'Tidak ada transaksi';
        }

        // Generate PDF
        $pdf = Pdf::loadView('reports.transactions-pdf', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'netBalance' => $netBalance,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        $filename = 'transactions-report-'.now()->format('Y-m-d').'.pdf';

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }
}
