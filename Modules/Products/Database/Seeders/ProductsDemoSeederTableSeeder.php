<?php

namespace Modules\Products\Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Platform\Core\Helper\SeederHelper;
use Modules\Products\Entities\PriceList;
use Modules\Products\Entities\Product;

class ProductsDemoSeederTableSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Product::truncate();
        PriceList::truncate();

        \Auth::attempt(['email' => config('bap.demo_company_1'), 'password' => config('bap.demo_company_pass_1')]);


        for ($i = 1; $i <= 20; $i++) {
            $faker = Factory::create();

            $isProduct = rand(1, 2);

            $prodName = 'Product';

            if ($isProduct == 2) {
                $prodName = 'Service';
            }

            $product = new Product();
            $product->id = $i;
            $product->changeOwnerTo(\Auth::user());

            $product->name = "$prodName # ".$faker->company;
            $product->part_number = rand(10000, 39494994);
            $product->vendor_part_number = rand(3999, 4488855);
            $product->serial_no = $faker->ean13;
            $product->price = rand(100, 3949);
            $product->vendor_id = rand(1, 20);
            $product->product_type_id = $isProduct;
            $product->product_category_id = rand(1, 3);
            $product->company_id = $this->firstCompany();
            $product->image_path = '/examples/product_img_'.rand(1,5).'.jpeg';

            $product->save();
        }

        \Auth::attempt(['email' => config('bap.demo_company_2'), 'password' => config('bap.demo_company_pass_2')]);


        for ($i = 21; $i <= 40; $i++) {
            $faker = Factory::create();

            $isProduct = rand(1, 2);

            $prodName = 'Product';

            if ($isProduct == 2) {
                $prodName = 'Service';
            }

            $product = new Product();
            $product->id = $i;
            $product->changeOwnerTo(\Auth::user());

            $product->name = "$prodName # ".$faker->company;
            $product->part_number = rand(10000, 39494994);
            $product->vendor_part_number = rand(3999, 4488855);
            $product->serial_no = $faker->ean13;
            $product->price = rand(100, 3949);
            $product->vendor_id = rand(21, 40);
            $product->product_type_id = $isProduct;
            $product->product_category_id = rand(1, 3);
            $product->company_id = $this->secondCompany();
            $product->image_path = '/examples/product_img_'.rand(1,5).'.jpeg';


            $product->save();
        }
    }
}
