<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\FederationController;
use App\Models\PSetting;
use App\Models\Multi;
class HorseSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:synchorses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = Str::uuid();
        try {
            info("`{$id}` - Started horse Sync");
            $settings = PSetting::first();
            info("`{$id}` - Check Sync all if enabled");
            if($settings->syncall){

            }else{
                info("`{$id}` - Sync all not enabled. Check Sync horse if enabled");
                if($settings->synchorses){
                    info("`{$id}` - Sync horse enabled. Call API");
                    $data = (new FederationController)->searchhorselist(new Request);
                    if($data){
                        info("`{$id}` - Check data count.");
                        $dcount = count($data['horses']['data']);
                        if($dcount>0){
                            Multi::insertOrUpdate($data['horses']['data'],'fhorses');
                            info("`{$id}` - `{$dcount}` records synced.");
                        }
                    }
                }else{
                    info("`{$id}` - Sync all not enabled. Sync horse not enabled");
                }
            }
        } catch (\Throwable $th) {
            info("Error occured in `{$id}`. Check logs.",['error'=>strval($th)]);
        }
        info("`{$id}` - Finished horse Sync");
    }
}
