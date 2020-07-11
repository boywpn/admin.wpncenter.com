<?php

namespace Modules\Invoices\Service;

use HipsterJazzbo\Landlord\Facades\Landlord;
use Modules\Invoices\Entities\Invoice;
use Modules\Invoices\Entities\InvoiceRow;

/**
 * Class InvoiceService
 * @package Modules\Invoices\Service
 */
class InvoiceService
{

    /**
     * Count by status
     *
     * @param $status
     * @return mixed
     */
    public function countByStatus($status)
    {

        $invoices = Invoice::where('invoice_status_id', $status);

        if (Landlord::hasTenant('company_id')) {
            $invoices->where('company_id', Landlord::getTenantId('company_id'));
        }

        return $invoices->count();

    }


    /**
     * Create, Update, Remove Invoice Rows
     *
     * @param $entity
     * @param $rows
     */
    public function saveRows($entity, $rows)
    {
        $ids = [];
        ;

        foreach ($rows as $row) {
            $row['invoice_id'] = $entity->id;

            if($row['price_list_id'] == '' ){
                $row['price_list_id'] = null;
            }

            if ($row['id'] > 0) { // Find and update

                $record = $entity->rows()->find($row['id']);
                $record->fill($row);
                $record->save();
            } else { // Create new record
                $record = new InvoiceRow();
                $record->fill($row);
                $record->save();
            }

            $ids[] = $record->id;
        }

        foreach ($entity->rows as $row) {
            if (!in_array($row->id, $ids)) { // Record is not in array of post ids - record was removed
                $row->delete();
            }
        }
    }
}
