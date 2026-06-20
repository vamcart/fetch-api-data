<?php

namespace App\Console\Commands;

use Illuminate\Bus\Batchable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Sale;
use Carbon\Carbon;

class FetchSalesCommand extends Command
{
    use Batchable;
    protected $signature = 'app:fetch-sales {--dateFrom= : Start date} {--dateTo= : End date}';
    protected $description = 'Загружаем данные из внешнего API и записываем в локальную базу.';

    public function handle(): int
    {
        $dateFrom = $this->option('dateFrom') ?: Carbon::now()->format('Y-m-d');
        $dateTo = $this->option('dateTo') ?: Carbon::now()->format('Y-m-d');
        $page = 1;
        while (true) {
            $response = Http::get(env('API_HOST')."/api/sales", [
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
                Sale::updateOrCreate(['g_number' => $item['g_number']], [
                    'date' => $item['date'],
                    'last_change_date' => $item['last_change_date'] ?? null,
                    'supplier_article' => $item['supplier_article'],
                    'tech_size' => $item['tech_size'],
                    'barcode' => $item['barcode'],
                    'total_price' => $item['total_price'],
                    'discount_percent' => $item['discount_percent'],
                    'is_supply' => $item['is_supply'],
                    'is_realization' => $item['is_realization'],
                    'promo_code_discount' => $item['promo_code_discount'],
                    'warehouse_name' => $item['warehouse_name'],
                    'country_name' => $item['country_name'],
                    'oblast_okrug_name' => $item['oblast_okrug_name'],
                    'region_name' => $item['region_name'],
                    'income_id' => $item['income_id'],
                    'sale_id' => $item['sale_id'],
                    'odid' => $item['odid'],
                    'spp' => $item['spp'],
                    'for_pay' => $item['for_pay'],
                    'finished_price' => $item['finished_price'],
                    'price_with_disc' => $item['price_with_disc'],
                    'nm_id' => $item['nm_id'],
                    'subject'        => $item['subject'],
                    'category'       => $item['category'],
                    'brand'          => $item['brand'],
                    'is_storno'      => $item['is_storno']                    
                ]);
            }
            $this->info("Page {$page} processed, records: " . count($data));
            $page++;
        }
        $this->info('Fetching completed.');
        return self::SUCCESS;
    }
}
