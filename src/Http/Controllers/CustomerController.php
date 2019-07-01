<?php

namespace Sinevia\Busiiness\Http\Controllers;

class CustomerController extends BaseController {

    function getBusinessCreate() {
        $type = request('Type', old('Type', ''));
        $businessName = request('BusinessName', old('BusinessName', ''));
        $address1 = request('Address1', old('Address1', ''));
        $address2 = request('Address2', old('Address2', ''));
        $city = request('City', old('City', ''));
        $province = request('Province', old('Province', ''));
        $postCode = request('PostCode', old('PostCode', ''));
        $country = request('Country', old('Country', ''));
        $emailAddressInvoice = request('EmailAddressInvoice', old('EmailAddressInvoice', ''));
        $status = request('Status', old('Status', ''));
        $phone = request('Phone', old('Phone', ''));
        $accountNumber = request('AccountNumber', old('AccountNumber', ''));
        $bankSortCode = request('BankSortCode', old('BankSortCode', ''));
        $user = $this->user;
        $countries = \App\Models\Countries\Country::orderBy('Name')->get();
        return view('admin/customers/business-create', get_defined_vars());
    }

    function getBusinessManager() {
        /* START: Data */
        $view = request('view', '');
        $filterId = request('filter_id', '');
        $filterStatus = request('filter_status', 'not_deleted');
        $filterName = request('filter_name', '');
        $orderBy = request('by', 'BusinessName');
        $sort = request('sort', 'ASC');
        if ($view == 'trash') {
            $filterStatus = 'Deleted';
        }
        if ($filterStatus == 'Deleted') {
            $view = 'trash';
        }
        $page = request('page', 0);
        $per_page = 10;
        /* END: Data */

        /* START: Sites Search */
        //\DB::connection('sinevia')->enableQueryLog();
        $model = \App\Models\Customers\Business::getModel();
        if ($filterStatus != '') {
            if ($filterStatus == 'not_deleted') {
                $model = $model->where('Status', '<>', 'Deleted');
            } else {
                $model = $model->where('Status', '=', $filterStatus);
            }
        }
        if ($filterName != '') {
            $model = $model->where('BusinessName', 'LIKE', '%' . $filterName . '%');
        }
        $businesses = $model->orderBy($orderBy, $sort)->paginate(10);
        //dd(\DB::connection('sinevia')->getQueryLog());
        /* END: Sites Search */

        return view('admin/customers/business-manager', get_defined_vars());
    }

    function getBusinessUpdate() {
        $businessId = request('BusinessId', '');

        if ($businessId == '') {
            return $this->flashError('Business ID missing');
        }

        $business = \App\Models\Customers\Business::find($businessId);

        if ($business == null) {
            return $this->flashError('Business with ID ' . $businessId . ' DOES NOT exist', \URL::action('Admin\CustomerController@getBusinessManager'));
        }

        $type = request('Type', old('Type', $business->Type));
        $businessName = request('BusinessName', old('BusinessName', $business->BusinessName));
        $address1 = request('Address1', old('Address1', $business->Address1));
        $address2 = request('Address2', old('Address2', $business->Address2));
        $city = request('City', old('City', $business->City));
        $province = request('Province', old('Province', $business->Province));
        $postCode = request('PostCode', old('PostCode', $business->PostCode));
        $country = request('Country', old('Country', $business->Country));
        $emailAddressInvoice = request('EmailAddressInvoice', old('EmailAddressInvoice', $business->EmailAddressInvoice));
        $status = request('Status', old('Status', $business->Status));
        $phone = request('Phone', old('Phone', $business->Phone));
        $accountNumber = request('AccountNumber', old('AccountNumber', $business->AccountNumber));
        $bankSortCode = request('BankSortCode', old('BankSortCode', $business->BankSortCode));
        $user = $this->user;
        $countries = \App\Models\Countries\Country::orderBy('Name')->get();
        return view('admin/customers/business-update', get_defined_vars());
    }

    function getCustomerCreate() {
        $businessId = request('BusinessId', '');
        $business = \App\Models\Customers\Business::find($businessId);

        if ($business == null) {
            return $this->flashError('Business with ID ' . $businessId . ' DOES NOT exist', action('Admin\CustomerController@getBusinessManager'));
        }

        $type = request('Type', old('Type', ''));
        $customerName = request('CustomerName', old('CustomerName', ''));
        $businessName = request('BusinessName', old('BusinessName', ''));
        $personFirstName = request('PersonFirstName', old('PersonFirstName', ''));
        $personLastName = request('PersonLastName', old('PersonLastName', ''));
        $address1 = request('Address1', old('Address1', ''));
        $address2 = request('Address2', old('Address2', ''));
        $city = request('City', old('City', ''));
        $province = request('Province', old('Province', ''));
        $postCode = request('PostCode', old('PostCode', ''));
        $country = request('Country', old('Country', ''));
        $emailAddressInvoice = request('EmailAddressInvoice', old('EmailAddressInvoice', ''));
        $emailAddressQuote = request('EmailAddressQuote', old('EmailAddressQuote', ''));
        $status = request('Status', old('Status', ''));
        $phone = request('Phone', old('Phone', ''));
        $user = $this->user;
        $countries = \App\Models\Countries\Country::orderBy('Name')->get();
        return view('admin/customers/customer-create', get_defined_vars());
    }

    function getCustomerManager() {
        $businessId = request('BusinessId', '');

        if ($businessId == '') {
            return $this->flashError('Business ID missing');
        }

        $business = \App\Models\Customers\Business::find($businessId);

        if ($business == null) {
            return $this->flashError('Business with ID ' . $businessId . ' DOES NOT exist', \URL::action('Admin\CustomerController@getBusinessManager'));
        }

        /* START: Data */
        $view = request('view', '');
        $filterId = request('filter_id', '');
        $filterStatus = request('filter_status', 'not_deleted');
        $filterName = request('filter_name', '');
        $orderBy = request('by', 'Id');
        $sort = request('sort', 'ASC');
        if ($view == 'trash') {
            $filterStatus = 'Deleted';
        }
        if ($filterStatus == 'Deleted') {
            $view = 'trash';
        }
        $page = request('page', 0);
        $per_page = 10;
        /* END: Data */

        /* START: Sites Search */
        //\DB::connection('sinevia')->enableQueryLog();
        $model = \App\Models\Customers\Customer::getModel();
        if ($filterStatus != '') {
            if ($filterStatus == 'not_deleted') {
                $model = $model->where('Status', '<>', 'Deleted');
            } else {
                $model = $model->where('Status', '=', $filterStatus);
            }
        }
        if ($filterName != '') {
            $model = $model->where('BusinessName', 'LIKE', '%' . $filterName . '%');
        }
        $customers = $model->orderBy($orderBy, $sort)->paginate(10);
        //dd(\DB::connection('sinevia')->getQueryLog());
        /* END: Sites Search */

        return view('admin/customers/customer-manager', get_defined_vars());
    }

