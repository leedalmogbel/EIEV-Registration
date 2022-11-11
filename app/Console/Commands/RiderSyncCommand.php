<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\FederationController;
use App\Models\Psetting;
use App\Models\Multi;


class RiderSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:syncriders';

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
            info("`{$id}` - Started Rider Sync");
            $settings = PSetting::first();
            info("`{$id}` - Check Sync all if enabled");
            if($settings->syncall){

            }else{
                info("`{$id}` - Sync all not enabled. Check Sync Rider if enabled");
                if($settings->syncriders){
                    info("`{$id}` - Sync Rider enabled. Call API");
                    $data = (new FederationController)->searchriderlist(new Request);
                    if($data){
                        info("`{$id}` - Check data count.");
                        $dcount = count($data['riders']['data']);
                        if($dcount>0){
                            Multi::insertOrUpdate($data['riders']['data'],'friders');
                            info("`{$id}` - `{$dcount}` records synced.");
                        }
                    }
                }else{
                    info("`{$id}` - Sync all not enabled. Sync Rider not enabled");
                }
            }
        } catch (\Throwable $th) {
            info("Error occured in `{$id}`. Check logs.",['error'=>strval($th)]);
        }
        info("`{$id}` - Finished Rider Sync");
        
    }
}
