<?php

namespace Sinevia\Business\Http\Controllers;

/**
 * Contains the business functionality
 */
class BusinessController extends \Illuminate\Routing\Controller {

    function anyIndex() {
        return $this->anyDashboard();
    }

    function getBookings($from, $to, $customerId) {
        $query = \App\Models\Bookings\Booking::where('Status', \App\Models\Bookings\Booking::STATUS_APPROVED)
                ->where('Ends', '>=', $from)
                ->where('Ends', '<=', $to);
        if ($customerId != "") {
            $query = $query->where('CustomerId', $customerId);
        }
        $bookings = $query->get();

        $data = [];
        foreach ($bookings as $b) {
            //console.log(created)
            $year = date('Y', strtotime($b->Ends));
            $month = date('m', strtotime($b->Ends));
            if (isset($data[$year . '.' . $month]) == false) {
                $data[$year . '.' . $month] = [];
            }
            $data[$year . '.' . $month][] = $b->Price;
        }

        $begin = new \DateTime($from);
        $end = new \DateTime($to);
        $end = $end->modify('+1 day');

        $daterange = new \DatePeriod($begin, (new \DateInterval('P1M')), $end);

        $income = [];
        foreach ($daterange as $date) {
            //echo $date->format("Ymd") . "<br>";

            $year = $date->format("Y");
            $month = $date->format("m");

            $income[$year . ' ' . $month] = 0; // Default

            if (isset($data[$year . '.' . $month])) {
                $income[$year . ' ' . $month] = array_sum($data[$year . '.' . $month]);
            }
        }
        return $income;
    }

    function getCredit($from, $to, $customerId) {
        $query = \Sinevia\Business\Models\Transaction::where('IsCredit', 'Yes');
        //$query = $query->where('CreatedAt', '>=', $from)->where('CreatedAt', '<=', $to);
        $query = $query->where('Date', '>=', $from)->where('Date', '<=', $to);
        if ($customerId != "") {
            //$query = $query->where('CustomerId', $customerId);
        }
        $transactions = $query->get();

        $data = [];
        foreach ($transactions as $b) {
            //console.log(created)
            $year = date('Y', strtotime($b->Date));
            $month = date('m', strtotime($b->Date));
            if (isset($data[$year . '.' . $month]) == false) {
                $data[$year . '.' . $month] = [];
            }
            $data[$year . '.' . $month][] = $b->Amount;
        }
        /*
          $bookings = $query->get();

          $data = [];
          foreach ($bookings as $b) {
          //console.log(created)
          $year = date('Y', strtotime($b->Ends));
          $month = date('m', strtotime($b->Ends));
          if (isset($data[$year . '.' . $month]) == false) {
          $data[$year . '.' . $month] = [];
          }
          $data[$year . '.' . $month][] = $b->Price;
          }
         *
         */

        $begin = new \DateTime($from);
        $end = new \DateTime($to);
        $end = $end->modify('+1 day');

        $daterange = new \DatePeriod($begin, (new \DateInterval('P1M')), $end);

        $income = [];
        foreach ($daterange as $date) {
            //echo $date->format("Ymd") . "<br>";

            $year = $date->format("Y");
            $month = $date->format("m");

            $income[$year . ' ' . $month] = 0; // Default

            if (isset($data[$year . '.' . $month])) {
                $income[$year . ' ' . $month] = array_sum($data[$year . '.' . $month]);
            }
        }
        return $income;
    }

    function getDebit($from, $to, $customerId) {
        $query = \Sinevia\Business\Business\Transaction::where('IsDebit', 'Yes');
        //$query = $query->where('CreatedAt', '>=', $from)->where('CreatedAt', '<=', $to);
        $query = $query->where('Date', '>=', $from)->where('Date', '<=', $to);
        if ($customerId != "") {
            //$query = $query->where('CustomerId', $customerId);
        }
        $transactions = $query->get();

        $data = [];
        foreach ($transactions as $b) {
            //console.log(created)
            $year = date('Y', strtotime($b->Date));
            $month = date('m', strtotime($b->Date));
            if (isset($data[$year . '.' . $month]) == false) {
                $data[$year . '.' . $month] = [];
            }
            $data[$year . '.' . $month][] = $b->Amount;
        }

        $begin = new \DateTime($from);
        $end = new \DateTime($to);
        $end = $end->modify('+1 day');

        $daterange = new \DatePeriod($begin, (new \DateInterval('P1M')), $end);

        $income = [];
        foreach ($daterange as $date) {
            //echo $date->format("Ymd") . "<br>";

            $year = $date->format("Y");
            $month = $date->format("m");

            $income[$year . ' ' . $month] = 0; // Default

            if (isset($data[$year . '.' . $month])) {
                $income[$year . ' ' . $month] = array_sum($data[$year . '.' . $month]);
            }
        }
        return $income;
    }

