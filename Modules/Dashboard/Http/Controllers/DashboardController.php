<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Charts\IncomeVsExpense;
use App\Charts\LeadOverview;
use App\Charts\TicketOverview;
use HipsterJazzbo\Landlord\Facades\Landlord;
use Modules\Calendar\Service\CalendarService;
use Modules\Contacts\Entities\Contact;
use Modules\Contacts\Service\ContactService;
use Modules\Dashboard\Datatables\TicketDatatable;
use Modules\Invoices\Entities\Invoice;
use Modules\Invoices\Service\InvoiceService;
use Modules\Leads\Entities\Lead;
use Modules\Leads\Service\LeadService;
use Modules\Orders\Entities\Order;
use Modules\Orders\Service\OrderService;
use Modules\Payments\Service\PaymentService;
use Modules\Platform\Core\Http\Controllers\AppBaseController;
use Modules\Tickets\Datatables\Scope\TicketStatusScope;
use Modules\Tickets\Entities\Ticket;
use Modules\Tickets\Service\TicketService;

/**
 * Class DashboardController
 * @package Modules\Dashboard\Http\Controllers
 */
class DashboardController extends AppBaseController
{

    /**
     * Dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $view = view('dashboard::index');

        $leadService = \App::make(LeadService::class);
        $contactService = \App::make(ContactService::class);
        $orderService = \App::make(OrderService::class);
        $invoiceService = \App::make(InvoiceService::class);
        $paymentsService = \App::make(PaymentService::class);
        $ticketService   = \App::make(TicketService::class);

        $countLeads = $leadService->countByStatus(Lead::STATUS_NEW);
        $countContacts = $contactService->countByStatus(Contact::STATUS_NEW);
        $countOrders = $orderService->countByStatus(Order::STATUS_CREATED);
        $countInvoices = $invoiceService->countByStatus(Invoice::STATUS_CREATED);


        $view->with('countContacts', $countContacts);
        $view->with('countLeads', $countLeads);
        $view->with('countOrders', $countOrders);
        $view->with('countInvoices', $countInvoices);


        $ticketDatatable = new TicketDatatable();
        $ticketDatatable->setTableId('TicketDatatable');
        $ticketDatatable->setAjaxSource(route('dashboard.new-tickets'));

        $view->with('leadOverview',new LeadOverview($leadService->groupByStatus()));
        $view->with('ticketStatusOverview', new TicketOverview($ticketService->groupByStatus()));
        $view->with('ticketPriorityOverview', new TicketOverview($ticketService->groupByPriority(),'#FF9800'));
        $view->with('incomeVsExpense',new IncomeVsExpense($paymentsService->incomeVsExpense()));

        $view->with('ticketDatatable', $ticketDatatable->html());

        return $view;
    }

    /**
     * @param TicketDatatable $ticketDatatable
     * @return mixed
     */
    public function getNewTickets(TicketDatatable $ticketDatatable)
    {
        $ticketDatatable->addScope(new TicketStatusScope(Ticket::STATUS_NEW));

        return $ticketDatatable->render('core::crud.module.modal-datatable');
    }
}
