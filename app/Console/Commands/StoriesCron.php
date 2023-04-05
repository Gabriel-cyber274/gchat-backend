<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StoriesMedia;
use App\Models\StoriesText;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class StoriesCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stories:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */

     
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        \Log::info("Cron is working fine!");
        $MediaAll = StoriesMedia::with(['stories'])->get();

        $old = $MediaAll->filter(function($value, $key){
            // $day = substr(Str::limit($value->created_at, 10, ''), 8) + 1;
            // $date = Str::limit($value->created_at, 8, ''). $day . ' '. substr($value->created_at, 11);
            return $value->created_at <= now()->subHours(24);
        });

        foreach($old as $data){
            StoriesMedia::destroy($data->id);
        }

        
        $textAll = StoriesText::with(['stories'])->get();

        $old2 = $textAll->filter(function($value, $key){
            // $day = substr(Str::limit($value->created_at, 10, ''), 8) + 1;
            // $date = Str::limit($value->created_at, 8, ''). $day . ' '. substr($value->created_at, 11);
            return $value->created_at <= now()->subHours(24);
        });

        foreach($old2 as $data){
            StoriesText::destroy($data->id);
        }
        
    }
}