    function anyDashboard() {
        $filterFrom = request('filter_from', date('Y-m-01', strtotime('-1year')));
        $filterTo = request('filter_to', date('Y-m-t'));
        $filterCustomerId = request('filter_customer_id', '');
        $customerList = \Sinevia\Business\Helpers\Helper::customerInstance()->whereStatus('Active')->get();

        $income = [];//$this->getBookings($filterFrom, $filterTo, $filterCustomerId);
        $debit = [];//$this->getDebit($filterFrom, $filterTo, $filterCustomerId);
        $credit = [];//$this->getCredit($filterFrom, $filterTo, $filterCustomerId);
        // Temporarily use Bookings together with transactions
        $temp = [];
        foreach ($credit as $k => $v) {
            $existingValue = isset($temp[$k]) ? $temp[$k] : 0;
            $temp[$k] = $existingValue + $v;
        }
        foreach ($income as $k => $v) {
            $existingValue = isset($temp[$k]) ? $temp[$k] : 0;
            $temp[$k] = $existingValue + $v;
        }
        $credit = $temp;

        return view('business::admin/dashboard', get_defined_vars());
    }

    function getCustomerInvoiceView() {
        $invoice = \Sinevia\Business\Models\Invoice::find(request('InvoiceId'));
        if ($invoice == null) {
            return redirect()->back()->withErrors('Invoice not found');
        }
        $customer = \Sinevia\Business\Models\Customer::find($invoice->CustomerId);
        $customerName = is_null($customer) ? 'Unknown customer' : $customer->FirstName . ' ' . $customer->LastName;
        $customerAddress1 = is_null($customer) ? 'Unknown customer' : $customer->Address1;
        $customerAddress2 = is_null($customer) ? 'Unknown customer' : $customer->Address2;
        $customerCity = is_null($customer) ? 'Unknown customer' : $customer->City;
        $customerProvince = is_null($customer) ? 'Unknown customer' : $customer->Province;
        $customerCountry = is_null($customer) ? '' : $customer->Country;
        $country = \Sinevia\Business\Models\Country::byIso2($customer->Country);
        if (is_null($country) == false) {
            $customerCountry = $country->Name;
        }
        $customerPostCode = is_null($customer) ? 'Unknown customer' : $customer->Postcode;

        $invoiceId = $invoice->Id;
        $reference = $invoice->Reference;
        $issuedAt = (trim($invoice->IssuedOn) == '') ? date('d M Y', strtotime($invoice->CreatedAt)) : date('d M Y', strtotime($invoice->IssuedOn));
        $dueAt = (trim($invoice->DueOn) == '') ? date('d M Y', (strtotime($issuedAt) + 7 * 24 * 60 * 60)) : date('d M Y', strtotime($invoice->DueOn));
        $discount = $invoice->Discount;
        $discountDescription = $invoice->DiscountDescription;
        $currency = $invoice->Currency;
        $tax = $invoice->Tax;
        $total = $invoice->Total;
        $subtotal = $invoice->Subtotal;

        $invoiceItems = \Sinevia\Business\Models\InvoiceItem::where('InvoiceId', $invoice->Id)->get(); //$invoice->items->get();
        $invoiceItemList = [];
        foreach ($invoiceItems as $item) {
            $invoiceItemList[] = [
                'Id' => $item->Id,
                'Details' => $item->Details,
                'PricePerUnit' => $item->PricePerUnit,
                'Units' => $item->Units,
                'Total' => $item->Total,
            ];
        }

        return view('business::customer/invoice-view', get_defined_vars());
    }