    function getCustomerUpdate() {
        $customerId = request('CustomerId', '');
        $customer = \App\Models\Customers\Customer::find($customerId);

        if ($customer == null) {
            return $this->flashError('Customer with ID ' . $customerId . ' DOES NOT exist', action('Admin\CustomerController@getCustomerManager'));
        }

        $type = request('Type', old('Type', $customer->Type));
        $businessId = request('BusinessId', old('BusinessId', $customer->BusinessId));
        $customerName = request('CustomerName', old('CustomerName', $customer->CustomerName));
        $businessName = request('BusinessName', old('BusinessName', $customer->BusinessName));
        $personFirstName = request('PersonFirstName', old('PersonFirstName', $customer->PersonFirstName));
        $personLastName = request('PersonLastName', old('PersonLastName', $customer->PersonLastName));
        $address1 = request('Address1', old('Address1', $customer->Address1));
        $address2 = request('Address2', old('Address2', $customer->Aaddress2));
        $city = request('City', old('City', $customer->City));
        $province = request('Province', old('Province', $customer->Province));
        $postCode = request('PostCode', old('PostCode', $customer->PostCode));
        $country = request('Country', old('Country', $customer->Country));
        $emailAddressInvoice = request('EmailAddressInvoice', old('EmailAddressInvoice', $customer->EmailAddressInvoice));
        $emailAddressQuote = request('EmailAddressQuote', old('EmailAddressQuote', $customer->EmailAddressQuote));
        $status = request('Status', old('Status', $customer->Status));
        $phone = request('Phone', old('Phone', $customer->Phone));
        $memo = request('Memo', old('Memo', $customer->Memo));
        $user = $this->user;
        $countries = \App\Models\Countries\Country::orderBy('Name')->get();
        return view('admin/customers/customer-update', get_defined_vars());
    }

    function getInvoiceManager() {
        $businessId = request('BusinessId', '');

        if ($businessId == '') {
            return $this->flashError('Business ID missing');
        }

        $business = \App\Models\Customers\Business::find($businessId);

        if ($business == null) {
            return $this->flashError('Business with ID ' . $businessId . ' DOES NOT exist', \URL::action('Admin\CustomerController@getBusinessManager'));
        }

        /* START: Data */
        $view = request('view', '');
        $filterId = request('filter_id', '');
        $filterStatus = request('filter_status', 'not_deleted');
        $filterName = request('filter_name', '');
        $orderBy = request('by', 'BusinessName');
        $sort = request('sort', 'ASC');
        if ($view == 'trash') {
            $filterStatus = 'Deleted';
        }
        if ($filterStatus == 'Deleted') {
            $view = 'trash';
        }
        $page = request('page', 0);
        $per_page = 10;
        /* END: Data */

        /* START: Sites Search */
        //\DB::connection('sinevia')->enableQueryLog();
        $model = \App\Models\Customers\Business::getModel();
        if ($filterStatus != '') {
            if ($filterStatus == 'not_deleted') {
                $model = $model->where('Status', '<>', 'Deleted');
            } else {
                $model = $model->where('Status', '=', $filterStatus);
            }
        }
        if ($filterName != '') {
            $model = $model->where('BusinessName', 'LIKE', '%' . $filterName . '%');
        }
        $businesses = $model->orderBy($orderBy, $sort)->paginate(10);
        //dd(\DB::connection('sinevia')->getQueryLog());
        /* END: Sites Search */

        return view('admin/customers/invoice-manager', get_defined_vars());
    }

    function getQuoteManager() {
        $businessId = request('BusinessId', '');

        if ($businessId == '') {
            return $this->flashError('Business ID missing');
        }

        $business = \App\Models\Customers\Business::find($businessId);

        if ($business == null) {
            return $this->flashError('Business with ID ' . $businessId . ' DOES NOT exist', \URL::action('Admin\CustomerController@getBusinessManager'));
        }

        /* START: Data */
        $view = request('view', '');
        $filterId = request('filter_id', '');
        $filterStatus = request('filter_status', 'not_deleted');
        $filterName = request('filter_name', '');
        $orderBy = request('by', 'BusinessName');
        $sort = request('sort', 'ASC');
        if ($view == 'trash') {
            $filterStatus = 'Deleted';
        }
        if ($filterStatus == 'Deleted') {
            $view = 'trash';
        }
        $page = request('page', 0);
        $per_page = 10;
        /* END: Data */

        /* START: Sites Search */
        //\DB::connection('sinevia')->enableQueryLog();
        $model = \App\Models\Customers\Business::getModel();
        if ($filterStatus != '') {
            if ($filterStatus == 'not_deleted') {
                $model = $model->where('Status', '<>', 'Deleted');
            } else {
                $model = $model->where('Status', '=', $filterStatus);
            }
        }
        if ($filterName != '') {
            $model = $model->where('BusinessName', 'LIKE', '%' . $filterName . '%');
        }
        $businesses = $model->orderBy($orderBy, $sort)->paginate(10);
        //dd(\DB::connection('sinevia')->getQueryLog());
        /* END: Sites Search */

        return view('admin/customers/customer-manager', get_defined_vars());
    }

    function getSettingsUpdate() {
        $isMultiBusiness = \App\Models\Customers\Setting::get('IsMultiBusiness', 'Yes');
        $businessId = \App\Models\Customers\Setting::get('BusinessId', '');
        $businesses = \App\Models\Customers\Business::get();
        return view('admin/customers/settings-update', get_defined_vars());
    }

