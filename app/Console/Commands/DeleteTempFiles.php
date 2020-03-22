<?php

namespace App\Console\Commands;

use App\ModelTempFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:temporary_files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temp files and deprecated database entries.';

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
     * @return mixed
     */
    public function handle(){
        ModelTempFile::where('is_used', 0)
            ->where('is_deleted', '0')
            ->chunkById(100, function ($items){
           foreach ($items as $item){
               try{
                   $filepath = 'public/' . $item->path;
                   $isFileExists = Storage::exists($filepath);
                   if ($isFileExists) Storage::delete($filepath);
                   $this->info('deleting -> ' . $item->path);
                   $item->is_deleted = 1;
                   $item->save();
               } catch (\Exception $e){
                    $this->error($e->getMessage());
               }

           }
        });

        $this->info('deleted all temp files.');
    }


}