    function getInvoiceManager() {
        $session_order_by = \Session::get('business_invoice_manager_by', 'Id');
        $session_order_sort = \Session::get('business_invoice_manager_sort', 'desc');

        $filterId = request('filter_id', '');
        $filterStarts = request('filter_starts', '');
        $filterEnds = request('filter_ends', '');
        $filterStatus = request('filter_status', '');
        $orderby = request('by', $session_order_by);
        $sort = request('sort', $session_order_sort);
        $page = request('page', 0);
        $results_per_page = 20;

        \Session::put('business_transaction_manager_by', $orderby); // Keep for session
        \Session::put('business_transaction_manager_sort', $sort);  // Keep for session

        $q = \Sinevia\Business\Models\Invoice::getModel();

        if ($filterId) {
            $q = $q->orWhere('Id', 'LIKE', '%' . $filterId . '%');
            $q = $q->orWhere('Reference', 'LIKE', '%' . $filterId . '%');
        }
        if ($filterStarts AND $filterEnds) {
            $q = $q->where('CreatedAt', '>=', $filterStarts . ' 00:00:00');
            $q = $q->where('CreatedAt', '<=', $filterEnds . ' 23:59:59');
        } else if ($filterStarts) {
            $q = $q->where('CreatedAt', '>=', $filterStarts . ' 00:00:00');
        } else if ($filterEnds) {
            $q = $q->where('CreatedAt', '<=', $filterEnds . ' 23:59:59');
        }
        if ($filterStatus != "") {
            $q = $q->where('Status', '=', $filterStatus);
        } else {
            $q = $q->where('Status', '<>', 'Deleted');
        }

        if ($orderby == "Title") {
            $orderby = 'Id';
        }
        $q = $q->orderBy($orderby, $sort);

        $statusList = [
            \Sinevia\Business\Models\Invoice::STATUS_DRAFT => 'Draft',
            \Sinevia\Business\Models\Invoice::STATUS_PAID => 'Paid',
            \Sinevia\Business\Models\Invoice::STATUS_UNPAID => 'Unpaid',
            \Sinevia\Business\Models\Invoice::STATUS_DELETED => 'Deleted',
        ];

        $invoices = $q->paginate($results_per_page);

        return view('business::admin/invoice-manager', get_defined_vars());

        $user = $this->user;
        $business = $this->business;
        $message = isset($_REQUEST['msg']) == false ? '' : trim($_REQUEST['msg']);
        $invoiceId = isset($_REQUEST['filterInvoiceId']) == false ? '' : trim($_REQUEST['filterInvoiceId']);
        $customerId = isset($_REQUEST['filterCustomerId']) == false ? '' : trim($_REQUEST['filterCustomerId']);
        $status = isset($_REQUEST['filterStatus']) == false ? '' : trim($_REQUEST['filterStatus']);

        $from = isset($_REQUEST['filterFrom']) == false ? '' : trim($_REQUEST['filterFrom']);
        if ($from != '') {
            $time = strtotime($from);
            $from = date('Y-m-d', $time);
        }
        $to = isset($_REQUEST['filterTo']) == false ? '' : trim($_REQUEST['filterTo']);
        if ($to != '') {
            $time = strtotime($to);
            $to = date('Y-m-d', $time);
        }
        $filter_page = isset($_REQUEST['page']) == false ? 0 : trim($_REQUEST['page']);
        $filter_results_per_page = 10;

        $invoices = BusinessesModel::getInvoices(array(
                    'invoiceId' => $invoiceId,
                    'customerId' => $customerId,
                    'status' => $status,
                    'from' => $from,
                    'to' => $to,
                    'orderby' => 'Id',
                    'sort' => 'desc',
                    'limit_from' => $filter_page * $filter_results_per_page,
                    'limit_to' => $filter_results_per_page,
                    'append_count' => true,
        ));


        $filter_results_total_count = array_pop($invoices);

        /* START: Pagination */
        $url = Application::createActionUrl('business-invoice-manager') . '?BusinessId=' . $business['Id'];
        $url .= '&ref=' . rawurlencode($invoiceId);
        $url .= '&customerId=' . rawurlencode($customerId);
        $url .= '&status=' . rawurlencode($status);
        $url .= '&page=';
        $pagination = \Sinevia\Utils::pagination($filter_results_total_count, $filter_results_per_page, ($filter_page), $url);
        /* END: Pagination */

        $customers = BusinessesModel::getActiveCustomersByBusinessId($business['Id']);

        /* START: View */
        $webpage_content = \Sinevia\Template::fromFile($this->templates_directory . 'business-invoice-manager.phtml', array(
                    'message' => $message,
                    'invoices' => $invoices,
                    'customers' => $customers,
                    'pagination' => $pagination,
                    'business' => $business,
                    'user' => $user,
                    'invoiceId' => $invoiceId,
                    'customerId' => $customerId,
                    'status' => $status
        ));
        $template = \Sinevia\Template::fromFile($this->templates_directory . 'layout.phtml', array(
                    'webpage_title' => 'Business',
                    'webpage_content' => $webpage_content,
        ));
        return $template;
        /* END: View */
    }

