<?php

namespace App\Jobs;

use App\Models\SearchResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PerformSearchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $query;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 検索処理を実行
        $results = SomeSearchService::search($this->query);

        // 検索結果を保存
        SearchResult::create([
            'query' => $this->query,
            'results' => json_encode($results)
        ]);
    }
}
