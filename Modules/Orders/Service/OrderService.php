<?php

namespace Modules\Orders\Service;

use HipsterJazzbo\Landlord\Facades\Landlord;
use Modules\Invoices\Entities\Invoice;
use Modules\Invoices\Entities\InvoiceRow;
use Modules\Orders\Entities\Order;
use Modules\Orders\Entities\OrderRow;

/**
 * Class OrderService
 * @package Modules\Orders\Service
 */
class OrderService
{

    /**
     * Convert Order to Invoice
     * @param $orderId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function convertToInvoice($orderId){

        $order = Order::findOrFail($orderId);

        $invoice = new Invoice();
        $invoice->fill($order->toArray());
        $invoice->order_id = $order->id;

        if($order->owner != null ) {
            $invoice->changeOwnerTo($order->owner);
        }

        $invoice->save();

        foreach ($order->rows as $row){

            $invoiceRow = new InvoiceRow();
            $invoiceRow->fill($row->toArray());
            $invoiceRow->invoice_id = $invoice->id;
            $invoiceRow->save();
        }

        return $invoice;
    }

    /**
     * Count order by status
     *
     * @param $status
     * @return mixed
     */
    public function countByStatus($status)
    {
        $orders = Order::where('order_status_id', $status);

        if (Landlord::hasTenant('company_id')) {
            $orders->where('company_id', Landlord::getTenantId('company_id'));
        }

        return $orders->count();

    }

    /**
     * Create, Update, Remove Order Rows
     *
     * @param $entity
     * @param $rows
     */
    public function saveRows($entity, $rows)
    {
        $ids = [];;

        foreach ($rows as $row) {
            $row['order_id'] = $entity->id;

            if ($row['id'] > 0) { // Find and update

                $record = $entity->rows()->find($row['id']);
                $record->fill($row);
                $record->save();
            } else { // Create new record
                $record = new OrderRow();
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