    function getInvoiceUpdate() {
        $invoice = \Sinevia\Business\Models\Invoice::find(request('InvoiceId'));
        if ($invoice == null) {
            return redirect()->back()->withErrors('Invoice not found');
        }

        $customerId = request('CustomerId', old('CustomerId', $invoice->CustomerId));
        $total = request('Total', old('Total', $invoice->Total));
        $status = request('Status', old('Status', $invoice->Status));
        $currency = request('Currency', old('Currency', $invoice->Currency));
        $subtotal = request('Subtotal', old('Subtotal', $invoice->Subtotal));
        $tax = request('Tax', old('Tax', $invoice->Tax));
        $memo = request('Memo', old('Memo', $invoice->Memo));
        $issuedOn = request('IssuedOn', old('IssuedOn', $invoice->IssuedAt));
        $paidOn = request('PaidOn', old('PaidOn', $invoice->PaidAt));
        $dueOn = request('DueOn', old('DueOn', $invoice->DueAt));
        $reference = request('Reference', old('Reference', $invoice->Reference));
        $transactionId = request('TranasactionId', old('TransactionId', $invoice->TransactionId));
        $discount = request('Discount', old('Discount', $invoice->Discount));
        $discountDescription = request('DiscountDescription', old('DiscountDescription', $invoice->DiscountDescription));
        //$items = request('Items', []);
        if ($reference == "") {
            $reference = $invoice->getReference();
        }

        $customerList = \Sinevia\Business\Helpers\Helper::customerInstance()->all();
        $statusList = [
            \Sinevia\Business\Models\Invoice::STATUS_DRAFT => 'Draft',
            \Sinevia\Business\Models\Invoice::STATUS_PAID => 'Paid',
            \Sinevia\Business\Models\Invoice::STATUS_UNPAID => 'Unpaid',
            \Sinevia\Business\Models\Invoice::STATUS_DELETED => 'Deleted',
        ];
        $currencyList = [
            'GBP' => 'British pound (GBP)',
            'EUR' => 'Euro (EUR)',
            'USD' => 'US Dollar (USD)',
        ];

        $invoiceItemList = request('Items', old('Items', null));
        if (is_null($invoiceItemList)) {
            $invoiceItems = \Sinevia\Business\Models\InvoiceItem::where('InvoiceId', $invoice->Id)->get(); //$invoice->items->get();
            $invoiceItemList = [];
            foreach ($invoiceItems as $item) {
                $invoiceItemList[] = [
                    'Id' => $item->Id,
                    'Details' => $item->Details,
                    'PricePerUnit' => $item->PricePerUnit,
                    'Units' => $item->Units,
                    'Total' => $item->Total,
                ];
            }
        }

        return view('business::admin/invoice-update', get_defined_vars());

        function invoiceSave() {
            $data = isset($_REQUEST['data']) == false ? '' : $_REQUEST['data'];
            $business = $this->business;

            if ($data['InvoiceId'] == '') {
                return 'Invoice missing';
            } else if ($data['CustomerId'] == '') {
                return 'Customer is required field';
            } else if ($data['Currency'] == '') {
                return 'Currency is required field';
            } else if ($data['Status'] == '') {
                return 'Status is required field';
            }

            Application::getDatabase()->transactionBegin();
            $subtotal = str_replace(',', '', $data['Subtotal']);
            $tax = str_replace(',', '', $data['Tax']);
            $total = str_replace(',', '', $data['Total']);
            $invoice = array(
                'Id' => $data['InvoiceId'],
                'Status' => $data['Status'],
                'BusinessId' => $business['Id'],
                'CustomerId' => $data['CustomerId'],
                'Currency' => $data['Currency'],
                'Subtotal' => $subtotal,
                'Tax' => $tax,
                'Total' => $total,
                'Updated' => date('Y-m-d H:i:s'),
                'AdminNotes' => $data['Memo']
            );

            BusinessesModel::updateInvoiceById($data['InvoiceId'], $invoice);

            $result = BusinessesModel::deleteInvoiceItems($data['InvoiceId']);

            foreach ($data['Items'] as $value) {
                $Id = \Sinevia\Convert::toCrockford32(Sinevia\Uid::microUid());
                $invoiceitem = array(
                    'Id' => isset($value['itemId']) ? $value['itemId'] : $Id,
                    'BusinessId' => $business['Id'],
                    'InvoiceId' => $data['InvoiceId'],
                    'Details' => trim($value['Description']),
                    'Units' => trim($value['UnitsQty']),
                    'PricePerUnit' => trim($value['UnitPrice']),
                    'Total' => trim($value['Subtotal'])
                );

                BusinessesModel::insertInvoiceItem($invoiceitem);
            }

            $result = Application::getDatabase()->transactionCommit();
            if ($result == false) {
                Application::getDatabase()->transactionRollBack();
                return 'The invoice failed to be saved';
            } else {
                return 'success';
            }
        }

        function invoiceEdit() {
            $invoiceId = isset($_REQUEST['InvoiceId']) == false ? '' : trim($_REQUEST['InvoiceId']);
            $customers = BusinessesModel::getActiveCustomers();
            $message = isset($_REQUEST['msg']) == false ? '' : trim($_REQUEST['msg']);

            $invoice = BusinessesModel::getInvoiceById($invoiceId);
            $invoice_item = BusinessesModel::getInvoiceItemById($invoiceId);

            /* START: View */
            $webpage_content = \Sinevia\Template::fromFile($this->templates_directory . 'business-invoice-edit.phtml', array(
                        'message' => $message,
                        'invoice' => $invoice,
                        'invoice_item' => $invoice_item,
                        'customers' => $customers,
            ));
            $template = \Sinevia\Template::fromFile($this->templates_directory . 'layout.phtml', array(
                        'webpage_title' => 'Business',
                        'webpage_content' => $webpage_content,
            ));
            return $template;
            /* END: View */
        }

    }

