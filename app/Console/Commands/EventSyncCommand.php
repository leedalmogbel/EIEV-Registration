<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\FederationController;
use App\Models\Psetting;
use App\Models\Multi;
class EventSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:syncevents {--ip=} {--host=}';

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
            info("`{$id}` - Started event Sync");
            $settings = Psetting::where('ipaddress',$this->option('ip'))->where('host',$this->option('host'))->first();
            if($settings){
                if(!$settings->processing_events){
                    info("`{$id}` - Check Sync all if enabled");
                    if($settings->syncall){
        
                    }else{
                        info("`{$id}` - Sync all not enabled. Check Sync event if enabled");
                        if($settings->syncevents){
                            info("`{$id}` - Sync event enabled. Call API");
                            $settings->processing_events = 1;
                            $settings->save();
                            $data = (new FederationController)->geteieveventlist(new Request);
                            if($data){
                                info("`{$id}` - Check data count.");
                                $dcount = count($data['events']['data']);
                                if($dcount>0){
                                    Multi::insertOrUpdate($data['events']['data'],'fevents');
                                    info("`{$id}` - `{$dcount}` records synced.");
                                }
                            }
                            $settings = Psetting::where('ipaddress',$this->option('ip'))->where('host',$this->option('host'))->update(['processing_events'=>0]);
                        }else{
                            info("`{$id}` - Sync all not enabled. Sync event not enabled");
                        }
                    }
                }else{
                    info("`{$id}` - Event Sync command already processing. Sync cancelled.");
                }
            }
        } catch (\Throwable $th) {
            info("Error occured in `{$id}`. Check logs.",['error'=>strval($th)]);
        }
        info("`{$id}` - Finished event Sync");
    }
}
