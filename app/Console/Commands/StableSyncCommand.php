<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\FederationController;
use App\Models\Psetting;
use App\Models\Multi;
class StableSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:syncstables {--ip=} {--host=}';

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
            info("`{$id}` - Started stable Sync");
            $settings = Psetting::where('ipaddress',$this->option('ip'))->where('host',$this->option('host'))->first();
            if($settings){
                if(!$settings->processing_stables){
                    info("`{$id}` - Check Sync all if enabled");
                    if($settings->syncall){
        
                    }else{
                        info("`{$id}` - Sync all not enabled. Check Sync stable if enabled");
                        if($settings->syncstables){
                            $settings->processing_stables = 1;
                            $settings->save();
                            info("`{$id}` - Sync stable enabled. Call API");
                            $data = (new FederationController)->getstablelist(new Request);
                            if($data){
                                info("`{$id}` - Check data count.");
                                $dcount = count($data['stables']['data']);
                                if($dcount>0){
                                    Multi::insertOrUpdate($data['stables']['data'],'fstables');
                                    info("`{$id}` - `{$dcount}` records synced.");
                                }
                            }
                            $settings = Psetting::where('ipaddress',$this->option('ip'))->where('host',$this->option('host'))->update(['processing_stables'=>0]);
                        }else{
                            info("`{$id}` - Sync all not enabled. Sync stable not enabled");
                        }
                    }
                }else{
                    info("`{$id}` - Stable Sync command already processing. Sync cancelled.");
                }
            }
        } catch (\Throwable $th) {
            info("Error occured in `{$id}`. Check logs.",['error'=>strval($th)]);
        }
        info("`{$id}` - Finished stable Sync");
    }
}
