<?php

namespace App\Console\Commands;

use App\ModelVix;
use Carbon\Carbon;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;


class ImportVixIndexData extends Command
{

    protected $signature = 'import:vix_indices_data';
    protected $description = 'Import vix, nf, bnf, data to database.';

    public function __construct(){
        parent::__construct();
    }


    public function handle(){
        $fromDate = Carbon::now()->subDays(5)->format('d-M-Y');
        $toDate = Carbon::now()->format('d-M-Y');
        $this->vix($fromDate, $toDate);

    }

    private function vix($from_date, $to_date){
        $referer_url = "https://www1.nseindia.com/products/content/equities/indices/historical_vix.htm";
        $url = "https://www1.nseindia.com/products/dynaContent/equities/indices/hist_vix_data.jsp?&fromDate=$from_date&toDate=$to_date";

        $client = new Client([
            'headers' => [
                'referer' => $referer_url,
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:72.0) Gecko/20100101 Firefox/72.0',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, br',
            ],
        ]);

        $response = $client->request('GET', $url, [
            'verify' => false,
        ]);

        $html = $response->getBody();

        $html = (string) $html;
        $dom = new DOMDocument();

        $this->info($url);
        $dom->loadHTML($html);
        $dom->preserveWhiteSpace = false;

        $tables = $dom->getElementsByTagName('table');
        $rows = $tables->item(0)->getElementsByTagName('tr');

        foreach ($rows as $row) {
            $cols = $row->getElementsByTagName('td');
            if ($cols->length > 0){
                try{
                    $date = Carbon::createFromFormat('d-M-Y', $cols->item(0)->nodeValue);
                    $open = floatval($cols->item(1)->nodeValue);
                    $high = floatval($cols->item(2)->nodeValue);
                    $low = floatval($cols->item(3)->nodeValue);
                    $close = floatval($cols->item(4)->nodeValue);
                    $prev_close = floatval($cols->item(5)->nodeValue);
                    $change = floatval($cols->item(6)->nodeValue);
                    $pct_change = floatval($cols->item(7)->nodeValue);

                    $count = ModelVix::where('date', '=', $date->format('Y-m-d'))->count();

                    if ($count == 0){
                        $model = new ModelVix();
                        $model->date = $date;
                        $model->open = $open;
                        $model->high = $high;
                        $model->low = $low;
                        $model->close = $close;
                        $model->prevclose = $prev_close;
                        $model->pct_change = $pct_change;
                        $model->save();
                        $this->info('added new vix for date : ' . $date->format('d-m-Y'));
                    }
                }catch (\Exception $e){
                    //$this->error($e->getMessage());
                }
            }
        }
    }

}