    function postBusinessCreate() {
        $action = request('action', '');
        $type = request('Type', old('Type', ''));
        $businessName = request('BusinessName', old('BusinessName', ''));
        $address1 = request('Address1', old('Address1', ''));
        $address2 = request('Address2', old('Address2', ''));
        $city = request('City', old('City', ''));
        $province = request('Province', old('Province', ''));
        $postCode = request('PostCode', old('PostCode', ''));
        $country = request('Country', old('Country', ''));
        $emailAddressInvoice = request('EmailAddressInvoice', old('EmailAddressInvoice', ''));
        $status = request('Status', old('Status', ''));
        $phone = request('Phone', old('Phone', ''));
        $accountNumber = request('AccountNumber', old('AccountNumber', ''));
        $bankSortCode = request('BankSortCode', old('BankSortCode', ''));
        $user = $this->user;
        $countries = \App\Models\Countries\Country::orderBy('Name')->get();

        $rules = array(
            'Type' => 'required',
            'BusinessName' => 'required',
            'Address1' => 'required',
            'City' => 'required',
            'Province' => 'required',
            'PostCode' => 'required',
            'Country' => 'required',
            'EmailAddressInvoice' => 'required',
            //'Status' => 'required',
            'AccountNumber' => 'required',
            'BankSortCode' => 'required',
        );

        $validator = \Validator::make(\Request::all(), $rules);
        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        }

        $business = new \App\Models\Customers\Business;
        $business->Id = \Sinevia\Uid::microUid();
        $business->Status = 'Active';
        $business->Type = $type;
        $business->BusinessName = $businessName;
        $business->Address1 = $address1;
        $business->Address2 = $address2;
        $business->City = $city;
        $business->Province = $province;
        $business->Country = $country;
        $business->PostCode = $postCode;
        $business->EmailAddressInvoice = $emailAddressInvoice;
        $business->Phone = $phone;
        $business->BankSortCode = $bankSortCode;
        $business->AccountNumber = $accountNumber;
        $business->Created = date('Y-m-d H:i:s');
        $business->Updated = date('Y-m-d H:i:s');

        $result = $business->save();
        if ($result == false) {
            return \Redirect::back()->withErrors('Businesses failed to be created. Please try again later')->withInput(\Request::all());
        }

        if ($action == 'save') {
            $redirectUrl = \URL::action('Admin\CustomerController@getBusinessUpdate') . '?BusinessId=' . urlencode($business->Id);
            return \Redirect::to($redirectUrl)->with('success', 'Business was successfully saved.');
        }

