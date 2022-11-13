<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\FederationController;
use App\Models\Psetting;
use App\Models\Multi;
class TrainerSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:synctrainers {--ip=} {--host=}';

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
            info("`{$id}` - Started trainer Sync");
            $settings = Psetting::where('ipaddress',$this->option('ip'))->where('host',$this->option('host'))->first();
            if($settings){
                if(!$settings->processing_trainers){
                    info("`{$id}` - Check Sync all if enabled");
                    if($settings->syncall){
                        
                    }else{
                        info("`{$id}` - Sync all not enabled. Check Sync trainer if enabled");
                        if($settings->synctrainers){
                            info("`{$id}` - Sync trainer enabled. Call API");
                            $settings->processing_trainers = 1;
                            $settings->save();
                            $data = (new FederationController)->searchtrainerlist(new Request);
                            if($data){
                                info("`{$id}` - Check data count.");
                                $dcount = count($data['trainers']['data']);
                                if($dcount>0){
                                    Multi::insertOrUpdate($data['trainers']['data'],'ftrainers');
                                    info("`{$id}` - `{$dcount}` records synced.");
                                }
                            }
                            $settings = Psetting::where('ipaddress',$this->option('ip'))->where('host',$this->option('host'))->update(['processing_trainers'=>0]);
                        }else{
                            info("`{$id}` - Sync all not enabled. Sync trainer not enabled");
                        }

                    }
                }else{
                    info("`{$id}` - Trainer Sync command already processing. Sync cancelled.");
                }
            }
        } catch (\Throwable $th) {
            info("Error occured in `{$id}`. Check logs.",['error'=>strval($th)]);
        }
        info("`{$id}` - Finished trainer Sync");
    }
}
