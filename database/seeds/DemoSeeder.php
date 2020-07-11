<?php

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Spatie\Activitylog\Models\Activity::truncate();
        \Bnb\Laravel\Attachments\Attachment::truncate();

        $this->call( \Modules\Platform\Notifications\Database\Seeders\NotificationsDemoSeeder::class);

        $this->call(\Modules\Payments\Database\Seeders\PaymentDemoSeederTableSeeder::class);
        $this->call(\Modules\Campaigns\Database\Seeders\CampaignDemoSeederTableSeeder::class);
        $this->call(\Modules\Leads\Database\Seeders\LeadDemoSeederTableSeeder::class);
        $this->call(\Modules\Accounts\Database\Seeders\AccountDemoSeederTableSeeder::class);
        $this->call(\Modules\Contacts\Database\Seeders\ContactDemoSeederTableSeeder::class);
        $this->call(\Modules\Products\Database\Seeders\ProductsDemoSeederTableSeeder::class);
        $this->call(\Modules\Deals\Database\Seeders\DealDemoSeederTableSeeder::class);
        $this->call(\Modules\Quotes\Database\Seeders\QuoteDemoSeederTableSeeder::class);
        $this->call(\Modules\Tickets\Database\Seeders\TicketDemoSeederTableSeeder::class);
        $this->call(\Modules\ServiceContracts\Database\Seeders\ServiceContractDemoSeederTableSeeder::class);
        $this->call(\Modules\Assets\Database\Seeders\AssetsDemoSeederTableSeeder::class);
        $this->call(\Modules\Vendors\Database\Seeders\VendorDemoSeederTableSeeder::class);
        $this->call(\Modules\Documents\Database\Seeders\DocumentDemoSeederTableSeeder::class);
        $this->call(\Modules\Calendar\Database\Seeders\CalendarDemoSeederTableSeeder::class);
        $this->call(\Modules\Invoices\Database\Seeders\InvoicesDemoSeederTableSeeder::class);
        $this->call(\Modules\Orders\Database\Seeders\OrdersDemoSeederTableSeeder::class);

    }
}
