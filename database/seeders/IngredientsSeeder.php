<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;

class IngredientsSeeder extends Seeder
{
    const BASE_URL = 'https://food.ndtv.com/ingredient/';
    const LOADMORE_BASE_URL = 'https://food.ndtv.com/ingredient/loadmore/';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $results = [];
        $tabs = [
            "Vegetables" => 4, "Spices-and-Herbs" => 4, "Cereals-and-Pulses" => 3, "Meat" => 2, "Dairy-Products" => 2, "Fruits" => 3, "Seafood" => 2,
            "Sugar-and-Sugar-Products" => 1, "Nuts-and-Oil-seeds" => 2, "Other-ingredients" => 5
        ];

        foreach ($tabs as $category => $loadMoreCount) {
            $url = self::BASE_URL . $category;
            $response = Http::get($url);
            $html = $response->body();
            $crawler = new Crawler($html);


            $crawler->filter('a.IngrLst-Ar_ttl')->each(function (Crawler $node) use (&$results) {
                $node->html();
                $title = $node->text();
                $results[$title] = ['name' => $title];
            });

            for ($i = 2; $i <= $loadMoreCount; $i++) {
                $loadUrl = self::LOADMORE_BASE_URL . $category . "/" . $i . "/18";
                $response = Http::get($loadUrl);
                $html = $response->body();
                $crawler = new Crawler($html);

                $crawler->filter('a.IngrLst-Ar_ttl')->each(function (Crawler $node) use (&$results) {
                    $node->html();
                    $title = $node->text();

                    $results[$title] = ['name' => $title];
                });
            }
        }
        Ingredient::insert($results);
    }
}
