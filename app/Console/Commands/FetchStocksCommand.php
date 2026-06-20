<?php
namespace App\Console\Commands;
use Illuminate\Bus\Batchable;
use Illuminate\Console\Command;
use App\Models\Stock;
use Carbon\Carbon;
use App\Console\Traits\PaginatedFetcher;

class FetchStocksCommand extends Command
{
    use Batchable, PaginatedFetcher;
    protected $signature = 'app:fetch-stocks {--dateFrom= : Start date} {--dateTo= : End date}';
    protected $description = 'Загружаем данные из внешнего API и записываем в локальную базу.';
    public function handle(): int
    {
        $dateFrom = $this->option('dateFrom') ?: Carbon::now()->format('Y-m-d');
        $dateTo = $this->option('dateTo') ?: Carbon::now()->format('Y-m-d');
        $baseParams = [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'key' => env('API_KEY'),
            'limit' => 500
        ];
        try {
            $this->fetchPaginated('/api/stocks', $baseParams, function ($item) {
                Stock::updateOrCreate(['nm_id' => $item['nm_id']], [
                    'date' => $item['date'],
                    'last_change_date' => $item['last_change_date'] ?? null,
                    'supplier_article' => $item['supplier_article'],
                    'tech_size' => $item['tech_size'],
                    'barcode' => $item['barcode'],
                    'quantity' => $item['quantity'],
                    'is_supply' => $item['is_supply'],
                    'is_realization' => $item['is_realization'],
                    'quantity_full' => $item['quantity_full'],
                    'warehouse_name' => $item['warehouse_name'],
                    'in_way_to_client' => $item['in_way_to_client'],
                    'in_way_from_client' => $item['in_way_from_client'],
                    'subject' => $item['subject'],
                    'category' => $item['category'],
                    'brand' => $item['brand'],
                    'sc_code' => $item['sc_code'],
                    'price' => $item['price'],
                    'discount' => $item['discount']
                ]);
            });
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }
        $this->info('Fetching completed.');
        return self::SUCCESS;
    }
}