        $redirectUrl = \URL::action('Admin\CustomerController@getBusinessManager');
        return \Redirect::to($redirectUrl)->with('success', 'Business was successfully saved.');
    }

    function postBusinessDelete() {
        $businessId = request('BusinessId', '');

        if ($businessId == '') {
            return $this->flashError('Business ID missing');
        }

        /*
         * @todo delete business customers, invoices, quotes
         * Deal with 
         */
        $business = \App\Models\Customers\Business::find($businessId);

        if ($business == null) {
            return $this->flashError('Business with ID ' . $businessId . ' DOES NOT exist', \URL::action('Admin\CustomerController@getBusinessManager'));
        }

        $result = $business->delete();

        if ($result == false) {
            $redirectUrl = \URL::action('Admin\CustomerController@getBusinessManager');
            return \Redirect::to($redirectUrl)->withErrors('Businesses failed to be deleted');
        }

        $redirectUrl = \URL::action('Admin\CustomerController@getBusinessManager');
        return \Redirect::to($redirectUrl)->with('success', 'Business was successfully deleted.');
    }

    function postBusinessMoveToTrash() {
        $businessId = request('BusinessId', '');

        if ($businessId == '') {
            return $this->flashError('Business ID missing');
        }

        $business = \App\Models\Customers\Business::find($businessId);

        if ($business == null) {
            return $this->flashError('Business with ID ' . $businessId . ' DOES NOT exist', \URL::action('Admin\CustomerController@getBusinessManager'));
        }

        $business->status = 'Deleted';

        $result = $business->save();

        if ($result == false) {
            $redirectUrl = \URL::action('Admin\CustomerController@getBusinessManager');
            return \Redirect::to($redirectUrl)->withErrors('Businesses failed to be be moved to trash');
        }

        $redirectUrl = \URL::action('Admin\CustomerController@getBusinessManager');
        return \Redirect::to($redirectUrl)->with('success', 'Business was moved to trash.');
    }

    function postBusinessUpdate() {
        $businessId = request('BusinessId', '');

        if ($businessId == '') {
            return $this->flashError('Business ID missing');
        }

        $business = \App\Models\Customers\Business::find($businessId);

        if ($business == null) {
            return $this->flashError('Business with ID ' . $businessId . ' DOES NOT exist', \URL::action('Admin\CustomerController@getBusinessManager'));
        }

        $action = request('action', '');
        $type = request('Type', $business->Type);
        $businessName = request('BusinessName', $business->BusinessName);
        $address1 = request('Address1', $business->Address1);
        $address2 = request('Address2', $business->Address2);
        $city = request('City', $business->City);
        $province = request('Province', $business->Province);
        $postCode = request('PostCode', $business->PostCode);
        $country = request('Country', $business->Country);
        $emailAddressInvoice = request('EmailAddressInvoice', $business->EmailAddressInvoice);
        $status = request('Status', $business->Status);
        $phone = request('Phone', $business->Phone);
        $accountNumber = request('AccountNumber', $business->AccountNumber);
        $bankSortCode = request('BankSortCode', $business->BankSortCode);
        $user = $this->user;
        $countries = \App\Models\Countries\Country::orderBy('Name')->get();

        $rules = array(
            'Type' => 'required',
            'BusinessName' => 'required',
            'Address1' => 'required',
            'City' => 'required',
            'Province' => 'required',
            'PostCode' => 'required',
            'Country' => 'required',
            'EmailAddressInvoice' => 'required',
            //'Status' => 'required',
            'AccountNumber' => 'required',
            'BankSortCode' => 'required',
        );

        $validator = \Validator::make(\Request::all(), $rules);
        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        }

        $business->Status = $status;
        $business->Type = $type;
        $business->BusinessName = $businessName;
        $business->Address1 = $address1;
        $business->Address2 = $address2;
        $business->City = $city;
        $business->Province = $province;
        $business->Country = $country;
        $business->PostCode = $postCode;
        $business->EmailAddressInvoice = $emailAddressInvoice;
        $business->Phone = $phone;
        $business->BankSortCode = $bankSortCode;
        $business->AccountNumber = $accountNumber;
        $business->Updated = date('Y-m-d H:i:s');

        $result = $business->save();
        if ($result == false) {
            return \Redirect::back()->withErrors('Businesses failed to be created. Please try again later')->withInput();
        }

        if ($action == 'save') {
            return \Redirect::back()->with('success', 'Business was successfully saved.');
        }

        $redirectUrl = \URL::action('Admin\CustomerController@getBusinessManager');
        return \Redirect::to($redirectUrl)->with('success', 'Business was successfully saved.');
    }

    function postCustomerCreate() {
        $businessId = request('BusinessId', '');

        if ($businessId == '') {
            $redirectUrl = \URL::action('Admin\CustomerController@getBusinessManager');
            return $this->flashError('Business ID is required', $redirectUrl);
        }

        $action = request('action', '');
        $type = request('Type', '');
        $customerName = request('CustomerName', '');
        $businessName = request('BusinessName', '');
        $personFirstName = request('PersonFirstName', '');
        $personLastName = request('PersonLastName', '');
        $address1 = request('Address1', '');
        $address2 = request('Address2', '');
        $city = request('City', '');
        $province = request('Province', '');
        $postCode = request('PostCode', '');
        $country = request('Country', '');
        $emailAddressInvoice = request('EmailAddressInvoice', '');
        $emailAddressQuote = request('EmailAddressQuote', '');
        $status = request('Status', 'Active');
        $phone = request('Phone', '');
        $memo = request('Memo', '');
        $user = $this->user;

        $rules = array(
            'BusinessId' => 'required',
            'CustomerName' => 'required',
            'Type' => 'required',
            'Address1' => 'required',
            'City' => 'required',
            'Province' => 'required',
            'PostCode' => 'required',
            'Country' => 'required',
            'EmailAddressInvoice' => 'required',
            'EmailAddressQuote' => 'required',
                //'Status' => 'required',
        );

        $validator = \Validator::make(\Request::all(), $rules);
        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        }

        $customer = new \App\Models\Customers\Customer;
        $customer->BusinessId = $businessId;
        $customer->CustomerName = $customerName;
        $customer->Status = 'Active';
        $customer->Type = $type;
        $customer->BusinessName = $businessName;
        $customer->PersonFirstName = $personFirstName;
        $customer->PersonLastName = $personLastName;
        $customer->Address1 = $address1;
        $customer->Address2 = $address2;
        $customer->City = $city;
        $customer->Province = $province;
        $customer->Country = $country;
        $customer->PostCode = $postCode;
        $customer->EmailAddressInvoice = $emailAddressInvoice;
        $customer->EmailAddressQuote = $emailAddressQuote;
        $customer->Phone = $phone;
        $customer->Memo = $memo;

        $result = $customer->save();
        if ($result == false) {
            return \Redirect::back()->withErrors('Customer failed to be created.')->withInput(\Request::all());
        }

        if ($action == 'save') {
            $redirectUrl = \URL::action('Admin\CustomerController@getCustomerUpdate') . '?CustomerId=' . $customer->Id;
            return \Redirect::to($redirectUrl)->with('success', 'Customer was successfully saved.');
        }

        $redirectUrl = \URL::action('Admin\CustomerController@getCustomerManager') . '?BusinessId=' . $businessId;
        return \Redirect::to($redirectUrl)->with('success', 'Customer was successfully saved.');
    }
    
    function postCustomerUpdate() {
        $customerId = request('CustomerId', '');        
        $businessId = request('BusinessId', '');
        $action = request('action', '');
        $type = request('Type', '');
        $customerName = request('CustomerName', '');
        $businessName = request('BusinessName', '');
        $personFirstName = request('PersonFirstName', '');
        $personLastName = request('PersonLastName', '');
        $address1 = request('Address1', '');
        $address2 = request('Address2', '');
        $city = request('City', '');
        $province = request('Province', '');
        $postCode = request('PostCode', '');
        $country = request('Country', '');
        $emailAddressInvoice = request('EmailAddressInvoice', '');
        $emailAddressQuote = request('EmailAddressQuote', '');
        $status = request('Status', 'Active');
        $phone = request('Phone', '');
        $memo = request('Memo', '');
        $user = $this->user;

        if ($customerId == '') {
            $redirectUrl = \URL::action('Admin\CustomerController@getCustomerManager').'?BusinessId='.$businessId;
            return $this->flashError('Business ID is required', $redirectUrl);
        }

        $rules = array(
            'CustomerId' => 'required',
            'BusinessId' => 'required',
            'CustomerName' => 'required',
            'Type' => 'required',
            'Address1' => 'required',
            'City' => 'required',
            'Province' => 'required',
            'PostCode' => 'required',
            'Country' => 'required',
            'EmailAddressInvoice' => 'required',
            'EmailAddressQuote' => 'required',
                //'Status' => 'required',
        );

        $validator = \Validator::make(\Request::all(), $rules);
        if ($validator->fails()) {
            $redirectUrl = \URL::action('Admin\CustomerController@getCustomerCreate') . '?BusinessId=' . $businessId;
            return \Redirect::to($redirectUrl)->withErrors($validator)->withInput();
        }

        $customer->BusinessId = $businessId;
        $customer->CustomerName = $customerName;
        $customer->Status = 'Active';
        $customer->Type = $type;
        $customer->BusinessName = $businessName;
        $customer->PersonFirstName = $personFirstName;
        $customer->PersonLastName = $personLastName;
        $customer->Address1 = $address1;
        $customer->Address2 = $address2;
        $customer->City = $city;
        $customer->Province = $province;
        $customer->Country = $country;
        $customer->PostCode = $postCode;
        $customer->EmailAddressInvoice = $emailAddressInvoice;
        $customer->EmailAddressQuote = $emailAddressQuote;
        $customer->Phone = $phone;
        $customer->Memo = '';

        $result = $customer->save();
        if ($result == false) {
            $redirectUrl = \URL::action('Admin\CustomerController@getCustomerCreate') . '?BusinessId=' . $businessId;
            return \Redirect::to($redirectUrl)->withErrors('Customer failed to be created.')->withInput(\Request::all());
        }

        if ($action == 'save') {
            $redirectUrl = \URL::action('Admin\CustomerController@getCustomerUpdate') . '?CustomerId=' . $customer->Id;
            return \Redirect::to($redirectUrl)->with('success', 'Customer was successfully saved.');
        }

        $redirectUrl = \URL::action('Admin\CustomerController@getCustomerManager') . '?BusinessId=' . $businessId;
        return \Redirect::to($redirectUrl)->with('success', 'Customer was successfully saved.');
    }

    function postSettingsUpdate() {
        $action = request('action', '');
        $isMultiBusiness = request('IsMultiBusiness', \App\Models\Customers\Setting::get('IsMultiBusiness'));
        $businessId = request('BusinessId', \App\Models\Customers\Setting::get('BusinessId'));

        $rules = array(
            'IsMultiBusiness' => 'required',
        );

        $validator = \Validator::make(\Request::all(), $rules);
        if ($validator->fails()) {
            return \Redirect::back()->withErrors($validator)->withInput();
        }

        if ($isMultiBusiness == 'No' AND $businessId == '') {
            return \Redirect::back()->withErrors('Business field is required in single business mode')->withInput();
        }

        \App\Models\Customers\Setting::set("IsMultiBusiness", $isMultiBusiness);
        $result = \App\Models\Customers\Setting::set("BusinessId", $businessId);

        if ($result == false) {
            return \Redirect::back()->withErrors('Settings failed to be saved')->withInput(\Request::all());
        }

        if ($action == 'save') {
            return \Redirect::back()->with('success', 'Settings were successfully saved.');
        }

        $redirectUrl = \URL::action('Admin\CustomerController@getBusinessManager');
        return \Redirect::to($redirectUrl)->with('success', 'Settings were successfully saved.');
    }

}

