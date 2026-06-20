<?php
namespace App\Console\Commands;
use Illuminate\Bus\Batchable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Income;
use Carbon\Carbon;
class FetchIncomesCommand extends Command
{
    use Batchable;
    protected $signature = 'app:fetch-incomes {--dateFrom= : Start date} {--dateTo= : End date}';
    protected $description = 'Загружаем данные из внешнего API и записываем в локальную базу.';
    public function handle(): int
    {
        $dateFrom = $this->option('dateFrom') ?: Carbon::now()->format('Y-m-d');
        $dateTo = $this->option('dateTo') ?: Carbon::now()->format('Y-m-d');
        $page = 1;
        while (true) {
            $response = Http::get(env('API_HOST')."/api/incomes", [
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
                Income::updateOrCreate(['income_id' => $item['income_id']], [
                    'number' => $item['number'],
                    'date' => $item['date'],
                    'last_change_date' => $item['last_change_date'] ?? null,
                    'supplier_article' => $item['supplier_article'],
                    'tech_size' => $item['tech_size'],
                    'barcode' => $item['barcode'],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price'],
                    'date_close' => $item['date_close'],
                    'warehouse_name' => $item['warehouse_name'],
                    'nm_id' => $item['nm_id']
                ]);
            }
            $this->info("Page {$page} processed, records: ".count($data));
            $page++;
        }
        $this->info('Fetching completed.');
        return self::SUCCESS;
    }
}
