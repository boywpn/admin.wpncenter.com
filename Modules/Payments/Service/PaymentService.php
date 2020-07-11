<?php

namespace Modules\Payments\Service;

use Carbon\Carbon;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Modules\Platform\Settings\Entities\Currency;

class PaymentService
{

    /**
     * Returns income vs expenses for currency
     *
     * @param int $currency
     * @param int $lastMonths
     * @return array
     */
    public function incomeVsExpense($currency = Currency::USD, $lastMonths = 2)
    {
        $result = \DB::table('payments')
            ->select(\DB::raw("DATE_FORMAT(payments.payment_date,'%Y-%m') as yearMonth"), \DB::raw('sum(if(income =1,amount,0)) as income'), \DB::raw('sum(if(income =0,amount,0)) as expense'))
            ->groupBy(\DB::raw("DATE_FORMAT(payments.payment_date,'%Y-%m') "))
            ->whereDate('payments.payment_date', '>=', Carbon::today()->subMonth($lastMonths)->firstOfMonth()->format('Y-m-d'))
            ->where('payments.payment_currency_id', '=', $currency)
            ->orderBy(\DB::raw("DATE_FORMAT(payments.payment_date,'%Y-%m')"), 'asc');

        if (Landlord::hasTenant('company_id')) {
            $result = $result->where('payments.company_id', Landlord::getTenantId('company_id'));
        }
        return $result->get()->flatten()->toArray();


    }
}
