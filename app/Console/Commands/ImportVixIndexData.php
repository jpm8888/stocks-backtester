<?php

namespace App\Console\Commands;

use App\ModelIndices;
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
        $fromDate = Carbon::now()->subDays(5);
        $toDate = Carbon::now();

        try{
            $this->vix($fromDate->format('d-M-Y'), $toDate->format('d-M-Y'));
            $this->index($fromDate->format('d-m-Y'), $toDate->format('d-m-Y'), 'NIFTY');
            $this->index($fromDate->format('d-m-Y'), $toDate->format('d-m-Y'), 'BANKNIFTY');
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }

        $this->info('all done.');
    }

    private function vix($from_date, $to_date){
        $this->info('staring to import vix...');
        $referer_url = "https://www1.nseindia.com/products/content/equities/indices/historical_vix.htm";

        $url = "https://www1.nseindia.com/products/dynaContent/equities/indices/hist_vix_data.jsp?&fromDate=$from_date&toDate=$to_date";


        $this->info('getting html');
        $html = $this->get_html($url, $referer_url);
        $this->info('getting html done');
        $internalErrors = libxml_use_internal_errors(true); //do not delete it.
        $dom = new DOMDocument();
        $this->info('loading html');
        $dom->loadHTML($html);
        $this->info('loading html done');
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
//                    $this->error($e->getMessage());
                }
            }
        }
    }

    private function index($from_date, $to_date, $symbol){
        $this->info("starting to import $symbol...");
        $referer_url = "https://www1.nseindia.com/products/content/equities/indices/historical_index_data.htm";

        $indexType = ($symbol == 'NIFTY') ? "NIFTY%2050" : "NIFTY%20BANK";

        $url = "https://www1.nseindia.com/products/dynaContent/equities/indices/historicalindices.jsp?indexType=$indexType&fromDate=$from_date&toDate=$to_date";

        $this->info('getting html');
        $html = $this->get_html($url, $referer_url);
        $this->info('getting html done');
        $internalErrors = libxml_use_internal_errors(true); //do not delete it.
        $dom = new DOMDocument();
        $this->info('loading html');
        $dom->loadHTML($html);
        $this->info('loading html done');
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
                    $volume = floatval($cols->item(5)->nodeValue);
                    $turnover = floatval($cols->item(6)->nodeValue);

                    $count = ModelIndices::where('symbol', '=', $symbol)->where('date', '=', $date->format('Y-m-d'))->count();

                    if ($count == 0){
                        $data = ModelIndices::where('symbol', $symbol)->whereDate('date', '<', $date)->orderBy('date', 'desc')->first();
                        $prevclose = ($data) ? $data->close : 0;

                        $model = new ModelIndices();
                        $model->symbol = $symbol;
                        $model->open = $open;
                        $model->high = $high;
                        $model->low = $low;
                        $model->close = $close;
                        $model->prevclose = $prevclose;
                        $model->volume = $volume;
                        $model->turnover = $turnover;
                        $model->date = $date;
                        $model->save();
                        $this->info("added new $symbol for date : " . $date->format('d-m-Y'));
                    }
                }catch (\Exception $e){
//                    $this->error($e->getMessage());
                }
            }
        }
    }

    public function get_html($url, $referer_url){
        $client = new Client([
            'verify' => false,
            'headers' => [
                'Referer' => $referer_url,
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:72.0) Gecko/20100101 Firefox/72.0',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, br',
                'X-Requested-With' => 'XMLHttpRequest',
//                'Cookie' => '_ga=GA1.2.890571748.1554353107; pointer=1; sym1=ADANIENT; RT="z=1&dm=nseindia.com&si=9550db99-e592-4f55-a9a3-950cfb2c47e5&ss=k8zl7eo5&sl=0&tt=0&bcn=%2F%2F684fc538.akstat.io%2F"; NSE-TEST-1=1944068106.20480.0000; JSESSIONID=811CD88AE123FCA233FB11D348087A77.tomcat2; ak_bmsc=20F709DD97970B724939FED3CA6943736011A90D384A0000878F955EBCD32355~plHzwajQSsts8aDiMs3oriR4XL2rtCRsN+lqLFAcOaOr9gf0LC/7fUeXfNP29bzB4ipKolQnfgYcmSBY5+lAY0UUTt12pMD/tnd33hXXzWdQTaH+HdktXab4MnlziJ1fzpOPvzO4KmHQgK/9PwdMXicktNPoY9baofaLPw7uvlF9Ks1RA+ytYvga1gX+zbAsSw8NiQZQTytGM8NSdtBhEmuHy8GVwPvxxByyseAXNPnz4=; bm_sv=53DC274F332964556BCDDE0ABF5BF514~qouneVNylrMy+3YA2+XYnHbDLE3HfvwCwHor9EV1aeOfjqTnsFH4M2y8Uj+aiRCDpz3YXGQbh9ww+FRlZIDb3yW/I1XFegSyJUPtjxocmnPTBEjHYChGxW+L79HlJ4gTkeYrcjzYNlFwLtQWnCECd31YvJtr767EGqSYHbNZVGc=',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
                'Accept-Language' => 'en-US,en;q=0.5'
            ],
        ]);


        $response = $client->request('GET', $url, [
            'verify' => '/etc/ssl/certs/cacert.pem',
            'debug' => true
        ]);

        $this->info('got response...');

        $html = $response->getBody();

        $html = (string) $html;
        return $html;
    }

}