    function getTransactionManager() {
        $session_order_by = \Session::get('business_transaction_manager_by', 'Date');
        $session_order_sort = \Session::get('business_transaction_manager_sort', 'asc');

        $filterId = request('filter_id', '');
        $filterStarts = request('filter_starts', '');
        $filterEnds = request('filter_ends', '');
        $filterType = request('filter_type', '');
        $orderby = request('by', $session_order_by);
        $sort = request('sort', $session_order_sort);
        $page = request('page', 0);
        $results_per_page = 20;

        \Session::put('business_transaction_manager_by', $orderby); // Keep for session
        \Session::put('business_transaction_manager_sort', $sort);  // Keep for session

        $q = \Sinevia\Business\Models\Transaction::getModel();

        if ($filterId) {
            $q = $q->orWhere('Id', 'LIKE', '%' . $filterId . '%');
            $q = $q->orWhere('Title', 'LIKE', '%' . $filterId . '%');
        }
        if ($filterType == "Credit") {
            $q = $q->where('IsCredit', 'Yes');
        }
        if ($filterType == "Debit") {
            $q = $q->where('IsDebit', 'Yes');
        }
        if ($filterStarts AND $filterEnds) {
            $q = $q->where('Date', '>=', $filterStarts . ' 00:00:00');
            $q = $q->where('Date', '<=', $filterEnds . ' 23:59:59');
        } else if ($filterStarts) {
            $q = $q->where('Date', '>=', $filterStarts . ' 00:00:00');
        } else if ($filterEnds) {
            $q = $q->where('Date', '<=', $filterEnds . ' 23:59:59');
        }

        if ($orderby == "Title") {
            $orderby = 'Id';
        }
        $q = $q->orderBy($orderby, $sort);

        $transactions = $q->paginate($results_per_page);

        $typeList = [
            'Credit' => 'Credit',
            'Debit' => 'Debit',
        ];

        return view('business::admin/transaction-manager', get_defined_vars());
    }

