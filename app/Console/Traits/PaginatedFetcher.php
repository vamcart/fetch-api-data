<?php
namespace App\Console\Traits;
use Illuminate\Support\Facades\Http;
trait PaginatedFetcher
{
    protected function fetchPaginated(string $endpoint, array $baseParams, callable $handler): void
    {
        $page = 1;
        while (true) {
            $response = Http::get(env('API_HOST').$endpoint, array_merge($baseParams, ['page' => $page]));
            if ($response->failed()) {
                throw new \Exception("HTTP error: {$response->status()}");
            }
            $data = $response->json('data');
            if (empty($data)) {
                break;
            }
            foreach ($data as $item) {
                $handler($item);
            }
            $this->info("Page {$page} processed, records: ".count($data));
            $page++;
        }
    }
}
