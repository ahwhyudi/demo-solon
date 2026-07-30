<?php

namespace App\Console\Commands;

use App\Models\JobDivisiFormOrder;
use App\Models\StatusJobOps;
use Carbon\Carbon;
use Illuminate\Console\Command;

class OpsPendingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ops-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = JobDivisiFormOrder::where("kategori", "operasional")
            ->whereHas("statusJobOps", function ($query) {
                return $query->where("status", "Penugasan");
            })
            ->with("statusJobOps")
            ->get();

        $insertStatus = collect();
        foreach ($data as $key => $value) {
            $status = $value->statusJobOps->where("status", "Penugasan")->first();
            $status = $value->statusJobOps->last();

            if (!$status) {
                continue;
            }

            $deadline = Carbon::parse($status->created_at)->addDays((int)$value->lama_proses);

            if (Carbon::now() > $deadline) {
                $insertStatus->push([
                    "job_divisi_form_order_id" => $value->id,
                    "status" => "Terlewat",
                    "created_by" => 0,
                    "keterangan" => "Melewati SLA Operasional",
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                ]);
            }
        }

        if ($insertStatus->count() > 0) {
            $insertStatusJob = StatusJobOps::insert($insertStatus->toArray());
        }

        $this->info("Success insert data jumlah {$insertStatus->count()}");
    }
}
