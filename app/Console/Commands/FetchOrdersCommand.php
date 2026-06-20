<?php

namespace App\Console\Commands;

use Illuminate\Bus\Batchable;
use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;
use App\Console\Traits\PaginatedFetcher;

class FetchOrdersCommand extends Command
{
    use Batchable, PaginatedFetcher;
    protected $signature = 'app:fetch-orders {--dateFrom= : Start date} {--dateTo= : End date}';
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
            $this->fetchPaginated('/api/orders', $baseParams, function ($item) {
                Order::updateOrCreate(['g_number' => $item['g_number']], [
                    'date' => $item['date'],
                    'last_change_date' => $item['last_change_date'] ?? null,
                    'supplier_article' => $item['supplier_article'],
                    'tech_size' => $item['tech_size'],
                    'barcode' => $item['barcode'],
                    'total_price' => $item['total_price'],
                    'discount_percent' => $item['discount_percent'],
                    'warehouse_name' => $item['warehouse_name'],
                    'oblast' => $item['oblast'],
                    'income_id' => $item['income_id'],
                    'odid' => $item['odid'],
                    'nm_id' => $item['nm_id'],
                    'subject' => $item['subject'],
                    'category' => $item['category'],
                    'brand' => $item['brand'],
                    'is_cancel' => $item['is_cancel'],
                    'cancel_dt' => $item['cancel_dt']
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