    function getTransactionUpdate() {
        $transaction = \Sinevia\Business\Models\Transaction::find(request('TransactionId'));
        if ($transaction == null) {
            return redirect()->back()->withErrors('Transaction not found');
        }
        $title = request('Title', old('Title', $transaction->Title));
        $type = request('Type', old('Tpe', $transaction->isCredit() ? 'credit' : 'debit'));
        $description = request('Description', old('Title', $transaction->Description));
        $amount = request('Amount', old('Amount', $transaction->Amount));
        $date = request('Date', old('Date', $transaction->Date));
        return view('business::admin/transaction-update', get_defined_vars());
    }

    function postInvoiceCreateAjax() {
        $rules = array(
            'CustomerId' => 'required', // required
        );

        $validator = \Validator::make(\Request::all(), $rules);
        if ($validator->fails()) {
            return \Sinevia\Business\Helpers\Helper::error($validator->getMessageBag()->all());
        }

        $customerId = request('CustomerId', '');

        $invoice = new \Sinevia\Business\Models\Invoice;
        $invoice->Status = \Sinevia\Business\Models\Invoice::STATUS_DRAFT;
        $invoice->CustomerId = $customerId;

        if ($invoice->save() === false) {
            return \Sinevia\Business\Helpers\Helper::error('Invoice COULD NOT be created');
        }

        $invoice->Reference = $invoice->getReference(); // Generate reference
        $invoice->save();

        return \Sinevia\Business\Helpers\Helper::success('Invoice successfully created', [
                    'InvoiceId' => $invoice->Id,
                    'InvoiceUrl' => \Sinevia\Business\Helpers\Links::adminInvoiceUpdate([
                        'InvoiceId' => $invoice->Id,
                    ])
        ]);

        $user = $this->user;
        $business = $this->business;
        $customerId = isset($_REQUEST['customerId']) == false ? '' : trim($_REQUEST['customerId']);
        $message = '';

        $Id = \Sinevia\Convert::toCrockford32(Sinevia\Uid::microUid());
        $result = BusinessesModel::createInvoice(array(
                    'Id' => $Id,
                    'CustomerId' => $customerId,
                    'BusinessId' => $business['Id'],
                    'Created' => date('Y-m-d H:i:s'),
                    'Updated' => date('Y-m-d H:i:s'),
                    'DueBy' => date('Y-m-d H:i:s', time() + 14 * 24 * 3600),
                    'Status' => 'Draft',
        ));
        if ($result === false) {
            return Sinevia\Utils::redirect(Application::createActionUrl('business-invoice-manager') . '?BusinessId=' . $business['Id']);
        }
        return Sinevia\Utils::redirect(Application::createActionUrl('business-invoice-edit') . '?InvoiceId=' . $Id);
    }

    function postInvoiceDelete() {
        $invoice = \Sinevia\Business\Models\Invoice::find(request('InvoiceId'));
        if ($invoice == null) {
            return redirect()->back()->withErrors('Invoice not found');
        }
        \DB::beginTransaction();
        try {
            \Sinevia\Business\Models\InvoiceItem::where('InvoiceId', $invoice->Id)->delete();
            $invoice->delete();

            \DB::commit();
            return redirect(\Sinevia\Business\Helpers\Links::adminInvoiceManager());
        } catch (Exception $e) {
            \DB::rollback();
            $error = "Invoice COULD NOT be deleted";
            return \Redirect::back()->withErrors($error);
        }
    }

    function postInvoiceMoveToTrash() {
        $invoice = \Sinevia\Business\Models\Invoice::find(request('InvoiceId'));
        if ($invoice == null) {
            return redirect()->back()->withErrors('Invoice not found');
        }
        \DB::beginTransaction();
        try {
            $invoice->Status = 'Deleted';
            $invoice->save();

            \DB::commit();
            return redirect()->back();
        } catch (Exception $e) {
            \DB::rollback();
            $error = "Invoice COULD NOT be deleted";
            return \Redirect::back()->withErrors($error);
        }
    }

