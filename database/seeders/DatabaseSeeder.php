<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MainModule;
use App\Models\SubModule;
use App\Models\ServiceItem;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\BookingItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with clean, non-duplicated data.
     */
    public function run(): void
    {
        // 0. Disable foreign key constraints & truncate tables to prevent duplicates
        Schema::disableForeignKeyConstraints();
        BookingItem::truncate();
        Booking::truncate();
        Customer::truncate();
        ServiceItem::truncate();
        SubModule::truncate();
        MainModule::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Create Super Admin User (Clean Single Record)
        User::updateOrCreate(
            ['email' => 'admin@salon.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Data Structure setup for Main Modules -> Sub Modules -> Service Items
        $modulesData = [
            [
                'name' => 'Hair Care',
                'icon' => 'scissors',
                'description' => 'Premium hair styling, coloring, and revitalizing scalp treatments.',
                'sub_modules' => [
                    [
                        'name' => 'Hair Styling & Cutting',
                        'items' => [
                            ['name' => 'Executive Cut & Blowdry', 'price' => 45.00],
                            ['name' => 'Signature Layered Precision Cut', 'price' => 60.00],
                            ['name' => 'Glossing & Thermal Styling', 'price' => 35.00],
                            ['name' => 'Bridal Hair Updo & Trial', 'price' => 120.00],
                            ['name' => 'Scalp Detox & Styling Blowout', 'price' => 50.00],
                        ]
                    ],
                    [
                        'name' => 'Hair Coloring & Highlights',
                        'items' => [
                            ['name' => 'Full Balayage & Tonal Gloss', 'price' => 140.00],
                            ['name' => 'Root Touch-up & Color Lock', 'price' => 65.00],
                            ['name' => 'Babylights & Full Foil Highlights', 'price' => 125.00],
                            ['name' => 'Ammonia-Free Organic All-Over Color', 'price' => 90.00],
                            ['name' => 'Color Correction & Tone Reset', 'price' => 150.00],
                        ]
                    ],
                    [
                        'name' => 'Hair Spa & Keratin Treatments',
                        'items' => [
                            ['name' => 'Brazilian Keratin Smoothing', 'price' => 160.00],
                            ['name' => 'Deep Argan Oil Moisture Infusion', 'price' => 55.00],
                            ['name' => 'Olaplex Bond Repair Therapy', 'price' => 75.00],
                            ['name' => 'Scalp Exfoliation & Anti-Dandruff Spa', 'price' => 45.00],
                            ['name' => 'Nanoplastia Silk Hair Treatment', 'price' => 180.00],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Skin Care',
                'icon' => 'sparkles',
                'description' => 'Advanced dermatological facials, peels, and brightening remedies.',
                'sub_modules' => [
                    [
                        'name' => 'Advanced Facial Treatments',
                        'items' => [
                            ['name' => 'Hydra-Infusion Deep Cleansing Facial', 'price' => 85.00],
                            ['name' => '24K Gold Luxury Radiance Facial', 'price' => 130.00],
                            ['name' => 'Oxygen Booster Glow Treatment', 'price' => 95.00],
                            ['name' => 'Microdermabrasion Skin Polish', 'price' => 70.00],
                            ['name' => 'Collagen Lifting & Firming Facial', 'price' => 110.00],
                        ]
                    ],
                    [
                        'name' => 'Skin Rejuvenation & Peels',
                        'items' => [
                            ['name' => 'Glycolic Renewal Resurfacing Peel', 'price' => 75.00],
                            ['name' => 'Vitamin C Anti-Aging Elixir Peel', 'price' => 85.00],
                            ['name' => 'LED Light Therapy Skin Rejuvenation', 'price' => 50.00],
                            ['name' => 'Dermaplaning Smooth Polish', 'price' => 65.00],
                            ['name' => 'Hyaluronic Acid Moisture Lock Peel', 'price' => 90.00],
                        ]
                    ],
                    [
                        'name' => 'Anti-Acne & Glow Therapies',
                        'items' => [
                            ['name' => 'Clear Balance Clarifying Facial', 'price' => 80.00],
                            ['name' => 'High-Frequency Acne Purifying Treatment', 'price' => 65.00],
                            ['name' => 'Snail Mucin Deep Hydration Mask', 'price' => 55.00],
                            ['name' => 'Calming Rose & Chamomile Sensitive Polish', 'price' => 60.00],
                            ['name' => 'Pigmentation Correcting Spot Therapy', 'price' => 95.00],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Body Treatment',
                'icon' => 'heart-pulse',
                'description' => 'Full body relaxation massage, scrubs, wraps, and aromatic hydro-spa.',
                'sub_modules' => [
                    [
                        'name' => 'Full Body Massages',
                        'items' => [
                            ['name' => 'Deep Tissue Muscle Relief (60 min)', 'price' => 90.00],
                            ['name' => 'Swedish Relaxation Massage (60 min)', 'price' => 80.00],
                            ['name' => 'Hot Stone Therapeutic Massage (75 min)', 'price' => 115.00],
                            ['name' => 'Thai Stretch & Acupressure Massage', 'price' => 95.00],
                            ['name' => 'Aromatherapy Stress Reliever (60 min)', 'price' => 85.00],
                        ]
                    ],
                    [
                        'name' => 'Detox Body Scrubs & Wraps',
                        'items' => [
                            ['name' => 'Himalayan Sea Salt Body Exfoliator', 'price' => 65.00],
                            ['name' => 'Organic Coffee Bean Cellulite Scrub', 'price' => 70.00],
                            ['name' => 'Seaweed Detoxifying Body Wrap', 'price' => 95.00],
                            ['name' => 'Hydrating Honey & Milk Skin Polish', 'price' => 75.00],
                            ['name' => 'Dead Sea Mud Firming Cocoon', 'price' => 105.00],
                        ]
                    ],
                    [
                        'name' => 'Luxury Aroma Hydro-Spa',
                        'items' => [
                            ['name' => 'Eucalyptus Essential Oil Steam Hydro Bath', 'price' => 50.00],
                            ['name' => 'Rose Petal & Coconut Milk Hydro Soaking', 'price' => 60.00],
                            ['name' => 'Infrared Sauna Detox Session (45 min)', 'price' => 40.00],
                            ['name' => 'Herbal Hydro-Therapy Foot & Leg Bath', 'price' => 35.00],
                            ['name' => 'Complete Head-to-Toe Hydro Rejuvenation', 'price' => 125.00],
                        ]
                    ],
                ]
            ]
        ];

        $allItems = [];

        foreach ($modulesData as $mGroup) {
            $mainModule = MainModule::updateOrCreate(
                ['name' => $mGroup['name']],
                ['icon' => $mGroup['icon'], 'description' => $mGroup['description']]
            );

            foreach ($mGroup['sub_modules'] as $sGroup) {
                $subModule = SubModule::updateOrCreate(
                    ['main_module_id' => $mainModule->id, 'name' => $sGroup['name']]
                );

                foreach ($sGroup['items'] as $item) {
                    $createdItem = ServiceItem::updateOrCreate(
                        ['sub_module_id' => $subModule->id, 'name' => $item['name']],
                        ['price' => $item['price']]
                    );
                    $allItems[] = $createdItem;
                }
            }
        }

        // 3. Seed Unique Customers
        $sampleCustomers = [
            ['name' => 'Sophia Montgomery', 'phone' => '+1 (555) 234-5678', 'email' => 'sophia@example.com', 'address' => '742 Evergreen Terrace, Suite 4B'],
            ['name' => 'Elena Rostova', 'phone' => '+1 (555) 876-5432', 'email' => 'elena.r@example.com', 'address' => '104 Beverly Hills Dr, Los Angeles'],
            ['name' => 'Claire Harrison', 'phone' => '+1 (555) 345-6789', 'email' => 'claire.h@example.com', 'address' => '12 Madison Ave, New York'],
            ['name' => 'Isabella Chen', 'phone' => '+1 (555) 901-2345', 'email' => 'isabella.c@example.com', 'address' => '55 Ocean Drive, Miami'],
            ['name' => 'Victoria Sterling', 'phone' => '+1 (555) 654-9870', 'email' => 'victoria@example.com', 'address' => '88 Park Avenue, New York'],
            ['name' => 'Amelia Vance', 'phone' => '+1 (555) 432-1098', 'email' => 'amelia.v@example.com', 'address' => '15 Sunset Blvd, Los Angeles'],
            ['name' => 'Charlotte Dubois', 'phone' => '+1 (555) 789-0123', 'email' => 'charlotte@example.com', 'address' => '220 Fifth Ave, New York'],
            ['name' => 'Olivia Martinez', 'phone' => '+1 (555) 321-6547', 'email' => 'olivia.m@example.com', 'address' => '77 Lombard St, San Francisco'],
            ['name' => 'Harper Jenkins', 'phone' => '+1 (555) 987-6543', 'email' => 'harper.j@example.com', 'address' => '400 Michigan Ave, Chicago'],
            ['name' => 'Evelyn Hayes', 'phone' => '+1 (555) 654-3210', 'email' => 'evelyn.h@example.com', 'address' => '303 Peachtree St, Atlanta'],
            ['name' => 'Mia Rodriguez', 'phone' => '+1 (555) 543-2109', 'email' => 'mia.r@example.com', 'address' => '500 Texas Ave, Houston'],
            ['name' => 'Ava Thompson', 'phone' => '+1 (555) 876-1234', 'email' => 'ava.t@example.com', 'address' => '120 Pine St, Seattle'],
        ];

        $customerModels = [];
        foreach ($sampleCustomers as $cData) {
            $customerModels[] = Customer::updateOrCreate(['email' => $cData['email']], $cData);
        }

        // 4. Seed Initial Clean Bookings (12 distinct bookings for pagination)
        $statuses = ['completed', 'confirmed', 'pending'];
        $types = ['quotation', 'lead'];

        for ($i = 1; $i <= 12; $i++) {
            $customer = $customerModels[($i - 1) % count($customerModels)];
            $type = $types[$i % 2];
            $status = $statuses[$i % 3];
            $createdDate = now()->subDays($i * 3);

            // Select 2 items per booking
            $item1 = $allItems[($i * 2) % count($allItems)];
            $item2 = $allItems[($i * 2 + 1) % count($allItems)];
            $totalAmount = $item1->price + $item2->price;

            $booking = Booking::create([
                'booking_number' => 'SB-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'type' => $type,
                'status' => $status,
                'total_amount' => $totalAmount,
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ]);

            BookingItem::create([
                'booking_id' => $booking->id,
                'service_item_id' => $item1->id,
                'price_at_booking' => $item1->price,
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ]);

            BookingItem::create([
                'booking_id' => $booking->id,
                'service_item_id' => $item2->id,
                'price_at_booking' => $item2->price,
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ]);
        }
    }
}
