<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\FederationController;
use App\Models\Psetting;
use App\Models\Multi;
class EntrySyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:syncentries {--ip= {--host=}';

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
            info("`{$id}` - Started entry Sync");
            $settings = Psetting::where('ipaddress',$this->option('ip'))->where('host',$this->option('host'))->first();
            if($settings){
                if(!$settings->processing_entries){
                    info("`{$id}` - Check Sync all if enabled");
                    if($settings->syncall){
        
                    }else{
                        info("`{$id}` - Sync all not enabled. Check Sync entry if enabled");
                        if($settings->syncentries){
                            info("`{$id}` - Sync entry enabled. Call API");
                            $settings->processing_entries = 1;
                            $settings->save();
                            $data = (new FederationController)->getentries(new Request);
                            if($data){
                                info("`{$id}` - Check data count.");
                                $dcount = count($data['entries']['data']);
                                if($dcount>0){
                                    Multi::insertOrUpdate($data['entries']['data'],'fentries');
                                    info("`{$id}` - `{$dcount}` records synced.");
                                }
                            }
                            $settings = Psetting::where('ipaddress',$this->option('ip'))->where('host',$this->option('host'))->update(['processing_entries'=>0]);
                        }else{
                            info("`{$id}` - Sync all not enabled. Sync entry not enabled");
                        }
                    }
                }else{
                    info("`{$id}` - Entry Sync command already processing. Sync cancelled.");
                }
            }
        } catch (\Throwable $th) {
            info("Error occured in `{$id}`. Check logs.",['error'=>strval($th)]);
        }
        info("`{$id}` - Finished entry Sync");
    }
}
