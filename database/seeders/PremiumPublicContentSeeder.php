<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PremiumPublicContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PageSeeder::class);

        $categoryDescriptions = [
            'appetizers' => 'Delicate beginnings designed to awaken the palate.',
            'main-courses' => 'Composed plates where premium ingredients meet precise technique.',
            'desserts' => 'Elegant finales balancing texture, restraint, and indulgence.',
            'beverages' => 'Signature cocktails, refined zero-proof creations, and thoughtfully composed refreshments.',
        ];

        foreach ($categoryDescriptions as $slug => $description) {
            DB::table('menu_categories')
                ->where('slug', $slug)
                ->update([
                    'description' => $description,
                    'updated_at' => now(),
                ]);
        }

        $itemDescriptions = [
            'truffle-crusted-beef-tenderloin' => 'Center-cut beef tenderloin with an aromatic herb-and-truffle crust, prepared to your preference for a rich, deeply savoury finish.',
            'miso-glazed-chilean-sea-bass' => 'Chilean sea bass glazed with white miso and caramelised until lacquered, balancing buttery richness with savoury-sweet umami.',
            'herb-roasted-rack-of-lamb' => 'French-trimmed rack of lamb roasted with rosemary, thyme, and garlic beneath a delicate Dijon-herb crust.',
            'butter-poached-lobster-thermidor' => 'Butter-poached lobster finished with cognac, Dijon, Gruyère, and a silken cream reduction.',
            'black-garlic-wagyu-striploin' => 'Marbled Wagyu striploin seared to a deep crust and finished with a savoury black-garlic glaze.',
            'valrhona-dark-chocolate-sphere' => 'A delicate Valrhona dark-chocolate sphere filled with chocolate crémeux, salted caramel, and hazelnut praline, opened tableside with warm chocolate sauce.',
            'madagascar-vanilla-mille-feuille' => 'Caramelised puff pastry layered with Madagascar vanilla diplomat cream for a delicate balance of crispness, richness, and restraint.',
            'pistachio-rose-entremet' => 'Pistachio mousse with raspberry gel, rose-infused sponge, and a polished white-chocolate glaze.',
            'yuzu-white-chocolate-cheesecake' => 'White-chocolate cheesecake brightened with yuzu, almond crumble, and passion-fruit coulis.',
            'caramelized-pear-and-almond-tart' => 'Warm pear and almond-frangipane tart with brown-butter ice cream and vanilla-bean sauce.',
            'imperial-saffron-champagne-cocktail' => 'Brut champagne with saffron syrup, elderflower liqueur, and lemon for a floral, gently citrus finish.',
            'smoked-fig-bourbon-reserve' => 'Small-batch bourbon with roasted fig, aromatic bitters, and restrained oak smoke for a smooth, gently sweet finish.',
            'white-peach-jasmine-elixir' => 'White peach nectar, jasmine tea, fresh lemon, and sparkling mineral water with a fragrant, refreshing finish.',
            'black-truffle-espresso-martini' => 'Vodka, freshly brewed espresso, coffee liqueur, and a restrained black-truffle infusion with a silky, aromatic finish.',
            'golden-yuzu-honey-sparkler' => 'Japanese yuzu, raw honey, ginger, and sparkling water for a bright, citrus-forward finish.',
        ];

        foreach ($itemDescriptions as $slug => $description) {
            DB::table('menu_items')
                ->where('slug', $slug)
                ->update([
                    'description' => $description,
                    'updated_at' => now(),
                ]);
        }

        $galleryImages = [
            'Elegant Dining Room' => [
                'title' => 'The Dining Room',
                'alt_text' => 'Warmly lit fine-dining room at Le Jardin',
            ],
            'Signature Dish' => [
                'title' => 'A Signature Composition',
                'alt_text' => 'Seared scallops presented with refined plating',
            ],
            'Banquet Setup' => [
                'title' => 'A Private Occasion',
                'alt_text' => 'Banquet hall arranged for a private celebration',
            ],
            'Warm Restaurant Ambiance' => [
                'title' => 'Evening Ambiance',
                'alt_text' => 'Warm evening ambiance inside Le Jardin',
            ],
        ];

        foreach ($galleryImages as $originalTitle => $values) {
            DB::table('gallery_images')
                ->whereIn('title', [$originalTitle, $values['title']])
                ->update([
                    ...$values,
                    'updated_at' => now(),
                ]);
        }
    }
}
