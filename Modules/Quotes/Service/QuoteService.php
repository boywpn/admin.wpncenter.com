<?php

namespace Modules\Quotes\Service;

use Modules\Orders\Entities\Order;
use Modules\Orders\Entities\OrderRow;
use Modules\Orders\Entities\OrderStatus;
use Modules\Quotes\Entities\Quote;
use Modules\Quotes\Entities\QuoteRow;

/**
 * Class QuoteService
 * @package Modules\Quotes\Service
 */
class QuoteService
{

    /**
     * Convert quote to order
     *
     * @param $quoteId
     * @return Order
     */
    public function convertQuoteToOrder($quoteId){

        $quote = Quote::findOrFail($quoteId);

        $order = new Order();
        $order->fill($quote->toArray());

        $order->bill_city = $quote->city;
        $order->bill_street = $quote->street;
        $order->bill_state = $quote->state;
        $order->bill_country = $quote->country;
        $order->bill_zip_code = $quote->zip_code;
        $order->order_status_id = OrderStatus::CREATED;

        if($quote->owner != null ) {
            $order->changeOwnerTo($quote->owner);
        }

        $order->save();

        foreach ($quote->rows as $row){

            $orderRow = new OrderRow();
            $orderRow->fill($row->toArray());
            $orderRow->order_id = $order->id;
            $orderRow->save();
        }

        return $order;
    }

    /**
     * Create, Update, Remove Quote Rows
     *
     * @param $entity
     * @param $rows
     */
    public function saveRows($entity, $rows)
    {
        $ids = [];
        ;

        foreach ($rows as $row) {
            $row['quote_id'] = $entity->id;

            if($row['product_id'] == ''){
                $row['product_id'] = null;
            }
            if ($row['id'] > 0) { // Find and update

                $record = $entity->rows()->find($row['id']);
                $record->fill($row);
                $record->save();
            } else { // Create new record
                $record = new QuoteRow();
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
