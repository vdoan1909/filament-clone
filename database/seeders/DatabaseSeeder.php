<?php

namespace Database\Seeders;

use App\Enums\OrderPayment;
use App\Enums\OrderStatus;
use App\Models\Blog\BlogAuthor;
use App\Models\Blog\BlogCategory;
use App\Models\Blog\Link;
use App\Models\Blog\Post;
use App\Models\City;
use App\Models\Country;
use App\Models\Role;
use App\Models\Shop\Brand;
use App\Models\Shop\Customer;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use App\Models\Shop\ShopCategory;
use App\Models\State;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $role = Role::create(
            [
                'name' => 'Admin',
                'slug' => 'admin',
            ]
        );

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password')
        ]);

        DB::table('role_users')->insert([
            'role_id' => $role->id,
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        for ($i = 1; $i < 61; $i++) {
            ShopCategory::create(
                [
                    'name' => fake()->name,
                    'is_active' => rand(1, 0)
                ]
            );
        }

        for ($i = 1; $i < 61; $i++) {
            Brand::create(
                [
                    'name' => fake()->name,
                    'website' => fake()->url,
                    'is_active' => rand(1, 0)
                ]
            );
        }

        $categories = ShopCategory::all();
        $brands = Brand::all();

        for ($i = 1; $i < 61; $i++) {
            Product::create([
                'shop_category_id' => $categories->random()->id,
                'brand_id' => $brands->random()->id,
                'name' => fake()->unique()->word . ' Product',
                'slug' => fake()->unique()->slug,
                'sku' => strtoupper(Str::random(8)),
                'description' => fake()->text(),
                'old_price' => fake()->randomFloat(2, 10, 100),
                'price' => fake()->randomFloat(2, 333, 666),
                'quantity' => rand(1, 100),
                'security_stock' => rand(1, 20),
                'published_at' => now(),
                'seo_title' => fake()->sentence(),
                'seo_description' => fake()->paragraph(),
                'is_active' => rand(0, 1)
            ]);
        }

        $countries = [
            ['name' => 'Vietnam'],
            ['name' => 'Russia'],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }

        $statesVietnam = [
            ['name' => 'Hanoi', 'country_id' => Country::where('name', 'Vietnam')->first()->id],
            ['name' => 'Ho Chi Minh City', 'country_id' => Country::where('name', 'Vietnam')->first()->id],
            ['name' => 'Da Nang', 'country_id' => Country::where('name', 'Vietnam')->first()->id],
            ['name' => 'Hai Phong', 'country_id' => Country::where('name', 'Vietnam')->first()->id],
            ['name' => 'Can Tho', 'country_id' => Country::where('name', 'Vietnam')->first()->id],
        ];

        foreach ($statesVietnam as $state) {
            State::create($state);
        }

        $statesRussia = [
            ['name' => 'Moscow', 'country_id' => Country::where('name', 'Russia')->first()->id],
            ['name' => 'Saint Petersburg', 'country_id' => Country::where('name', 'Russia')->first()->id],
            ['name' => 'Novosibirsk', 'country_id' => Country::where('name', 'Russia')->first()->id],
            ['name' => 'Yekaterinburg', 'country_id' => Country::where('name', 'Russia')->first()->id],
            ['name' => 'Nizhny Novgorod', 'country_id' => Country::where('name', 'Russia')->first()->id],
        ];

        foreach ($statesRussia as $state) {
            State::create($state);
        }

        $citiesVietnam = [
            ['name' => 'Hanoi', 'state_id' => State::where('name', 'Hanoi')->first()->id],
            ['name' => 'Ho Chi Minh City', 'state_id' => State::where('name', 'Ho Chi Minh City')->first()->id],
            ['name' => 'Da Nang', 'state_id' => State::where('name', 'Da Nang')->first()->id],
            ['name' => 'Hai Phong', 'state_id' => State::where('name', 'Hai Phong')->first()->id],
            ['name' => 'Can Tho', 'state_id' => State::where('name', 'Can Tho')->first()->id],
        ];

        foreach ($citiesVietnam as $city) {
            City::create($city);
        }

        $citiesRussia = [
            ['name' => 'Moscow', 'state_id' => State::where('name', 'Moscow')->first()->id],
            ['name' => 'Saint Petersburg', 'state_id' => State::where('name', 'Saint Petersburg')->first()->id],
            ['name' => 'Novosibirsk', 'state_id' => State::where('name', 'Novosibirsk')->first()->id],
            ['name' => 'Yekaterinburg', 'state_id' => State::where('name', 'Yekaterinburg')->first()->id],
            ['name' => 'Nizhny Novgorod', 'state_id' => State::where('name', 'Nizhny Novgorod')->first()->id],
        ];

        foreach ($citiesRussia as $city) {
            City::create($city);
        }

        for ($i = 1; $i < 61; $i++) {
            BlogCategory::create(
                [
                    'name' => fake()->name,
                    'is_active' => rand(1, 0)
                ]
            );
        }

        for ($i = 1; $i < 61; $i++) {
            Link::create(
                [
                    'url' => fake()->url,
                    'image' => 'link-images/meo.jpg',
                    'title' => fake()->sentence(),
                    'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                    'description' => fake()->paragraph()
                ]
            );
        }

        for ($i = 1; $i < 61; $i++) {
            BlogAuthor::create(
                [
                    'name' => fake()->name,
                    'email' => fake()->safeEmail,
                    'photo' => fake()->imageUrl,
                    'bio' => 'bio ' . $i,
                    'github_handle' => 'vip' . rand(666, 999),
                    'twitter_handle' => 'vip' . rand(666, 999),
                ]
            );
        }

        $blogCategories = BlogCategory::all();
        $blogAuthors = BlogAuthor::all();

        for ($i = 1; $i < 61; $i++) {
            Post::create(
                [
                    'blog_author_id' => $blogAuthors->random()->id,
                    'blog_category_id' => $blogCategories->random()->id,
                    'title' => fake()->sentence(),
                    'image' => 'post-images/dsq2-jean-behind-vip.webp',
                    'content' => fake()->text(),
                    'published_at' => now()
                ]
            );
        }

        $countries = Country::all();
        $states = State::all();
        $cities = City::all();

        for ($i = 1; $i < 61; $i++) {
            $randomCreatedAt = \Carbon\Carbon::now()->subDays(rand(0, 365));
            $randomUpdatedAt = (clone $randomCreatedAt)->addDays(rand(0, 30));

            Customer::create(
                [
                    'country_id' => $countries->random()->id,
                    'state_id' => $states->random()->id,
                    'city_id' => $cities->random()->id,
                    'name' => fake()->name,
                    'email' => fake()->safeEmail,
                    'photo' => 'customer-images/pp-scaled.jpg',
                    'created_at' => $randomCreatedAt,
                    'updated_at' => $randomUpdatedAt
                ]
            );
        }

        $customers = Customer::all();
        $data = [];

        $orderStatuses = OrderStatus::cases();
        $orderPayments = OrderPayment::cases();

        for ($i = 1; $i < 61; $i++) {
            $randomCreatedAt = \Carbon\Carbon::now()->subDays(rand(0, 365));
            $randomUpdatedAt = (clone $randomCreatedAt)->addDays(rand(0, 30));

            $data[] = [
                'customer_id' => $customers->random()->id,
                'number' => 'D2-' . rand(666666, 999999),
                'total_price' => rand(111111, 999999),
                'notes' => 'nhanh nhe',
                'status_order' => $orderStatuses[array_rand($orderStatuses)]->value,
                'status_payment' => $orderPayments[array_rand($orderPayments)]->value,
                'currency' => 'USD',
                'created_at' => $randomCreatedAt,
                'updated_at' => $randomUpdatedAt
            ];
        }

        Order::insert($data);

        $orders = Order::all();
        $products = Product::all();

        foreach ($orders as $order) {
            $order->items()->create([
                'order_id' => $order->id,
                'product_id' => $products->random()->id
            ]);
        }

    }
}
