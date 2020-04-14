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
//            $this->vix($fromDate->format('d-M-Y'), $toDate->format('d-M-Y'));
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
            'headers' => [
                'Referer' => $referer_url,
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.14; rv:72.0) Gecko/20100101 Firefox/72.0',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Cookie' => '_ga=GA1.2.890571748.1554353107; pointer=1; sym1=ADANIENT; RT="z=1&dm=nseindia.com&si=9550db99-e592-4f55-a9a3-950cfb2c47e5&ss=k8zl7eo5&sl=0&tt=0&bcn=%2F%2F684d0d3b.akstat.io%2F"; NSE-TEST-1=1944068106.20480.0000; JSESSIONID=811CD88AE123FCA233FB11D348087A77.tomcat2; bm_sv=0C75B1D59D936ED5B46FA28E3C802BAD~bzAFCjRoFlsiJUZnnadSEYIuUh25cmEiq/8/JQfp+rf8ols6fiCHBuXfJP61WjeDdObCtUnECJnBgCIoxlc8XjB8bFAKEJgynaDT8IAcX6UZqoUN3w/U/wzeyUAn/EWRDF8jttT4ek86ELGavovpbRxv3EQF9sFr7am3J/nDqDM=; ak_bmsc=578DC99F3CFF4710262C35FE48D4A2CD6011A90FB81800003D67955EF2B4D71E~plb46leFvQa53mdrS+fKWGTKao9VDeVHA0/y+psEIJmOsWQjOrVgWuuExkvTQxoYGy/BwT+fo+CS/Q8QdyHqUrSGQHcsWKiuVnIpIq52cc9ZE6gUUo0qtC7ZN/kGjsUkiUdPvGOVCIeSUUhhV125Bal4BDsIKdW1cY1NB9Zmylq+xnRBcM5237MgFchdA8InqyfswUtjX2rkE45IIjR6qITaGFw0k0p1QFavFDdgsdUgw=; bm_mi=B00A8C4483E49DFC45E5B0F6C24C4277~5L+9s6U4szN6PisWvTS+fZ76Y25bIFum5w87EuO43Sjz2I4ywn1dud/eBZE4E3bNMJAKEsCYjq9HJAmcmSDx9BW26MGN0FNxz8NkoyQZSfgEzNs/Fu0OI60L/tEkF1KPDXKS3ZTMUsO7Ik8lOyPQvV/iFxWMuTVl/hBRlnEUV8fa8w6poNw4xmfw414F/e2LFWFHuIpAX1luR/rWQ5XLK4p4fCaOU7xerAt0WAbI5JIPTXF/kNylQD4G+BOA1eIEE7R7l55uBsR8Dvi56yOcc2uUIEae5w9hiY3zdewaaUAaL+cvrjup+WWQ/l9mAJeT'
            ],
        ]);


       // $jar = new \GuzzleHttp\Cookie\CookieJar();
        $response = $client->request('GET', $url, [
//            'curl' => array( CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false ),
            'verify' => false,
            //'timeout' => 10, // 10 seconds
//            'allow_redirects' => true,
            'debug' => true
//            'cookies' => $jar,
//            'referer' => true,
//            'strict' => false,
//            'protocols' => ['http', 'https'],
        ]);

        $this->info('got response...');

        $html = $response->getBody();

        $html = (string) $html;
        return $html;
    }

}