    function postTransactionCreateAjax() {
        $rules = array(
            'Title' => 'required', // required
            'Amount' => 'required|numeric', // required
            'Type' => 'required', // required
            'Date' => 'required', // required
            'Description' => 'required', // required
        );

        $validator = \Validator::make(\Request::all(), $rules);
        if ($validator->fails()) {
            return \Sinevia\Business\Helpers\Helper::error($validator->getMessageBag()->all());
        }

        $title = request('Title', '');
        $description = request('Description', '');
        $type = request('Type', '');
        $amount = request('Amount', '');
        $date = request('Date', date('Y-m-d'));
        $isCredit = strtolower($type) == 'credit' ? 'Yes' : 'No';
        $isDebit = strtolower($type) == 'debit' ? 'Yes' : 'No';

        $transaction = new \Sinevia\Business\Models\Transaction;
        $transaction->Title = $title;
        $transaction->Description = $description;
        $transaction->Date = date('Y-m-d', strtotime($date));
        $transaction->IsCredit = $isCredit;
        $transaction->IsDebit = $isDebit;
        $transaction->Amount = $amount;

        if ($transaction->save() === false) {
            return \Sinevia\Business\Helpers\Helper::error('Transaction COULD NOT be created');
        }

        return \Sinevia\Business\Helpers\Helper::success('Transaction successfully created', [
                    'TransactionId' => $transaction->Id,
                    'TransactionUrl' => \Sinevia\Business\Helpers\Links::adminTransactionUpdate([
                        'TransactionId' => $transaction->Id,
                    ])
        ]);
    }

    function postTransactionDelete() {
        $transaction = \Sinevia\Cms\Models\Page::find(request('TransactionId'));
        if ($transaction == null) {
            return \Redirect::back()->withErrors('Page not found');
        }
        \DB::beginTransaction();
        try {
            /* Move to Trash */
            $transaction->DeletedAt = date('Y-m-d H:i:s');
            $transaction->save();
            /* Delete */
            $transaction->delete();

            \DB::commit();

            return \Redirect::back();
        } catch (Exception $e) {
            \DB::rollback();
            $error = "Transaction COULD NOT be deleted";
            return \Redirect::back()->withErrors($error);
        }
    }

    function postTransactionMoveToTrash() {
        $transaction = \Sinevia\Business\Models\Transaction::find(request('TransactionId'));
        if ($transaction == null) {
            return redirect()->back()->withErrors('Transaction not found');
        }
        \DB::beginTransaction();
        try {
            $transaction->DeletedAt = date('Y-m-d H:i:s');
            $transaction->save();

            \DB::commit();
            return redirect()->back();
        } catch (Exception $e) {
            \DB::rollback();
            $error = "Transaction COULD NOT be moved to trash";
            return \Redirect::back()->withErrors($error);
        }
    }

