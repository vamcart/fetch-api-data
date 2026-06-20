<?php
namespace App\Console\Commands;
use Illuminate\Bus\Batchable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Stock;
class FetchStocksCommand extends Command
{
    use Batchable;
    protected $signature = 'app:fetch-stocks {--dateFrom= : Start date} {--dateTo= : End date}';
    protected $description = 'Fetch stocks from external API and store in database';
    public function handle(): int
    {
        $dateFrom = $this->option('dateFrom') ?: '2026-06-20';
        $dateTo = $this->option('dateTo') ?: '2026-06-20';
        $page = 1;
        while (true) {
            $response = Http::get(env('API_HOST')."/api/stocks", [
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
            }
            $this->info("Page {$page} processed, records: " . count($data));
            $page++;
        }
        $this->info('Fetching completed.');
        return self::SUCCESS;
    }
}
