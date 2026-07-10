<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class MenuSeeder extends Seeder
{
    private const IMAGE_SOURCE_DIRECTORY = 'seeders/images/menu';

    private const IMAGE_STORAGE_DIRECTORY = 'menu-items';

    private const MAX_IMAGE_SIZE_IN_BYTES = 2 * 1024 * 1024;

    /**
     * @var array<string, string>
     */
    private const IMAGE_EXTENSIONS_BY_MIME_TYPE = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    public function run(): void
    {
        $disk = Storage::disk('public');

        $disk->makeDirectory(self::IMAGE_STORAGE_DIRECTORY);

        $categories = [
            [
                'name' => 'Appetizers',
                'description' => 'Elegant starters prepared for sharing and tasting.',
                'sort_order' => 1,
                'items' => [
                    [
                        'name' => 'Seared Hokkaido Scallops',
                        'description' => 'Golden-seared scallops served with cauliflower purée, brown butter, crispy pancetta, and citrus beurre blanc.',
                        'price' => 14.00,
                        'sort_order' => 1,
                        'image' => 'Seared-Hokkaido-Scallops.png',
                    ],
                    [
                        'name' => 'Wagyu Beef Tartare',
                        'description' => 'Hand-cut premium Wagyu beef seasoned with Dijon mustard, capers, shallots, and truffle oil, served with toasted brioche.',
                        'price' => 22.00,
                        'sort_order' => 2,
                        'image' => 'Wagyu-Beef-Tartare.png',
                    ],
                    [
                        'name' => 'Lobster and Crab Raviolo',
                        'description' => 'Handmade pasta filled with lobster and crab, finished with saffron bisque, herb oil, and microgreens.',
                        'price' => 18.00,
                        'sort_order' => 3,
                        'image' => 'Lobster-and-Crab-Raviolo.png',
                    ],
                    [
                        'name' => 'Foie Gras Torchon',
                        'description' => 'Silky foie gras served with fig compote, toasted brioche, pistachio crumble, and aged balsamic reduction.',
                        'price' => 18.00,
                        'sort_order' => 4,
                        'image' => 'Foie-Gras-Torchon.png',
                    ],
                    [
                        'name' => 'Truffle Mushroom Vol-au-Vent',
                        'description' => 'Flaky puff pastry filled with wild mushrooms, black truffle cream, Parmesan, and fresh herbs.',
                        'price' => 18.00,
                        'sort_order' => 5,
                        'image' => 'Truffle-Mushroom-Vol-au-Vent.png',
                    ],
                ],
            ],
            [
                'name' => 'Main Courses',
                'description' => 'Signature entrées crafted for a refined dining experience.',
                'sort_order' => 2,
                'items' => [
                    [
                        'name' => 'Truffle-Crusted Beef Tenderloin',
                        'description' => 'Center-cut beef tenderloin, herb-truffle crust, precisely cooked medium-rare, rich and aromatic presentation.',
                        'price' => 34.00,
                        'sort_order' => 1,
                        'image' => 'Golden-Truffle-Beef-Tenderloin.png',
                    ],
                    [
                        'name' => 'Miso-Glazed Chilean Sea Bass',
                        'description' => 'Buttery sea bass with a caramelized white-miso glaze; delicate, savory-sweet, and umami-forward.',
                        'price' => 48.00,
                        'sort_order' => 2,
                        'image' => 'Miso-Glazed-Chilean-Sea-Bass.png',
                    ],
                    [
                        'name' => 'Herb-Roasted Rack of Lamb',
                        'description' => 'French-trimmed lamb rack with rosemary, thyme, garlic, and Dijon crust.',
                        'price' => 32.00,
                        'sort_order' => 3,
                        'image' => 'Herb-Roasted-Rack-of-Lamb.png',
                    ],
                    [
                        'name' => 'Butter-Poached Lobster Thermidor',
                        'description' => 'Tender lobster tail finished with cognac, Dijon, Gruyère, and a luxurious cream reduction.',
                        'price' => 30.00,
                        'sort_order' => 4,
                        'image' => 'Butter-Poached-Lobster-Thermidor.png',
                    ],
                    [
                        'name' => 'Black Garlic Wagyu Striploin',
                        'description' => 'Premium Wagyu with intense marbling, black-garlic glaze, and a deeply caramelized crust.',
                        'price' => 25.00,
                        'sort_order' => 5,
                        'image' => 'Black-Garlic-Wagyu-Striploin.png',
                    ],
                ],
            ],
            [
                'name' => 'Desserts',
                'description' => 'House-made desserts for a memorable finish.',
                'sort_order' => 3,
                'items' => [
                    [
                        'name' => 'Valrhona Dark Chocolate Sphere',
                        'description' => 'Premium dark-chocolate shell filled with silky chocolate crémeux, salted caramel, and hazelnut praline. Finished tableside with warm chocolate sauce for a dramatic presentation.',
                        'price' => 12.00,
                        'sort_order' => 1,
                        'image' => 'Valrhona-Dark-Chocolate-Sphere.png',
                    ],
                    [
                        'name' => 'Madagascar Vanilla Mille-Feuille',
                        'description' => 'Crisp caramelized puff pastry layered with Madagascar vanilla diplomat cream. Features delicate textures, balanced sweetness, and elegant precision plating.',
                        'price' => 13.00,
                        'sort_order' => 2,
                        'image' => 'Madagascar-Vanilla-Mille-Feuille.png',
                    ],
                    [
                        'name' => 'Pistachio Rose Entremet',
                        'description' => 'Smooth pistachio mousse with raspberry gel, rose-infused sponge, and a glossy white-chocolate glaze. Floral, nutty, lightly tart, and visually refined.',
                        'price' => 11.00,
                        'sort_order' => 3,
                        'image' => 'Pistachio-Rose-Entremet.png',
                    ],
                    [
                        'name' => 'Yuzu White Chocolate Cheesecake',
                        'description' => 'Creamy white-chocolate cheesecake accented with fresh yuzu citrus, almond crumble, and passion-fruit coulis. Rich yet refreshing with a bright, clean finish.',
                        'price' => 11.00,
                        'sort_order' => 4,
                        'image' => 'Yuzu-White-Chocolate-Cheesecake.png',
                    ],
                    [
                        'name' => 'Caramelized Pear and Almond Tart',
                        'description' => 'Buttery pastry filled with almond frangipane and caramelized pear, served warm with brown-butter ice cream and vanilla-bean sauce. Comforting, aromatic, and sophisticated.',
                        'price' => 11.00,
                        'sort_order' => 5,
                        'image' => 'Caramelized-Pear-and-Almond-Tart.png',
                    ],
                ],
            ],
            [
                'name' => 'Beverages',
                'description' => 'Refreshing beverages and curated non-alcoholic selections.',
                'sort_order' => 4,
                'items' => [
                    [
                        'name' => 'Imperial Saffron Champagne Cocktail',
                        'description' => 'Premium brut champagne infused with saffron syrup, elderflower liqueur, and a delicate lemon twist. Floral, lightly citrusy, elegant, and effervescent.',
                        'price' => 8.00,
                        'sort_order' => 1,
                        'image' => 'Imperial-Saffron-Champagne-Cocktail.png',
                    ],
                    [
                        'name' => 'Smoked Fig & Bourbon Reserve',
                        'description' => 'Small-batch bourbon combined with roasted fig reduction, aromatic bitters, and subtle oak smoke. Rich, smooth, slightly sweet, and visually dramatic.',
                        'price' => 7.00,
                        'sort_order' => 2,
                        'image' => 'Smoked-Fig-Bourbon-Reserve.png',
                    ],
                    [
                        'name' => 'White Peach & Jasmine Elixir',
                        'description' => 'White peach nectar, jasmine tea, fresh lemon, and sparkling mineral water. Fragrant, refreshing, delicately sweet, and refined.',
                        'price' => 6.00,
                        'sort_order' => 3,
                        'image' => 'White-Peach-Jasmine-Elixir.png',
                    ],
                    [
                        'name' => 'Black Truffle Espresso Martini',
                        'description' => 'Premium vodka, freshly brewed espresso, coffee liqueur, and a restrained black-truffle infusion. Silky, aromatic, earthy, and luxurious.',
                        'price' => 6.00,
                        'sort_order' => 4,
                        'image' => 'Black-Truffle-Espresso-Martini.png',
                    ],
                    [
                        'name' => 'Golden Yuzu & Honey Sparkler',
                        'description' => 'Japanese yuzu juice, raw honey, ginger, and premium sparkling water, finished with edible gold flakes. Bright, citrus-forward, refreshing, and visually elegant.',
                        'price' => 6.00,
                        'sort_order' => 5,
                        'image' => 'Golden-Yuzu-Honey-Sparkler.png',
                    ],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $items = $categoryData['items'];

            unset($categoryData['items']);

            $categorySlug = Str::slug($categoryData['name']);

            $category = MenuCategory::updateOrCreate(
                [
                    'slug' => $categorySlug,
                ],
                [
                    ...$categoryData,
                    'slug' => $categorySlug,
                    'is_visible' => true,
                ],
            );

            foreach ($items as $itemData) {
                $imageFilename = $itemData['image'];

                unset($itemData['image']);

                $itemSlug = Str::slug($itemData['name']);
                $imagePath = $this->storeSeedImage(
                    disk: $disk,
                    sourceFilename: $imageFilename,
                );

                MenuItem::updateOrCreate(
                    [
                        'menu_category_id' => $category->id,
                        'slug' => $itemSlug,
                    ],
                    [
                        ...$itemData,
                        'menu_category_id' => $category->id,
                        'slug' => $itemSlug,
                        'image_path' => $imagePath,
                        'is_visible' => true,
                    ],
                );
            }
        }
    }

    /**
     * Copy a trusted seeder image into Laravel's public storage disk.
     */
    private function storeSeedImage(
        FilesystemAdapter $disk,
        string $sourceFilename,
    ): string {
        $sourcePath = database_path(self::IMAGE_SOURCE_DIRECTORY.'/'.$sourceFilename);

        if (! File::isFile($sourcePath)) {
            throw new RuntimeException(
                "Menu seed image does not exist: {$sourcePath}",
            );
        }

        if (! File::isReadable($sourcePath)) {
            throw new RuntimeException(
                "Menu seed image is not readable: {$sourcePath}",
            );
        }

        $size = File::size($sourcePath);

        if ($size > self::MAX_IMAGE_SIZE_IN_BYTES) {
            throw new RuntimeException(
                sprintf(
                    'Menu seed image exceeds the 2 MB upload limit (%d bytes): %s',
                    $size,
                    $sourcePath,
                ),
            );
        }

        $mimeType = File::mimeType($sourcePath);

        if (
            ! is_string($mimeType)
            || ! array_key_exists($mimeType, self::IMAGE_EXTENSIONS_BY_MIME_TYPE)
        ) {
            throw new RuntimeException(
                sprintf(
                    'Unsupported menu seed image type "%s" for file: %s',
                    $mimeType ?: 'unknown',
                    $sourcePath,
                ),
            );
        }

        $contentHash = hash_file('sha256', $sourcePath);

        if (! is_string($contentHash)) {
            throw new RuntimeException(
                "Unable to hash menu seed image: {$sourcePath}",
            );
        }

        $destinationFilename = $contentHash.'.'.self::IMAGE_EXTENSIONS_BY_MIME_TYPE[$mimeType];
        $destinationPath = self::IMAGE_STORAGE_DIRECTORY.'/'.$destinationFilename;

        if ($disk->exists($destinationPath)) {
            return $destinationPath;
        }

        $storedPath = $disk->putFileAs(
            self::IMAGE_STORAGE_DIRECTORY,
            new HttpFile($sourcePath),
            $destinationFilename,
            [
                'visibility' => 'public',
            ],
        );

        if ($storedPath === false) {
            throw new RuntimeException(
                "Unable to store menu seed image: {$destinationPath}",
            );
        }

        return $storedPath;
    }
}
