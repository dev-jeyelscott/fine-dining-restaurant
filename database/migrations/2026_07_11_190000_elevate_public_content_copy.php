<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $pages = [
            'home' => [
                'title' => ['Welcome to Le Jardin', 'A Dining Experience, Beautifully Composed'],
                'excerpt' => ['A refined dining experience for families, guests, and special occasions.', 'Season-led cuisine, gracious hospitality, and an intimate setting for dinners, celebrations, and private occasions.'],
                'content' => ['Enjoy carefully prepared dishes, warm hospitality, and an elegant restaurant atmosphere designed for memorable dining experiences.', 'At Le Jardin, precise technique and generous hospitality come together in a dining experience that feels polished, personal, and warmly familiar.'],
                'meta_title' => ['Home | Le Jardin Fine Dining', 'Le Jardin Fine Dining | Seasonal Cuisine & Private Occasions'],
                'meta_description' => ['Discover Le Jardin Fine Dining, featuring curated menus, elegant ambiance, banquet options, and request-based reservations.', 'Discover seasonal cuisine, gracious hospitality, private dining, and beautifully considered occasions at Le Jardin Fine Dining.'],
            ],
            'menu' => [
                'title' => ['Our Menu', 'A Menu Guided by Season and Craft'],
                'excerpt' => ['Explore chef-prepared dishes, seasonal favorites, and refined dining selections.', 'From delicate first courses to expressive mains and elegant finales, every plate is composed with balance, precision, and character.'],
                'content' => ['Our menu features appetizers, entrées, desserts, and beverages prepared with quality ingredients and thoughtful presentation.', 'Explore a considered progression of dishes prepared with premium ingredients, precise technique, and a respect for flavour.'],
                'meta_title' => ['Menu | Le Jardin Fine Dining', 'Seasonal Menu | Le Jardin Fine Dining'],
                'meta_description' => ['Browse menu categories, dish descriptions, and prices from Le Jardin Fine Dining.', 'Explore Le Jardin’s considered menu of delicate starters, signature mains, elegant desserts, and composed beverages.'],
            ],
            'reservation-request' => [
                'title' => ['Reservation Request', 'Plan Your Evening at Le Jardin'],
                'excerpt' => ['Submit your preferred dining date and time for manual restaurant review.', 'Share your preferred date, time, and party size, and our team will personally confirm availability with you.'],
                'content' => ['Reservation requests are reviewed by our team. A submitted request is not yet a confirmed reservation.', 'Tell us when you would like to join us and who will be dining. Every request is reviewed personally so the details of your visit can be handled with care.'],
                'meta_description' => ['Submit a reservation request for manual confirmation by the restaurant team.', 'Request a table at Le Jardin. Our team personally reviews each preferred date, time, and party size before confirming availability.'],
            ],
            'order-inquiry' => [
                'title' => ['Order Inquiry', 'Bring Le Jardin to Your Table'],
                'excerpt' => ['Send your pickup or delivery inquiry for manual restaurant review.', 'Share your preferred dishes and pickup or delivery details, and our team will personally review availability and pricing with you.'],
                'content' => ['Order inquiries are reviewed by our team. Availability, final total, payment, and pickup or delivery details are confirmed directly with you.', 'Share the dishes you are considering, your preferred timing, and any special notes. Our team will then confirm availability, pricing, and fulfillment arrangements with you.'],
                'meta_description' => ['Submit an order inquiry for pickup or delivery review by the restaurant team.', 'Submit an Order Inquiry for pickup or delivery. Our team will confirm availability, final pricing, and arrangements directly with you.'],
            ],
            'gallery' => [
                'title' => ['Gallery', 'A Portrait of Le Jardin'],
                'excerpt' => ['View our restaurant interiors, dishes, events, and ambiance.', 'Discover the atmosphere, culinary craft, and celebrations that define Le Jardin.'],
                'content' => ['Browse a preview of our dining experience, food presentation, banquet spaces, and restaurant atmosphere.', 'From softly lit dining rooms to carefully finished plates and beautifully prepared celebrations, every image reflects our warmth, refinement, and attention to detail.'],
                'meta_description' => ['View restaurant interiors, dishes, events, and ambiance photos.', 'Explore Le Jardin’s dining rooms, signature dishes, private celebrations, and beautifully considered details.'],
            ],
            'banquet-hall' => [
                'title' => ['Banquet Hall', 'A Setting for Meaningful Occasions'],
                'excerpt' => ['Host family gatherings, private dining, and special events in an elegant setting.', 'Gather for private dinners, family milestones, celebrations, and corporate occasions in a setting shaped around attentive hospitality.'],
                'content' => ['Our banquet hall supports private dining, celebrations, corporate gatherings, and special occasions. Please contact us to discuss availability and event details.', 'Our banquet hall offers a refined setting for family celebrations, private dining, corporate meals, and life’s most meaningful gatherings. Contact our team to discuss availability and event details.'],
                'meta_title' => ['Banquet Hall | Le Jardin Fine Dining', 'Banquet Hall & Private Dining | Le Jardin Fine Dining'],
                'meta_description' => ['Explore banquet hall information, event types, capacity, and inquiry options.', 'Discover Le Jardin’s banquet hall and private-dining setting for family milestones, celebrations, and corporate occasions.'],
            ],
            'contact' => [
                'title' => ['Contact Us', 'Begin a Conversation with Le Jardin'],
                'excerpt' => ['Reach our team for general questions, directions, and dining inquiries.', 'Whether you are planning a visit, a private occasion, or have a dining question, our team will be pleased to assist.'],
                'content' => ['Contact us by phone, email, location map, or our inquiry form. Our team will respond as soon as possible.', 'Reach our team by phone, email, location map, or inquiry form. Every message is personally reviewed and answered as soon as practical.'],
                'meta_title' => ['Contact | Le Jardin Fine Dining', 'Contact Le Jardin Fine Dining'],
                'meta_description' => ['Contact Le Jardin Fine Dining by phone, email, map, or inquiry form.', 'Contact Le Jardin for dining questions, directions, Reservation Requests, Order Inquiries, and private occasions.'],
            ],
        ];

        $this->replaceDefaults('pages', 'slug', $pages);

        $this->replaceDefaults('menu_categories', 'slug', [
            'appetizers' => ['description' => ['Elegant starters prepared for sharing and tasting.', 'Delicate beginnings designed to awaken the palate.']],
            'main-courses' => ['description' => ['Signature entrées crafted for a refined dining experience.', 'Composed plates where premium ingredients meet precise technique.']],
            'desserts' => ['description' => ['House-made desserts for a memorable finish.', 'Elegant finales balancing texture, restraint, and indulgence.']],
            'beverages' => ['description' => ['Refreshing beverages and curated non-alcoholic selections.', 'Signature cocktails, refined zero-proof creations, and thoughtfully composed refreshments.']],
        ]);

        $this->replaceDefaults('menu_items', 'slug', [
            'truffle-crusted-beef-tenderloin' => ['description' => ['Center-cut beef tenderloin, herb-truffle crust, precisely cooked medium-rare, rich and aromatic presentation.', 'Center-cut beef tenderloin with an aromatic herb-and-truffle crust, prepared to your preference for a rich, deeply savoury finish.']],
            'miso-glazed-chilean-sea-bass' => ['description' => ['Buttery sea bass with a caramelized white-miso glaze; delicate, savory-sweet, and umami-forward.', 'Chilean sea bass glazed with white miso and caramelised until lacquered, balancing buttery richness with savoury-sweet umami.']],
            'herb-roasted-rack-of-lamb' => ['description' => ['French-trimmed lamb rack with rosemary, thyme, garlic, and Dijon crust.', 'French-trimmed rack of lamb roasted with rosemary, thyme, and garlic beneath a delicate Dijon-herb crust.']],
            'butter-poached-lobster-thermidor' => ['description' => ['Tender lobster tail finished with cognac, Dijon, Gruyère, and a luxurious cream reduction.', 'Butter-poached lobster finished with cognac, Dijon, Gruyère, and a silken cream reduction.']],
            'black-garlic-wagyu-striploin' => ['description' => ['Premium Wagyu with intense marbling, black-garlic glaze, and a deeply caramelized crust.', 'Marbled Wagyu striploin seared to a deep crust and finished with a savoury black-garlic glaze.']],
            'valrhona-dark-chocolate-sphere' => ['description' => ['Premium dark-chocolate shell filled with silky chocolate crémeux, salted caramel, and hazelnut praline. Finished tableside with warm chocolate sauce for a dramatic presentation.', 'A delicate Valrhona dark-chocolate sphere filled with chocolate crémeux, salted caramel, and hazelnut praline, opened tableside with warm chocolate sauce.']],
            'madagascar-vanilla-mille-feuille' => ['description' => ['Crisp caramelized puff pastry layered with Madagascar vanilla diplomat cream. Features delicate textures, balanced sweetness, and elegant precision plating.', 'Caramelised puff pastry layered with Madagascar vanilla diplomat cream for a delicate balance of crispness, richness, and restraint.']],
            'pistachio-rose-entremet' => ['description' => ['Smooth pistachio mousse with raspberry gel, rose-infused sponge, and a glossy white-chocolate glaze. Floral, nutty, lightly tart, and visually refined.', 'Pistachio mousse with raspberry gel, rose-infused sponge, and a polished white-chocolate glaze.']],
            'yuzu-white-chocolate-cheesecake' => ['description' => ['Creamy white-chocolate cheesecake accented with fresh yuzu citrus, almond crumble, and passion-fruit coulis. Rich yet refreshing with a bright, clean finish.', 'White-chocolate cheesecake brightened with yuzu, almond crumble, and passion-fruit coulis.']],
            'caramelized-pear-and-almond-tart' => ['description' => ['Buttery pastry filled with almond frangipane and caramelized pear, served warm with brown-butter ice cream and vanilla-bean sauce. Comforting, aromatic, and sophisticated.', 'Warm pear and almond-frangipane tart with brown-butter ice cream and vanilla-bean sauce.']],
            'imperial-saffron-champagne-cocktail' => ['description' => ['Premium brut champagne infused with saffron syrup, elderflower liqueur, and a delicate lemon twist. Floral, lightly citrusy, elegant, and effervescent.', 'Brut champagne with saffron syrup, elderflower liqueur, and lemon for a floral, gently citrus finish.']],
            'smoked-fig-bourbon-reserve' => ['description' => ['Small-batch bourbon combined with roasted fig reduction, aromatic bitters, and subtle oak smoke. Rich, smooth, slightly sweet, and visually dramatic.', 'Small-batch bourbon with roasted fig, aromatic bitters, and restrained oak smoke for a smooth, gently sweet finish.']],
            'white-peach-jasmine-elixir' => ['description' => ['White peach nectar, jasmine tea, fresh lemon, and sparkling mineral water. Fragrant, refreshing, delicately sweet, and refined.', 'White peach nectar, jasmine tea, fresh lemon, and sparkling mineral water with a fragrant, refreshing finish.']],
            'black-truffle-espresso-martini' => ['description' => ['Premium vodka, freshly brewed espresso, coffee liqueur, and a restrained black-truffle infusion. Silky, aromatic, earthy, and luxurious.', 'Vodka, freshly brewed espresso, coffee liqueur, and a restrained black-truffle infusion with a silky, aromatic finish.']],
            'golden-yuzu-honey-sparkler' => ['description' => ['Japanese yuzu juice, raw honey, ginger, and premium sparkling water, finished with edible gold flakes. Bright, citrus-forward, refreshing, and visually elegant.', 'Japanese yuzu, raw honey, ginger, and sparkling water for a bright, citrus-forward finish.']],
        ]);

        $gallery = [
            'Elegant Dining Room' => ['title' => 'The Dining Room', 'alt_text' => ['Elegant fine-dining restaurant interior', 'Warmly lit fine-dining room at Le Jardin']],
            'Signature Dish' => ['title' => 'A Signature Composition', 'alt_text' => ['Chef-prepared signature dish presentation', 'Seared scallops presented with refined plating']],
            'Banquet Setup' => ['title' => 'A Private Occasion', 'alt_text' => ['Banquet hall setup for a private event', 'Banquet hall arranged for a private celebration']],
            'Warm Restaurant Ambiance' => ['title' => 'Evening Ambiance', 'alt_text' => ['Warm restaurant lighting and ambiance', 'Warm evening ambiance inside Le Jardin']],
        ];

        foreach ($gallery as $oldTitle => $values) {
            DB::table('gallery_images')
                ->where('title', $oldTitle)
                ->whereIn('alt_text', $values['alt_text'])
                ->update(['alt_text' => $values['alt_text'][1]]);

            DB::table('gallery_images')
                ->whereIn('title', [$oldTitle, $values['title']])
                ->update(['title' => $values['title']]);
        }
    }

    public function down(): void
    {
        // Copy refinements are intentionally retained to avoid overwriting later admin edits.
    }

    /**
     * @param  array<string, array<string, array{0: string, 1: string}>>  $records
     */
    private function replaceDefaults(string $table, string $keyColumn, array $records): void
    {
        foreach ($records as $key => $columns) {
            foreach ($columns as $column => [$oldValue, $newValue]) {
                DB::table($table)
                    ->where($keyColumn, $key)
                    ->whereIn($column, [$oldValue, $newValue])
                    ->update([$column => $newValue]);
            }
        }
    }
};
