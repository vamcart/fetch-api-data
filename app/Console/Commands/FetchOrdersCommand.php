<?php

namespace App\Console\Commands;

use Illuminate\Bus\Batchable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Carbon\Carbon;

class FetchOrdersCommand extends Command
{
    use Batchable;
    protected $signature = 'app:fetch-orders {--dateFrom= : Start date} {--dateTo= : End date}';
    protected $description = 'Загружаем данные из внешнего API и записываем в локальную базу.';

    public function handle(): int
    {
        $dateFrom = $this->option('dateFrom') ?: Carbon::now()->format('Y-m-d');
        $dateTo = $this->option('dateTo') ?: Carbon::now()->format('Y-m-d');
        $page = 1;
        while (true) {
            $response = Http::get(env('API_HOST')."/api/orders", [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'page' => $page,
                'key' => env('API_KEY'),
                'limit' => 500
            ]);
            if ($response->failed()) {
                $this->error("HTTP error: {$response->status()}");
                return self::FAILURE;
            }
            $data = $response->json('data');
            if (empty($data)) break;
            foreach ($data as $item) {
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
            }
            $this->info("Page {$page} processed, records: " . count($data));
            $page++;
        }
        $this->info('Fetching completed.');
        return self::SUCCESS;
    }
}