class BusinessController {

    public $templates_directory = 'templates/business/';
    public $templates_common_directory = 'templates/common/';
    public $business = null;
    public $user = null;

    function __construct() {
        $this->templates_directory = ROOT_DIR . 'templates/business/';
        $this->user = Application::getCurrentUser();
        $this->business = Application::getCurrentBusiness();

        if ($this->user == null) {
            $current_url = Application::getCurrentUrl();
            Sinevia\Utils::redirect(Application::createActionUrl('login') . '?r=' . base64_encode($current_url));
        }

        if ($this->business == null) {
            $current_url = Application::getCurrentUrl();
            Sinevia\Utils::redirect(Application::createActionUrl('business-create') . '?r=' . base64_encode($current_url));
        }
    }

    /**
     * Employer home page
     * @return string
     */
    function home() {
        $message = '';
        /* START: View */
        $webpage_content = \Sinevia\Template::fromFile($this->templates_directory . 'home.phtml', array(
                    'message' => $message,
        ));
        $template = \Sinevia\Template::fromFile($this->templates_directory . 'layout.phtml', array(
                    'webpage_title' => 'Home',
                    'webpage_content' => $webpage_content,
        ));
        return $template;
        /* END: View */
    }

    function invoiceCreate() {
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

    function quoteCreate() {
        $user = $this->user;
        $business = $this->business;
        $customerId = isset($_REQUEST['customerId']) == false ? '' : trim($_REQUEST['customerId']);
        $message = '';

        $Id = \Sinevia\Convert::toCrockford32(Sinevia\Uid::microUid());
        $result = BusinessesModel::createQuotes(array(
                    'Id' => $Id,
                    'CustomerId' => $customerId,
                    'BusinessId' => $business['Id'],
                    'Created' => date('Y-m-d H:i:s'),
                    'Updated' => date('Y-m-d H:i:s'),
                    'Status' => 'Draft',
        ));
        if ($result === false) {
            return Sinevia\Utils::redirect(Application::createActionUrl('business-quotes-manager') . '?businessId=' . $business['Id']);
        }
        return Sinevia\Utils::redirect(Application::createActionUrl('business-quote-edit') . '?QuoteId=' . $Id);
    }

    function customerCreate() {
        $user = $this->user;
        $business = $this->business;
        $type = isset($_REQUEST['Type']) == false ? '' : trim($_REQUEST['Type']);
        $companyName = isset($_REQUEST['CompanyName']) == false ? '' : trim($_REQUEST['CompanyName']);
        $firstName = isset($_REQUEST['FirstName']) == false ? '' : trim($_REQUEST['FirstName']);
        $lastName = isset($_REQUEST['LastName']) == false ? '' : trim($_REQUEST['LastName']);
        $message = '';

        if ($type == '') {
            return Sinevia\Utils::redirect(Application::createActionUrl('business-customers-manager') . '?businessId=' . $business['Id'] . '&msg=Type is required field');
        } else if ($type == 'Company' && $companyName == '') {
            return Sinevia\Utils::redirect(Application::createActionUrl('business-customers-manager') . '?businessId=' . $business['Id'] . '&msg=Company Name is required field');
        } else if ($type == 'Person' && $firstName == '') {
            return Sinevia\Utils::redirect(Application::createActionUrl('business-customers-manager') . '?businessId=' . $business['Id'] . '&msg=First Name is required field');
        } else if ($type == 'Person' && $lastName == '') {
            return Sinevia\Utils::redirect(Application::createActionUrl('business-customers-manager') . '?businessId=' . $business['Id'] . '&msg=Last Name is required field');
        }

        $Id = \Sinevia\Convert::toCrockford32(Sinevia\Uid::microUid());
        $result = BusinessesModel::createCustomer(array(
                    'Id' => $Id,
                    'BusinessId' => $business['Id'],
                    'Status' => 'Inactive',
                    'Type' => $type,
                    'CompanyName' => $companyName,
                    'PersonFirstName' => $firstName,
                    'PersonLastName' => $lastName,
                    'Created' => date('Y-m-d H:i:s'),
                    'Updated' => date('Y-m-d H:i:s')
        ));
        if ($result === false) {
            return Sinevia\Utils::redirect(Application::createActionUrl('business-customers-manager') . '?businessId=' . $business['Id']);
        }
        return Sinevia\Utils::redirect(Application::createActionUrl('business-customer-edit') . '?CustomerId=' . $Id);
    }

    function invoiceDelete() {
        $invoiceId = isset($_REQUEST['invoiceId']) == false ? '' : trim($_REQUEST['invoiceId']);
        $business = $this->business;
        $invoice = array(
            'Status' => 'Deleted'
        );
        $result = BusinessesModel::updateInvoiceById($invoiceId, $invoice);
        if ($result === false) {
            return $this->flashError('Invoice failed to be delete. Please try again later');
        } else {
            $message = 'Your invoice was successfully deleted.';
            return $this->flashSuccess($message, Application::createActionUrl('business-invoice-manager') . '?businessId=' . $business['Id'], 5);
        }
    }

    function customerDelete() {
        $customerId = isset($_REQUEST['customerId']) == false ? '' : trim($_REQUEST['customerId']);
        $business = $this->business;
        $customer = array(
            'Status' => 'Deleted'
        );
        $result = BusinessesModel::updateCustomerById($customerId, $customer);
        if ($result === false) {
            return $this->flashError('Customer failed to be delete. Please try again later');
        } else {
            $message = 'Your customer was successfully deleted.';
            return $this->flashSuccess($message, Application::createActionUrl('business-customers-manager') . '?businessId=' . $business['Id'], 5);
        }
    }

    function quoteDelete() {
        $quoteId = isset($_REQUEST['quoteId']) == false ? '' : trim($_REQUEST['quoteId']);
        $business = $this->business;
        $quote = array(
            'Status' => 'Deleted'
        );
        $result = BusinessesModel::updateQuoteById($quoteId, $quote);
        if ($result === false) {
            return $this->flashError('Quote failed to be delete. Please try again later');
        } else {
            $message = 'Your quote was successfully deleted.';
            return $this->flashSuccess($message, Application::createActionUrl('business-quotes-manager') . '?businessId=' . $business['Id'], 5);
        }
    }

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

    function quoteSave() {
        $data = isset($_REQUEST['data']) == false ? '' : $_REQUEST['data'];
        $business = $this->business;

        if ($data['QuoteId'] == '') {
            return 'Quote missing';
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
        $quote = array(
            'Id' => $data['QuoteId'],
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
        //BusinessesModel::getMySqlDatabase()->debug = true;
        BusinessesModel::updateQuoteById($data['QuoteId'], $quote);

        $result = BusinessesModel::deleteQuoteItems($data['QuoteId']);
        foreach ($data['Items'] as $value) {
            $Id = \Sinevia\Convert::toCrockford32(Sinevia\Uid::microUid());
            $quoteitem = array(
                'Id' => isset($value['itemId']) ? $value['itemId'] : $Id,
                'BusinessId' => $business['Id'],
                'QuoteId' => $data['QuoteId'],
                'Details' => trim($value['Description']),
                'Units' => trim($value['UnitsQty']),
                'PricePerUnit' => trim($value['UnitPrice']),
                'Total' => trim($value['Subtotal'])
            );
            BusinessesModel::insertQuoteItem($quoteitem);
        }

        $result = Application::getDatabase()->transactionCommit();
        if ($result == false) {
            Application::getDatabase()->transactionRollBack();
            return 'The invoice failed to be saved';
        } else {
            return 'success';
        }
    }

    function quoteSend() {
        $data = isset($_REQUEST['data']) == false ? '' : $_REQUEST['data'];
        if ($data['recipient'] == '') {
            return 'Recipient is required field';
        } else if ($data['subject'] == '') {
            return 'Subject is required field';
        } else if ($data['message'] == '') {
            return 'Message is required field';
        }

        $message = explode("\n", $data['message']);
        $html_email = implode("<br />\n", $message);
        $text_email = implode("\n", $message);
        $mail = array(
            'from' => Application::getEmailAdmin(),
            'to' => $data['recipient'],
            'cc' => Application::getEmailAdmin(),
            'bcc' => '',
            'text' => $text_email,
            'html' => $html_email,
            'subject' => $data['subject'],
        );

        $mail_sent = Application::sendMail($mail);

        if ($mail_sent == true) {
            session_regenerate_id();
            $message = 'Quote was sent successfully via email.';
            return $message;
        } else {
            $message = 'There was a system problem with sending emails. Please try again later';
            return $message;
        }
    }

    function invoiceSend() {
        $data = isset($_REQUEST['data']) == false ? '' : $_REQUEST['data'];
        if ($data['recipient'] == '') {
            return 'Recipient is required field';
        } else if ($data['subject'] == '') {
            return 'Subject is required field';
        } else if ($data['message'] == '') {
            return 'Message is required field';
        }

        $message = explode("\n", $data['message']);
        $html_email = implode("<br />\n", $message);
        $text_email = implode("\n", $message);
        $mail = array(
            'from' => Application::getEmailAdmin(),
            'to' => $data['recipient'],
            'cc' => Application::getEmailAdmin(),
            'bcc' => '',
            'text' => $text_email,
            'html' => $html_email,
            'subject' => $data['subject'],
        );

        $mail_sent = Application::sendMail($mail);

        if ($mail_sent == true) {
            session_regenerate_id();
            $message = 'Invoice was sent successfully via email.';
            return $message;
        } else {
            $message = 'There was a system problem with sending emails. Please try again later';
            return $message;
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

    function customerEdit() {
        /* START: Data */
        $business = $this->business;
        $customerId = isset($_REQUEST['CustomerId']) == false ? '' : trim($_REQUEST['CustomerId']);
        $message = isset($_REQUEST['msg']) == false ? '' : trim($_REQUEST['msg']);
        $action = isset($_REQUEST['action']) == false ? '' : trim($_REQUEST['action']);
        /* END: Data */

        $customer = BusinessesModel::getCustomerById($customerId);
        if ($customer == false) {
            return Sinevia\Utils::redirect(Application::createActionUrl('business-customers-manager') . '?BusinessId=' . $business['Id'] . '&msg=This customer  does not exist any more.');
        }

        /* START: Data */
        $type = isset($_REQUEST['Type']) == false ? $customer['Type'] : trim($_REQUEST['Type']);
        $companyName = isset($_REQUEST['CompanyName']) == false ? $customer['CompanyName'] : trim($_REQUEST['CompanyName']);
        $personFirstName = isset($_REQUEST['PersonFirstName']) == false ? $customer['PersonFirstName'] : trim($_REQUEST['PersonFirstName']);
        $personLastName = isset($_REQUEST['PersonLastName']) == false ? $customer['PersonLastName'] : trim($_REQUEST['PersonLastName']);
        $address1 = isset($_REQUEST['Address1']) == false ? $customer['Address1'] : trim($_REQUEST['Address1']);
        $address2 = isset($_REQUEST['Address2']) == false ? $customer['Address2'] : trim($_REQUEST['Address2']);
        $city = isset($_REQUEST['City']) == false ? $customer['City'] : trim($_REQUEST['City']);
        $province = isset($_REQUEST['Province']) == false ? $customer['Province'] : trim($_REQUEST['Province']);
        $country = isset($_REQUEST['Country']) == false ? $customer['Country'] : trim($_REQUEST['Country']);
        $postCode = isset($_REQUEST['PostCode']) == false ? $customer['PostCode'] : trim($_REQUEST['PostCode']);
        $emailAddressInvoice = isset($_REQUEST['EmailAddressInvoice']) == false ? $customer['EmailAddressInvoice'] : trim($_REQUEST['EmailAddressInvoice']);
        $emailAddressQuote = isset($_REQUEST['EmailAddressQuote']) == false ? $customer['EmailAddressQuote'] : trim($_REQUEST['EmailAddressQuote']);
        $status = isset($_REQUEST['Status']) == false ? $customer['Status'] : trim($_REQUEST['Status']);
        $memo = isset($_REQUEST['AdminNotes']) == false ? $customer['AdminNotes'] : trim($_REQUEST['AdminNotes']);
        /* END: Data */

        if ($action != '') {
            if ($type == 'Company' && $companyName == '') {
                $message = 'Company name is required field';
            } else if ($type == 'Person' && $personFirstName == '') {
                $message = 'First name is required field';
            } else if ($type == 'Person' && $personLastName == '') {
                $message = 'Last name is required field';
            } else if ($address1 == '') {
                $message = 'Address1 is required field';
            } else if ($city == '') {
                $message = 'City is required field';
            } else if ($province == '') {
                $message = 'Province is required field';
            } else if ($country == '') {
                $message = 'Country is required field';
            } else if ($postCode == '') {
                $message = 'Post Code is required field';
            } else if ($emailAddressInvoice == '') {
                $message = 'Invoice Email is required field';
            } else if (!filter_var($emailAddressInvoice, FILTER_VALIDATE_EMAIL)) {
                $message = "Invoice Email is incorrect";
            } else if ($emailAddressQuote == '') {
                $message = 'Quote Email  is required field';
            } else if (!filter_var($emailAddressQuote, FILTER_VALIDATE_EMAIL)) {
                $message = "Quote Email is incorrect";
            }

            if ($message == '') {
                $customer = array(
                    'Id' => $customerId,
                    'BusinessId' => $business['Id'],
                    'Status' => $status,
                    'Type' => $type,
                    'CompanyName' => $companyName,
                    'PersonFirstName' => $personFirstName,
                    'PersonLastName' => $personLastName,
                    'Address1' => $address1,
                    'Address2' => $address2,
                    'City' => $city,
                    'Province' => $province,
                    'Country' => $country,
                    'PostCode' => $postCode,
                    'EmailAddressInvoice' => $emailAddressInvoice,
                    'EmailAddressQuote' => $emailAddressQuote,
                    'Updated' => date('Y-m-d H:i:s'),
                    'AdminNotes' => $memo
                );
                $result = BusinessesModel::updateCustomerById($customer['Id'], $customer);
                if ($result == false) {
                    $message = 'The customer failed to be edit.';
                } else {
                    $message = 'You successfully update a customer';
                }
                if ($action == 'save') {
                    return Sinevia\Utils::redirect(Application::createActionUrl('business-customer-edit') . '?CustomerId=' . $customerId . '&msg=' . $message);
                } else if ($action == 'close') {
                    return Sinevia\Utils::redirect(Application::createActionUrl('business-customers-manager') . '?BusinessId=' . $business['Id'] . '&msg=' . $message);
                }
            }
        }

        /* START: View */
        $webpage_title = 'Business';
        $webpage_content = \Sinevia\Template::fromFile($this->templates_directory . 'business-customer-edit.phtml', get_defined_vars());
        return \Sinevia\Template::fromFile($this->templates_directory . 'layout.phtml', get_defined_vars());
        /* END: View */
    }

    function quoteEdit() {
        $quoteId = isset($_REQUEST['QuoteId']) == false ? '' : trim($_REQUEST['QuoteId']);
        $customers = BusinessesModel::getActiveCustomers();
        $message = isset($_REQUEST['msg']) == false ? '' : trim($_REQUEST['msg']);

        $quote = BusinessesModel::getQuoteById($quoteId);
        $quote_item = BusinessesModel::getQuoteItemById($quoteId);

        /* START: View */
        $webpage_content = \Sinevia\Template::fromFile($this->templates_directory . 'business-quote-edit.phtml', array(
                    'message' => $message,
                    'quote' => $quote,
                    'quote_item' => $quote_item,
                    'customers' => $customers,
        ));
        $template = \Sinevia\Template::fromFile($this->templates_directory . 'layout.phtml', array(
                    'webpage_title' => 'Business',
                    'webpage_content' => $webpage_content,
        ));
        return $template;
        /* END: View */
    }

    function invoiceManager() {
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

    function quotesManager() {
        $user = $this->user;
        $business = $this->business;
        $message = isset($_REQUEST['msg']) == false ? '' : trim($_REQUEST['msg']);
        $quoteId = isset($_REQUEST['filterQuoteId']) == false ? '' : trim($_REQUEST['filterQuoteId']);
        $customerId = isset($_REQUEST['filterCustomerId']) == false ? '' : trim($_REQUEST['filterCustomerId']);
        $status = isset($_REQUEST['filterStatus']) == false ? '' : trim($_REQUEST['filterStatus']);
        $from = isset($_REQUEST['filterFrom']) == false ? '' : trim($_REQUEST['filterFrom']);
        if ($from != '') {
            $time = strtotime($from);
            $from = date('Y-m-d', $time);
        }
        $to = isset($_REQUEST['filterTo']) == false ? '' : trim($_REQUEST['filterTo'] . ' 23:59:59');
        if ($to != '') {
            $time = strtotime($to);
            $to = date('Y-m-d H:i:s', $time);
        }
        $filter_page = isset($_REQUEST['page']) == false ? 0 : trim($_REQUEST['page']);
        $filter_results_per_page = 10;

        $quotes = BusinessesModel::getQuotes(array(
                    'quoteId' => $quoteId,
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


        $filter_results_total_count = array_pop($quotes);

        /* START: Pagination */
        $url = Application::createActionUrl('business-quotes-manager') . '?BusinessId=' . $business['Id'];
        $url .= '&ref=' . rawurlencode($quoteId);
        $url .= '&customerId=' . rawurlencode($customerId);
        $url .= '&status=' . rawurlencode($status);
        $url .= '&page=';
        $pagination = \Sinevia\Utils::pagination($filter_results_total_count, $filter_results_per_page, ($filter_page), $url);
        /* END: Pagination */

        $customers = BusinessesModel::getActiveCustomersByBusinessId($business['Id']);

        /* START: View */
        $webpage_content = \Sinevia\Template::fromFile($this->templates_directory . 'business-quotes-manager.phtml', array(
                    'message' => $message,
                    'quotes' => $quotes,
                    'customers' => $customers,
                    'pagination' => $pagination,
                    'business' => $business,
                    'user' => $user,
                    'quoteId' => $quoteId,
                    'status' => $status,
                    'customerId' => $customerId,
        ));
        $template = \Sinevia\Template::fromFile($this->templates_directory . 'layout.phtml', array(
                    'webpage_title' => 'Business',
                    'webpage_content' => $webpage_content,
        ));
        return $template;
        /* END: View */
    }

    function customersManager() {
        $user = $this->user;
        $business = $this->business;
        $message = isset($_REQUEST['msg']) == false ? '' : trim($_REQUEST['msg']);
        $status = isset($_REQUEST['filterStatus']) == false ? '' : trim($_REQUEST['filterStatus']);
        $keyword = isset($_REQUEST['filterKeyword']) == false ? '' : trim($_REQUEST['filterKeyword']);
        $status = isset($_REQUEST['filterStatus']) == false ? '' : trim($_REQUEST['filterStatus']);
        $filter_page = isset($_REQUEST['page']) == false ? 0 : trim($_REQUEST['page']);
        $filter_results_per_page = 10;

        $customers = BusinessesModel::getCustomers(array(
                    'keyword' => $keyword,
                    'businessId' => $business['Id'],
                    'status' => $status,
                    'orderby' => 'Id',
                    'sort' => 'desc',
                    'limit_from' => $filter_page * $filter_results_per_page,
                    'limit_to' => $filter_results_per_page,
                    'append_count' => true,
        ));


        $filter_results_total_count = array_pop($customers);

        /* START: Pagination */
        $url = Application::createActionUrl('business-customers-manager') . '?BusinessId=' . $business['Id'];
        $url .= '&keyword=' . rawurlencode($keyword);
        $url .= '&status=' . rawurlencode($status);
        $url .= '&page=';
        $pagination = \Sinevia\Utils::pagination($filter_results_total_count, $filter_results_per_page, ($filter_page), $url);
        /* END: Pagination */

        /* START: View */
        $webpage_content = \Sinevia\Template::fromFile($this->templates_directory . 'business-customers-manager.phtml', array(
                    'message' => $message,
                    'customers' => $customers,
                    'pagination' => $pagination,
                    'keyword' => $keyword,
                    'status' => $status,
        ));
        $template = \Sinevia\Template::fromFile($this->templates_directory . 'layout.phtml', array(
                    'webpage_title' => 'Business',
                    'webpage_content' => $webpage_content,
        ));
        return $template;
        /* END: View */
    }

    /**
     * Shows a flashing message
     * @param string $message
     * @param string $url
     * @param int $time
     * @return string
     */
    protected function flashError($message, $url = '', $time = 10) {
        $templates_common_directory = ROOT_DIR . $this->templates_common_directory;

        // START: View
        $template_flash = \Sinevia\Template::fromFile($templates_common_directory . 'flash-error.phtml', array(
                    'message' => $message,
                    'url' => $url,
                    'time' => $time,
        ));
        $template = \Sinevia\Template::fromFile($this->templates_directory . 'layout.phtml', array(
                    'webpage_title' => 'System Message',
                    'webpage_content' => $template_flash
        ));
        return $template;
        // END: View
    }

    /**
     * Shows a flashing message
     * @param string $message
     * @param string $url
     * @param int $time
     * @return string
     */
    protected function flashInfo($message, $url = '', $time = 10) {
        $templates_common_directory = ROOT_DIR . $this->templates_common_directory;
        // START: View
        $template_flash = \Sinevia\Template::fromFile($templates_common_directory . 'flash-info.phtml', array(
                    'message' => $message,
                    'url' => $url,
                    'time' => $time,
        ));
        $template = \Sinevia\Template::fromFile($templates_common_directory . 'layout.phtml', array(
                    'webpage_title' => 'System Message',
                    'webpage_content' => $template_flash
        ));
        return $template;
        // END: View
    }

    /**
     * Shows a flashing message
     * @param string $message
     * @param string $url
     * @param int $time
     * @return string
     */
    protected function flashSuccess($message, $url = '', $time = 10) {
        $templates_common_directory = ROOT_DIR . $this->templates_common_directory;
        // START: View
        $template_flash = \Sinevia\Template::fromFile($this->templates_common_directory . 'flash-success.phtml', array(
                    'message' => $message,
                    'url' => $url,
                    'time' => $time,
        ));
        $template = \Sinevia\Template::fromFile($this->templates_directory . 'layout.phtml', array(
                    'webpage_title' => 'System Message',
                    'webpage_content' => $template_flash
        ));
        return $template;
        // END: View
    }

    /**
     * Shows a flashing message
     * @param string $message
     * @param string $url
     * @param int $time
     * @return string
     */
    protected function flashWarning($message, $url = '', $time = 10) {
        $templates_common_directory = ROOT_DIR . $this->templates_common_directory;
        // START: View
        $template_flash = \Sinevia\Template::fromFile($templates_common_directory . 'flash-warning.phtml', array(
                    'message' => $message,
                    'url' => $url,
                    'time' => $time,
        ));
        $template = \Sinevia\Template::fromFile($templates_common_directory . 'layout.phtml', array(
                    'webpage_title' => 'System Message',
                    'webpage_content' => $template_flash
        ));
        return $template;
        // END: View
    }

}
