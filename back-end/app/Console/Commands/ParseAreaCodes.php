<?php

namespace App\Console\Commands;

use App\Models\DB\AreaCode;
use App\Models\DB\Country;
use Illuminate\Console\Command;

class ParseAreaCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'area-codes:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse Area Codes and store them to the DB';

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
    public function handle()
    {
        $dbCountries = Country::all();

        foreach ($dbCountries as $dbCountry) {

            $this->info($dbCountry->name . ' ...');

            $codesJson = file_get_contents('http://gomashup.com/json.php?fds=geo/international/areacodes/country/' . $dbCountry->name);

            $codesJson[0] = ' ';
            $codesJson[strlen($codesJson) - 1] = ' ';

            $codes = json_decode(trim($codesJson));

            if (!$codes) {
                $this->error('-----------------------------------------------------------------------');
                continue;
            }

            foreach ($codes->result as $code) {
                $areaCode = preg_replace('/\(.*\)/', ' ', $code->AreaCode);

                AreaCode::create(
                    [
                        'country_id' => $dbCountry->id,
                        'country_code' => $code->CountryCode,
                        'area_code' => trim($areaCode),
                        'area_name' => $code->Area
                    ]
                );
            }

            $this->info('Success!');
            $this->info('');
            $this->info('');
        }
    }
}
