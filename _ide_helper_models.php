<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $customer_name
 * @property string $email
 * @property string|null $phone
 * @property string|null $subject
 * @property string $message
 * @property bool $is_read
 * @property \Carbon\CarbonImmutable|null $notification_sent_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry latestFirst()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry whereNotificationSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactInquiry whereUpdatedAt($value)
 */
	class ContactInquiry extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $title
 * @property string|null $alt_text
 * @property string $image_path
 * @property string|null $category
 * @property int $sort_order
 * @property bool $is_visible
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage visible()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage whereAltText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GalleryImage whereUpdatedAt($value)
 */
	class GalleryImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $sort_order
 * @property bool $is_visible
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuItem> $menuItems
 * @property-read int|null $menu_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MenuItem> $visibleMenuItems
 * @property-read int|null $visible_menu_items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory visible()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuCategory whereUpdatedAt($value)
 */
	class MenuCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $menu_category_id
 * @property string $name
 * @property string|null $slug
 * @property string|null $description
 * @property numeric|null $price
 * @property string|null $image_path
 * @property int $sort_order
 * @property bool $is_visible
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\MenuCategory $menuCategory
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem ordered()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem visible()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereMenuCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MenuItem whereUpdatedAt($value)
 */
	class MenuItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $customer_name
 * @property string $phone
 * @property string $email
 * @property string $fulfillment_type
 * @property string $preferred_time
 * @property string|null $order_details
 * @property string|null $special_instructions
 * @property string|null $delivery_address
 * @property bool $is_read
 * @property \Carbon\CarbonImmutable|null $notification_sent_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderInquiryItem> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry latestFirst()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry whereDeliveryAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry whereFulfillmentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry whereNotificationSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry whereOrderDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry wherePreferredTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry whereSpecialInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiry whereUpdatedAt($value)
 */
	class OrderInquiry extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_inquiry_id
 * @property int|null $menu_item_id
 * @property string $item_name_snapshot
 * @property numeric|null $display_price_snapshot
 * @property int $quantity
 * @property string|null $notes
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\MenuItem|null $menuItem
 * @property-read \App\Models\OrderInquiry $orderInquiry
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiryItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiryItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiryItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiryItem whereDisplayPriceSnapshot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiryItem whereItemNameSnapshot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiryItem whereMenuItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiryItem whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiryItem whereOrderInquiryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiryItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderInquiryItem whereUpdatedAt($value)
 */
	class OrderInquiryItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $excerpt
 * @property string|null $content
 * @property array<array-key, mixed>|null $sections
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property bool $is_published
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereSections($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereUpdatedAt($value)
 */
	class Page extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $customer_name
 * @property string $phone
 * @property string $email
 * @property \Carbon\CarbonImmutable $preferred_date
 * @property string $preferred_time
 * @property int $guest_count
 * @property string|null $special_requests
 * @property bool $is_banquet_or_event
 * @property bool $is_read
 * @property \Carbon\CarbonImmutable|null $notification_sent_at
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest latestFirst()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest whereGuestCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest whereIsBanquetOrEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest whereNotificationSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest wherePreferredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest wherePreferredTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest whereSpecialRequests($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReservationRequest whereUpdatedAt($value)
 */
	class ReservationRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string|null $group
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting group(string $group)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting whereValue($value)
 */
	class SiteSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passkeys\Passkey> $passkeys
 * @property-read int|null $passkeys_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Laravel\Fortify\Contracts\PasskeyUser, \Laravel\Passkeys\Contracts\PasskeyUser {}
}