    function postTransactionUpdate() {
        $transaction = \Sinevia\Business\Models\Transaction::find(request('TransactionId'));
        if ($transaction == null) {
            return \Redirect::back()->withErrors('Transaction not found');
        }

        $rules = array(
            'Title' => 'required', // required
            'Amount' => 'required|numeric', // required
            'Type' => 'required', // required
            'Description' => 'required', // required
            'Date' => 'required|date_format:"Y-m-d"', // required
        );

        $validator = \Validator::make(\Request::all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $title = request('Title', '');
        $description = request('Description', $transaction->Description);
        $type = request('Type', '');
        $amount = request('Amount', $transaction->Amount);
        $isCredit = strtolower($type) == 'credit' ? 'Yes' : 'No';
        $isDebit = strtolower($type) == 'debit' ? 'Yes' : 'No';
        $date = request('Date', $transaction->Date);
        $action = request('action', '');

        \DB::beginTransaction();
        try {
            $transaction->Title = $title;
            $transaction->Description = $description;
            $transaction->IsCredit = $isCredit;
            $transaction->IsDebit = $isDebit;
            $transaction->Amount = $amount;
            $transaction->Date = date('Y-m-d', strtotime($date));
            $transaction->save();

            $result = \DB::commit();

            if ($result !== false) {
                if ($action === 'save') {
                    \Session::flash('success', 'You successuly updated the transaction');
                    return redirect()->back();
                }
                \Session::flash('success', 'You successuly updated the transaction');
                return redirect(\Sinevia\Business\Helpers\Links::adminTransactionManager());
            }
        } catch (Exception $e) {
            \DB::rollback();
        }

        return redirect()->back()->withErrors('Saving the transaction FAILED...')->withInput();
    }

    function postInvoiceUpdate() {

        $invoice = \Sinevia\Business\Models\Invoice::find(request('InvoiceId'));
        if ($invoice == null) {
            return \Redirect::back()->withErrors('Invoice not found');
        }

        $rules = array(
            'CustomerId' => 'required', // required
            'Status' => 'required', // required
            'Currency' => 'required', // required
            'Subtotal' => 'required|numeric', // required
            'Tax' => 'required|numeric', // required
            'Total' => 'required|numeric', // required
            'Items.*.Id' => 'required',
            'Items.*.Details' => 'required',
            'Items.*.PricePerUnit' => 'required|numeric',
            'Items.*.Units' => 'required|numeric',
            'Items.*.Total' => 'required|numeric',
            'IssuedOn' => 'required', // required
            'DueOn' => 'required', // required
        );

        $validator = \Validator::make(\Request::all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $action = request('action', '');
        $customerId = request('CustomerId', '');
        $status = request('Status', '');
        $currency = request('Currency', '');
        $discount = request('Discount', '');
        $discountDescription = request('DiscountDescription', '');
        $subtotal = request('Subtotal', '');
        $tax = request('Tax', '');
        $total = request('Total', '');
        $memo = request('Memo', '');
        $issuedOn = request('IssuedOn', '');
        $paidOn = request('PaidOn', '');
        $dueOn = request('DueOn', '');
        $reference = request('Reference', '');
        $transactionId = request('TranasactionId', '');
        $items = request('Items', []);

        \DB::beginTransaction();
        try {
            $invoice->Status = $status;
            $invoice->CustomerId = $customerId;
            $invoice->Currency = $currency;
            $invoice->Discount = $discount;
            $invoice->DiscountDescription = $discountDescription;
            $invoice->Subtotal = $subtotal;
            $invoice->Tax = $tax;
            $invoice->Total = $total;
            $invoice->IssuedAt = date('Y-m-d', strtotime($issuedOn));
            $invoice->PaidAt = $paidOn != '' ? date('Y-m-d', strtotime($paidOn)) : null;
            $invoice->DueAt = date('Y-m-d', strtotime($dueOn));
            $invoice->Reference = $reference;
            $invoice->TransactionId = $transactionId;
            $invoice->Memo = $memo;

            $invoice->save();

            // Remove delted items
            $submittedIds = array_column($items, 'Id');
            \Sinevia\Business\Models\InvoiceItem::where('InvoiceId', $invoice->Id)
                    ->whereNotIn('Id', $submittedIds)->delete();

            // Create or update existing
            foreach ($items as $item) {
                $id = $item['Id'];
                $details = $item['Details'];
                $pricePerUnit = $item['PricePerUnit'];
                $units = $item['Units'];
                $total = $item['Total'];

                $invoiceItem = \Sinevia\Business\Models\InvoiceItem::find($id);
                if (is_null($invoiceItem)) {
                    $invoiceItem = new \Sinevia\Business\Models\InvoiceItem;
                    $invoiceItem->InvoiceId = $invoice->Id;
                } else {
                    if ($invoiceItem->InvoiceId != $invoice->Id) {
                        return redirect()->back()->withErrors('Invoice Item ' . $id . ' belngs to another invoice. Please contact admin!')->withInput();
                    }
                }

                $invoiceItem->Details = $details;
                $invoiceItem->PricePerUnit = $pricePerUnit;
                $invoiceItem->Units = $units;
                $invoiceItem->Total = $total;

                $invoiceItem->save();
            }

            $result = \DB::commit();

            if ($result !== false) {
                if ($action === 'save') {
                    \Session::flash('success', 'You successuly updated the invoice');
                    return redirect()->back();
                }
                \Session::flash('success', 'You successuly updated the invoice');
                return redirect(\Sinevia\Business\Helpers\Links::adminInvoiceManager());
            }
        } catch (Exception $e) {
            \DB::rollback();
        }

        return redirect()->back()->withErrors('Saving the invoice FAILED...')->withInput();
    }

}
