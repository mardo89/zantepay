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
        $codesFile = fopen("d:/codes.csv", "r");

        $areaCodes = [];

        while (($data = fgetcsv($codesFile)) !== FALSE) {
            $country = $data[0];
            $countryCode = $data[1];
            $areaName = $data[2];
            $areaCode = $data[3];

            if (trim($areaName) != '') {
                $areaCodes[$countryCode][] = [
                    'country' => $country,
                    'country_code' => $countryCode,
                    'area_name' => $areaName,
                    'area_code' => preg_replace('/\(.*\)/', ' ', $areaCode),
                ];
            }
        }

        fclose($codesFile);

        $dbCountries = Country::all();

        foreach ($dbCountries as $dbCountry) {

            $this->info($dbCountry->name . ' ...');

            if (!isset($areaCodes[$dbCountry->phonecode])) {
                $this->error('-----------------------------------------------------------------------');
                $this->info('');
                $this->info('');
                continue;
            }

            foreach ($areaCodes[$dbCountry->phonecode] as $code) {
                AreaCode::create(
                    [
                        'country_id' => $dbCountry->id,
                        'country_code' => $code['country_code'],
                        'area_code' => trim($code['area_code']),
                        'area_name' => $code['area_name']
                    ]
                );
            }

            $this->info('Success!');
            $this->info('');
            $this->info('');
        }
    }
}
