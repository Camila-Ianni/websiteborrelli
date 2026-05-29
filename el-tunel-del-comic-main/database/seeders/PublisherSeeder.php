<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    public function run(): void
    {
        $publishers = [
            ['name' => 'Marvel Comics', 'country' => 'USA', 'website' => 'https://www.marvel.com'],
            ['name' => 'DC Comics', 'country' => 'USA', 'website' => 'https://www.dc.com'],
            ['name' => 'Image Comics', 'country' => 'USA', 'website' => 'https://www.imagecomics.com'],
            ['name' => 'Dark Horse Comics', 'country' => 'USA', 'website' => 'https://www.darkhorse.com'],
            ['name' => 'Shueisha', 'country' => 'Japan', 'website' => 'https://www.shueisha.co.jp'],
            ['name' => 'Kodansha', 'country' => 'Japan', 'website' => 'https://www.kodansha.co.jp'],
            ['name' => 'Shogakukan', 'country' => 'Japan', 'website' => 'https://www.shogakukan.co.jp'],
            ['name' => 'VIZ Media', 'country' => 'USA', 'website' => 'https://www.viz.com'],
            ['name' => 'IDW Publishing', 'country' => 'USA', 'website' => 'https://www.idwpublishing.com'],
            ['name' => 'Vertigo', 'country' => 'USA', 'website' => 'https://www.vertigocomics.com'],
        ];

        foreach ($publishers as $publisher) {
            Publisher::create($publisher);
        }
    }
}
