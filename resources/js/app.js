
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
import { VuejsDatatableFactory } from 'vuejs-datatable';

Vue.use( VuejsDatatableFactory );

import Loader from 'Components/loaders/Loader.vue';
Vue.component('Loader', Loader);

import Notification from 'Components/notifications/Notification.vue';
Vue.component('Notification', Notification);

import Notifications from 'vue-notification';
Vue.use(Notifications);

import VuejsDialog from 'vuejs-dialog';
 
// include the default style

Vue.use(VuejsDialog);

import VuePhoneNumberInput from 'vue-phone-number-input';



Vue.component('vue-phone-number-input', VuePhoneNumberInput);

Vue.component('purchase-promissory-note-view', require('./views/purchase-promissory-notes/PromissoryNoteView.vue').default);
Vue.component('purchase-promissory-note-table', require('./views/purchase-promissory-notes/PromissoryNoteTable.vue').default);

Vue.component('invoice-view', require('./views/invoices/InvoiceView.vue').default);
Vue.component('invoice-table', require('./views/invoices/InvoiceTable.vue').default);

Vue.component('customer-table', require('./views/customers/CustomerTable.vue').default);
Vue.component('customer-view', require('./views/customers/CustomerView.vue').default);

Vue.component('term-table', require('./views/terms/TermTable.vue').default);
Vue.component('term-view', require('./views/terms/TermView.vue').default);

Vue.component('term-customer-table', require('./views/terms/TermCustomerTable.vue').default);
Vue.component('term-customer-view', require('./views/terms/TermCustomerView.vue').default);

Vue.component('payment-method-table', require('./views/payment-methods/PaymentMethodTable.vue').default);
Vue.component('payment-method-view', require('./views/payment-methods/PaymentMethodView.vue').default);

Vue.component('purchase-order-table', require('./views/purchase-orders/PurchaseOrderTable.vue').default);
Vue.component('purchase-order-view', require('./views/purchase-orders/PurchaseOrderView.vue').default);

Vue.component('purchase-order-return-table', require('./views/purchase-order-returns/PurchaseOrderTable.vue').default);
Vue.component('purchase-order-return-view', require('./views/purchase-order-returns/PurchaseOrderView.vue').default);

Vue.component('screen-setup-view', require('./views/screen-setups/ScreenSetupView.vue').default);

Vue.component('journal-table', require('./views/journals/JournalTable.vue').default);
Vue.component('journal-view', require('./views/journals/JournalView.vue').default);

Vue.component('subsidiary-table', require('./views/subsidiaries/SubsidiaryTable.vue').default);
Vue.component('subsidiary-view', require('./views/subsidiaries/SubsidiaryView.vue').default);

Vue.component('vendor-payment-table', require('./views/vendor-payments/VendorPaymentTable.vue').default);
Vue.component('vendor-payment-form', require('./views/vendor-payments/VendorPaymentForm.vue').default);

Vue.component('general-journal-table', require('./views/general-journals/GeneralJournalTable.vue').default);
Vue.component('general-journal-view', require('./views/general-journals/GeneralJournalView.vue').default);

Vue.component('fiscal-view', require('./views/fiscals/FiscalView.vue').default);
Vue.component('fiscal-show-view', require('./views/fiscals/FiscalShowView.vue').default);

Vue.component('sales-order-table', require('./views/sales-order/SalesOrderTable.vue').default);
Vue.component('sales-order-view', require('./views/sales-order/SalesOrderView.vue').default);

Vue.component('sales-order-returns-table', require('./views/sales-order-returns/SalesOrderTable.vue').default);
Vue.component('sales-order-returns-view', require('./views/sales-order-returns/SalesOrderView.vue').default);

Vue.component('customer-invoice-table', require('./views/customer-invoices/CustomerInvoiceTable.vue').default);
Vue.component('customer-invoice-view', require('./views/customer-invoices/CustomerInvoiceView.vue').default);
Vue.component('customer-invoice-aging-table', require('./views/customer-invoices/CustomerInvoiceAgingTable.vue').default);

Vue.component('sales-delivery-receipt-table', require('./views/sales-delivery-receipts/SalesDeliveryReceiptTable.vue').default);
Vue.component('sales-delivery-receipt-view', require('./views/sales-delivery-receipts/SalesDeliveryReceiptView.vue').default);

Vue.component('chart-of-account-view', require('./views/chart-of-accounts/ChartOfAccountView.vue').default);

Vue.component('main-account-view', require('./views/main-accounts/MainAccountView.vue').default);
Vue.component('main-account-coa-view', require('./views/main-accounts/MainAccountCOAView.vue').default);

Vue.component('adjustments-table', require('./views/adjustments/AdjustmentTable.vue').default);
Vue.component('adjustments-view', require('./views/adjustments/AdjustmentView.vue').default);


// Vue.component('customer-posting-profile-table', require('./views/customer-posting-profiles/CustomerPostingProfileTable.vue').default);
// Vue.component('customer-posting-profile-view', require('./views/customer-posting-profiles/CustomerPostingProfileView.vue').default);
// Vue.component('table-restriction-view', require('./views/customer-posting-profiles/TableRestrictionView.vue').default);

Vue.component('setup-table', require('./views/setups/SetupTable.vue').default);
Vue.component('setup-view', require('./views/setups/SetupView.vue').default);

Vue.component('fiscal-calendar-view', require('./views/fiscal-calendars/FiscalCalendarView.vue').default);
Vue.component('fiscal-calendar-show-view', require('./views/fiscal-calendars/FiscalCalendarShowView.vue').default);

Vue.component('date-interval-table', require('./views/date-intervals/DateIntervalTable.vue').default);
Vue.component('date-interval-view', require('./views/date-intervals/DateIntervalView.vue').default);

Vue.component('ledger-reason-table', require('./views/ledger-reasons/LedgerReasonTable.vue').default);
Vue.component('ledger-reason-view', require('./views/ledger-reasons/LedgerReasonView.vue').default);

Vue.component('ledger-calendar-view', require('./views/ledger-calendars/LedgerCalendarShowView.vue').default);

Vue.component('department-table', require('./views/departments/DepartmentTable.vue').default);
Vue.component('department-view', require('./views/departments/DepartmentView.vue').default);

Vue.component('position-table', require('./views/positions/PositionTable.vue').default);
Vue.component('position-view', require('./views/positions/PositionView.vue').default);

Vue.component('cost-center-table', require('./views/cost-centers/CostCenterTable.vue').default);
Vue.component('cost-center-view', require('./views/cost-centers/CostCenterView.vue').default);

Vue.component('user-table', require('./views/users/UserTable.vue').default);
Vue.component('user-view', require('./views/users/UserView.vue').default);

Vue.component('admin-user-table', require('./views/admin-users/AdminUserTable.vue').default);
Vue.component('admin-user-view', require('./views/admin-users/AdminUserView.vue').default);

Vue.component('cash-discount-table', require('./views/cash-discounts/CashDiscountTable.vue').default);
Vue.component('cash-discount-view', require('./views/cash-discounts/CashDiscountView.vue').default);

Vue.component('customer-summary-table', require('./views/customer-summaries/CustomerSummaryTable.vue').default);
Vue.component('customer-summary-view', require('./views/customer-summaries/CustomerSummaryView.vue').default);

Vue.component('payment-day-table', require('./views/payment-days/PaymentDayTable.vue').default);
Vue.component('payment-day-view', require('./views/payment-days/PaymentDayView.vue').default);

Vue.component('journal-name-table', require('./views/journal-names/JournalNameTable.vue').default);
Vue.component('journal-name-view', require('./views/journal-names/JournalNameView.vue').default);

Vue.component('vendor-table', require('./views/vendors/VendorTable.vue').default);
Vue.component('vendor-view', require('./views/vendors/VendorView.vue').default);

Vue.component('trial-balance-table', require('./views/trial-balance/TrialBalanceTable.vue').default);
Vue.component('trial-balance-view', require('./views/trial-balance/TrialBalanceView.vue').default);

Vue.component('opening-transaction-table', require('./views/opening-transactions/OpeningTransactionTable.vue').default);
Vue.component('opening-transaction-view', require('./views/opening-transactions/OpeningTransactionView.vue').default);

Vue.component('closing-transaction-table', require('./views/closing-transactions/ClosingTransactionTable.vue').default);
Vue.component('closing-transaction-view', require('./views/closing-transactions/ClosingTransactionView.vue').default);

Vue.component('vendor-invoice-table', require('./views/vendor-invoices/VendorInvoiceTable.vue').default);
Vue.component('vendor-invoice-view', require('./views/vendor-invoices/VendorInvoiceView.vue').default);
Vue.component('vendor-invoice-aging-table', require('./views/vendor-invoices/VendorInvoiceAgingTable.vue').default);

Vue.component('purchase-delivery-receipt-table', require('./views/purchase-delivery-receipts/PurchaseDeliveryReceiptTable.vue').default);
Vue.component('purchase-delivery-receipt-view', require('./views/purchase-delivery-receipts/PurchaseDeliveryReceiptView.vue').default);

Vue.component('customer-payment-table', require('./views/customer-payments/CustomerPaymentTable.vue').default);
Vue.component('customer-payment-form', require('./views/customer-payments/CustomerPaymentForm.vue').default);

Vue.component('customer-payment-fee-table', require('./views/customer-payment-fees/CustomerPaymentFeeTable.vue').default);
Vue.component('customer-payment-fee-view', require('./views/customer-payment-fees/CustomerPaymentFeeView.vue').default);

Vue.component('vendor-payment-journal-table', require('./views/vendor-payment-journals/VendorPaymentJournalTable.vue').default);
Vue.component('vendor-payment-journal-view', require('./views/vendor-payment-journals/VendorPaymentJournalView.vue').default);

Vue.component('customer-payment-journal-table', require('./views/customer-payment-journals/CustomerPaymentJournalTable.vue').default);
Vue.component('customer-payment-journal-view', require('./views/customer-payment-journals/CustomerPaymentJournalView.vue').default);

Vue.component('setup-form-table', require('./views/setup-forms/SetupFormTable.vue').default);
Vue.component('setup-form-view', require('./views/setup-forms/SetupFormView.vue').default);

Vue.component('invoice-approval-journal-table', require('./views/invoice-approval-journals/JournalTable.vue').default);
Vue.component('invoice-approval-journal-view', require('./views/invoice-approval-journals/JournalView.vue').default);

Vue.component('purchase-return-journal-table', require('./views/purchase-return-journals/JournalTable.vue').default);
Vue.component('purchase-return-journal-view', require('./views/purchase-return-journals/JournalView.vue').default);

Vue.component('sales-return-journal-table', require('./views/sales-return-journals/JournalTable.vue').default);
Vue.component('sales-return-journal-view', require('./views/sales-return-journals/JournalView.vue').default);

Vue.component('financial-dimension-table', require('./views/financial-dimensions/FinancialDimensionTable.vue').default);
Vue.component('financial-dimension-view', require('./views/financial-dimensions/FinancialDimensionView.vue').default);

Vue.component('financial-dimension-value-table', require('./views/financial-dimension-values/FinancialDimensionValueTable.vue').default);
Vue.component('financial-dimension-value-view', require('./views/financial-dimension-values/FinancialDimensionValueView.vue').default);

Vue.component('customer-invoice-approval-journal-table', require('./views/customer-invoice-approval-journals/JournalTable.vue').default);
Vue.component('customer-invoice-approval-journal-view', require('./views/customer-invoice-approval-journals/JournalView.vue').default);

Vue.component('bill-of-exchange-table', require('./views/bill-of-exchanges/JournalTable.vue').default);
Vue.component('bill-of-exchange-view', require('./views/bill-of-exchanges/JournalView.vue').default);
Vue.component('bill-of-exchange-create-update', require('./views/bill-of-exchanges/BillOfExchangeCreateUpdate.vue').default);

Vue.component('promissory-note-table', require('./views/promissory-notes/JournalTable.vue').default);
Vue.component('promissory-note-view', require('./views/promissory-notes/JournalView.vue').default);
Vue.component('promissory-note-create-update', require('./views/promissory-notes/PromissoryNoteCreateUpdate.vue').default);

Vue.component('journal-create-update', require('./views/global-forms/JournalHeaderCreateUpdate.vue').default);

Vue.component('customer-payment-fee-setup-table', require('./views/customer-payment-fee-setups/CustomerPaymentFeeSetupTable.vue').default);
Vue.component('customer-payment-fee-setup-view', require('./views/customer-payment-fee-setups/CustomerPaymentFeeSetupView.vue').default);



/**
 * Vue Component For DataTable Vue
 *  
 */

Vue.component('ledger-table', require('./views/ledgers/LedgerTable.vue').default);

Vue.component('chart-of-account-table', require('./views/chart-of-accounts/ChartOfAccountTable.vue').default);

Vue.component('chart-of-accounts-main-account-table', require('./views/chart-of-accounts-main-accounts/ChartOfAccountMainAccountTable.vue').default);

Vue.component('chart-of-accounts-account-structure-table', require('./views/chart-of-accounts-account-structure/ChartOfAccountAccountStructureTable.vue').default);

Vue.component('main-account-table', require('./views/main-accounts/MainAccountTable.vue').default);

Vue.component('main-account-category-table', require('./views/main-accounts-category/MainAccountCategoryTable.vue').default);

Vue.component('link-main-account-table', require('./views/link-main-accounts/LinkMainAccountTable.vue').default);

Vue.component('account-structure-table', require('./views/account-structures/AccountStructureTable.vue').default);

Vue.component('ledger-calendar-table', require('./views/ledger-calendars/LedgerCalendarTable.vue').default);

Vue.component('fiscal-calendar-table', require('./views/fiscal-calendars/FiscalCalendarTable.vue').default);

Vue.component('fiscal-table', require('./views/fiscals/FiscalTable.vue').default);

Vue.component('period-table', require('./views/periods/PeriodTable.vue').default);

Vue.component('journal-names-table', require('./views/journal-names/JournalNameTable.vue').default);

Vue.component('fiscal-period-table', require('./views/fiscal-periods/FiscalPeriodTable.vue').default);
Vue.component('accrual-period-table', require('./views/accrual-periods/AccrualPeriodTable.vue').default);

Vue.component('dashboard', require('./views/dashboards/Dashboard.vue').default);

/**
 * Vue Component For General Ledger
 *  
 */


Vue.component('general-ledger-table', require('./views/general-ledgers/GeneralLedgerTable.vue').default);
Vue.component('general-ledger-view', require('./views/general-ledgers/GeneralLedgerView.vue').default);

Vue.component('client-table', require('./views/clients/ClientTable.vue').default);
Vue.component('client-view', require('./views/clients/ClientView.vue').default);


/**
 * Vue Component For Form Views Vue
 *  
 */

Vue.component('main-account-category-form', require('./views/main-accounts-category/MainAccountCategoryForm.vue').default);

Vue.component('ledger-view', require('./views/ledgers/LedgerView.vue').default);

Vue.component('link-main-account-view', require('./views/link-main-accounts/LinkMainAccountView.vue').default);

Vue.component('link-main-account-process', require('./views/link-main-accounts/LinkMainAccountProcess.vue').default);

Vue.component('fiscal-period-view', require('./views/fiscal-periods/FiscalPeriodView.vue').default);

Vue.component('chart-of-accounts-main-account-view', require('./views/chart-of-accounts-main-accounts/ChartOfAccountMainAccountView.vue').default);

Vue.component('account-structure-view', require('./views/account-structures/AccountStructureView.vue').default);

Vue.component('account-structure-ledger-view', require('./views/account-structures/AccountStructureLedgerView.vue').default);

Vue.component('ledger-calendar-view', require('./views/ledger-calendars/LedgerCalendarView.vue').default);

Vue.component('account-structure-coa-view', require('./views/account-structures/AccountStructureCoaView.vue').default);

// Services
	Vue.component('service-table', require('./views/services/ServiceTable.vue').default);
	Vue.component('service-view', require('./views/services/ServiceView.vue').default);

// Ledger Setup
	// Document Code Control
	Vue.component('document-code-control-table', require('./views/ledger-setups/document-code-controls/DocumentCodeControlTable.vue').default);
	Vue.component('document-code-control-view', require('./views/ledger-setups/document-code-controls/DocumentCodeControlView.vue').default);
// Admin Setup

	// Company
	Vue.component('company-table', require('./views/admin/admin-setups/companies/CompanyTable.vue').default);
	Vue.component('company-view', require('./views/admin/admin-setups/companies/CompanyView.vue').default);

// Inventory

	// Product
	Vue.component('product-table', require('./views/products/ProductTable.vue').default);
	Vue.component('product-view', require('./views/products/ProductView.vue').default);

	// Variant
	Vue.component('variant-table', require('./views/variants/VariantTable.vue').default);
	Vue.component('variant-view', require('./views/variants/VariantView.vue').default);
	Vue.component('product-detail-view', require('./views/variants/ProductDetails.vue').default);

	// Inventory Hand
	Vue.component('inventory-on-hand-table', require('./views/inventory-on-hands/InventoryOnHandTable.vue').default);
	Vue.component('inventory-on-hand-view', require('./views/inventory-on-hands/InventoryOnHandView.vue').default);

// Bank Accounts
Vue.component('vendor-bank-account-table', require('./views/vendor-bank-accounts/VendorBankAccountTable.vue').default);
Vue.component('vendor-bank-account-view', require('./views/vendor-bank-accounts/VendorBankAccountView.vue').default);
Vue.component('vendor-bank-account-create', require('./views/vendor-bank-accounts/VendorBankAccountCreate.vue').default);

// Customer posting profile header
Vue.component('customer-posting-profile-header-table', require('./views/customer-posting-profile-headers/CustomerPostingProfileHeaderTable.vue').default);
Vue.component('customer-posting-profile-header-view', require('./views/customer-posting-profile-headers/CustomerPostingProfileHeaderView.vue').default);

// Vendor posting profile header
Vue.component('vendor-posting-profile-header-table', require('./views/vendor-posting-profile-headers/VendorPostingProfileHeaderTable.vue').default);
Vue.component('vendor-posting-profile-header-view', require('./views/vendor-posting-profile-headers/VendorPostingProfileHeaderView.vue').default);

// Vendor posting profile line
Vue.component('vendor-posting-profile-table', require('./views/vendor-posting-profiles/VendorPostingProfileTable.vue').default);
Vue.component('vendor-posting-profile-view', require('./views/vendor-posting-profiles/VendorPostingProfileView.vue').default);

// Transaction Posting Header
Vue.component('transaction-posting-header-table', require('./views/transaction-posting-headers/TransactionPostingHeaderTable.vue').default);
Vue.component('transaction-posting-header-view', require('./views/transaction-posting-headers/TransactionPostingHeaderView.vue').default);

// Vendor posting profile line
Vue.component('transaction-posting-table', require('./views/transaction-postings/TransactionPostingTable.vue').default);
Vue.component('transaction-posting-view', require('./views/transaction-postings/TransactionPostingView.vue').default);

// Customer posting profile
Vue.component('customer-posting-profile-table', require('./views/customer-posting-profiles/CustomerPostingProfileTable.vue').default);
Vue.component('customer-posting-profile-view', require('./views/customer-posting-profiles/CustomerPostingProfileView.vue').default);

Vue.component('customer-bank-account-table', require('./views/customer-bank-accounts/CustomerBankAccountTable.vue').default);
Vue.component('customer-bank-account-view', require('./views/customer-bank-accounts/CustomerBankAccountView.vue').default);
Vue.component('customer-bank-account-create', require('./views/customer-bank-accounts/CustomerBankAccountCreate.vue').default);

Vue.component('client-bank-account-table', require('./views/client-bank-accounts/ClientBankAccountTable.vue').default);
Vue.component('client-bank-account-view', require('./views/client-bank-accounts/ClientBankAccountView.vue').default);

Vue.component('bank-reason-table', require('./views/bank-reasons/BankReasonTable.vue').default);
Vue.component('bank-reason-view', require('./views/bank-reasons/BankReasonView.vue').default);

Vue.component('letter-of-guarantees-table', require('./views/letter-of-guarantees/LetterOfGuaranteeTable.vue').default);
Vue.component('letter-of-guarantees-view', require('./views/letter-of-guarantees/LetterOfGuaranteeView.vue').default);

Vue.component('deposit-table', require('./views/deposits/DepositTable.vue').default);
Vue.component('deposit-view', require('./views/deposits/DepositView.vue').default);

Vue.component('check-table', require('./views/checks/CheckTable.vue').default);
Vue.component('check-view', require('./views/checks/CheckView.vue').default);

Vue.component('letter-credit-purchases-table', require('./views/letter-credit-purchases/LetterOfCreditTable.vue').default);
Vue.component('letter-credit-purchases-view', require('./views/letter-credit-purchases/LetterOfCreditView.vue').default);

Vue.component('letter-credit-sales-table', require('./views/letter-credit-sales/LetterOfCreditTable.vue').default);
Vue.component('letter-credit-sales-view', require('./views/letter-credit-sales/LetterOfCreditView.vue').default);

Vue.component('bank-documents-table', require('./views/bank-documents/BankDocumentTable.vue').default);
Vue.component('bank-documents-view', require('./views/bank-documents/BankDocumentView.vue').default);

Vue.component('bank-facility-groups-table', require('./views/bank-facility-groups/BankFacilityGroupTable.vue').default);
Vue.component('bank-facility-groups-view', require('./views/bank-facility-groups/BankFacilityGroupView.vue').default);

Vue.component('bank-facility-types-table', require('./views/bank-facility-types/BankFacilityTypeTable.vue').default);
Vue.component('bank-facility-types-view', require('./views/bank-facility-types/BankFacilityTypeView.vue').default);

Vue.component('vendor-payment-method-table', require('./views/vendor-payment-methods/VendorPaymentMethodTable.vue').default);
Vue.component('vendor-payment-method-view', require('./views/vendor-payment-methods/VendorPaymentMethodView.vue').default);

Vue.component('customer-payment-method-table', require('./views/customer-payment-methods/CustomerPaymentMethodTable.vue').default);
Vue.component('customer-payment-method-view', require('./views/customer-payment-methods/CustomerPaymentMethodView.vue')	.default);

Vue.component('inventory-journal-table', require('./views/inventory-journals/JournalTable.vue').default);
Vue.component('inventory-journal-view', require('./views/inventory-journals/JournalView.vue').default);
Vue.component('order-line-table', require('./views/inventory-on-hands/OrderLine.vue').default);

Vue.component('bank-account-transaction-table', require('./views/bank-account-transactions/BankAccountTransactionTable.vue').default);
Vue.component('bank-account-transaction-view', require('./views/bank-account-transactions/BankAccountTransactionView.vue').default);

Vue.component('bank-account-statement-table', require('./views/bank-account-statements/BankAccountStatementTable.vue').default);
Vue.component('bank-account-statement-view', require('./views/bank-account-statements/BankAccountStatementView.vue').default);

Vue.component('procurement-table', require('./views/procurements/ProcurementTable.vue').default);
Vue.component('procurement-view', require('./views/procurements/ProcurementView.vue').default);

Vue.component('bank-account-statement-line-table', require('./views/bank-account-statement-lines/BankAccountStatementLineTable.vue').default);
Vue.component('bank-account-statement-line-view', require('./views/bank-account-statement-lines/BankAccountStatementLineView.vue').default);

Vue.component('cashflow-transaction-table', require('./views/cashflow-transactions/CashflowTransactionTable.vue').default);
Vue.component('cashflow-transaction-view', require('./views/cashflow-transactions/CashflowTransactionView.vue').default);

Vue.component('bank-reconciliation-form', require('./views/bank-reconciliations/BankReconciliationForm.vue').default);

Vue.component('specifications-table', require('./views/specifications/SpecificationTable.vue').default);
Vue.component('specifications-view', require('./views/specifications/SpecificationView.vue').default);


Vue.component('accrual-posting-table', require('./views/accrual-postings/AccrualPostingTable.vue').default);
Vue.component('accrual-posting-view', require('./views/accrual-postings/AccrualPostingView.vue').default);

Vue.component('bank-posting-table', require('./views/bank-postings/BankPostingTable.vue').default);
Vue.component('bank-posting-view', require('./views/bank-postings/BankPostingView.vue').default);

Vue.component('bank-reconciliation-table', require('./views/bank-reconciliations/BankReconciliationTable.vue').default);
Vue.component('bank-reconciliation-view', require('./views/bank-reconciliations/BankReconciliationView.vue').default);

Vue.component('bank-reconciliation-line-table', require('./views/bank-reconciliations/BankReconciliationLineTable.vue').default);
Vue.component('bank-reconciliation-line-view', require('./views/bank-reconciliations/BankReconciliationLineView.vue').default);
Vue.component('bank-reconciliation-line-create', require('./views/bank-reconciliations/BankReconciliationLineCreate.vue').default);

Vue.component('bank-reconciliation-details', require('./views/bank-reconciliations/BankReconciliationDetails.vue').default);

Vue.component('bank-reconciliation-journal-table', require('./views/bank-reconciliation-journals/BankReconciliationJournalTable.vue').default);
Vue.component('bank-reconciliation-journal-view', require('./views/bank-reconciliation-journals/BankReconciliationJournalView.vue').default);

Vue.component('service-task-table', require('./views/service-tasks/ServiceTaskTable.vue').default);
Vue.component('service-task-view', require('./views/service-tasks/ServiceTaskView.vue').default);

Vue.component('charge-table', require('./views/charges/ChargeTable.vue').default);
Vue.component('charge-view', require('./views/charges/ChargeView.vue').default);

Vue.component('payment-fee-table', require('./views/payment-fees/PaymentFeeTable.vue').default);
Vue.component('payment-fee-view', require('./views/payment-fees/PaymentFeeView.vue').default);

Vue.component('vendor-payment-fee-setup-table', require('./views/vendor-payment-fee-setups/VendorPaymentFeeSetupTable.vue').default);
Vue.component('vendor-payment-fee-setup-view', require('./views/vendor-payment-fee-setups/VendorPaymentFeeSetupView.vue').default);

Vue.component('vendor-payment-fee-table', require('./views/vendor-payment-fees/VendorPaymentFeeTable.vue').default);
Vue.component('vendor-payment-fee-view', require('./views/vendor-payment-fees/VendorPaymentFeeView.vue').default);

Vue.component('discount-table', require('./views/discounts/DiscountTable.vue').default);
Vue.component('discount-view', require('./views/discounts/DiscountView.vue').default);

Vue.component('payment-reversal-table', require('./views/payment-reversals/PaymentReversalTable.vue').default);
Vue.component('payment-reversal-view', require('./views/payment-reversals/PaymentReversalView.vue').default);

Vue.component('bank-reconciliation-journal-voucher-create', require('./views/bank-reconciliation-journals/BankReconciliationJournalVoucherCreate.vue').default);
Vue.component('bank-reconciliation-journal-voucher-table', require('./views/bank-reconciliation-journals/BankReconciliationJournalVoucherTable.vue').default);
Vue.component('bank-reconciliation-journal-voucher-view', require('./views/bank-reconciliation-journals/BankReconciliationJournalVoucherView.vue').default);

Vue.component('payment-cancellation-journal-table', require('./views/payment-cancellation-journals/PaymentCancellationJournalTable.vue').default);
Vue.component('payment-cancellation-journal-view', require('./views/payment-cancellation-journals/PaymentCancellationJournalView.vue').default);

Vue.component('payment-cancellation-journal-voucher-create', require('./views/payment-cancellation-journals/PaymentCancellationJournalVoucherCreate.vue').default);
Vue.component('payment-cancellation-journal-voucher-table', require('./views/payment-cancellation-journals/PaymentCancellationJournalVoucherTable.vue').default);
Vue.component('payment-cancellation-journal-voucher-view', require('./views/payment-cancellation-journals/PaymentCancellationJournalVoucherView.vue').default);

Vue.component('tax-view', require('./views/tax-tables/TaxView.vue').default);
Vue.component('tax-table', require('./views/tax-tables/TaxTable.vue').default);

Vue.component('tax-line-create', require('./views/tax-tables/TaxLineCreate.vue').default);
Vue.component('tax-line-table', require('./views/tax-tables/TaxLineTable.vue').default);
Vue.component('tax-line-view', require('./views/tax-tables/TaxLineView.vue').default);

Vue.component('bills-exchange-table', require('./views/bills-exchanges/BillsExchangeTable.vue').default);
Vue.component('bills-exchange-view', require('./views/bills-exchanges/BillsExchangeView.vue').default);

Vue.component('collection-table', require('./views/collections/CollectionTable.vue').default);
Vue.component('collection-view', require('./views/collections/CollectionView.vue').default);

Vue.component('interest-setup-table', require('./views/interest-setups/InterestSetupTable.vue').default);
Vue.component('interest-setup-view', require('./views/interest-setups/InterestSetupView.vue').default);

Vue.component('interest-calculation-table', require('./views/interest-calculations/InterestCalculationTable.vue').default);
Vue.component('interest-calculation-view', require('./views/interest-calculations/InterestCalculationView.vue').default);

Vue.component('interest-note-table', require('./views/interest-notes/InterestNoteTable.vue').default);
Vue.component('interest-note-view', require('./views/interest-notes/InterestNoteView.vue').default);

Vue.component('payment-schedule-table', require('./views/payment-schedules/PaymentScheduleTable.vue').default);
Vue.component('payment-schedule-view', require('./views/payment-schedules/PaymentScheduleView.vue').default);

Vue.component('payment-schedule-line-create', require('./views/payment-schedules/PaymentScheduleLineCreate.vue').default);
Vue.component('payment-schedule-line-table', require('./views/payment-schedules/PaymentScheduleLineTable.vue').default);
Vue.component('payment-schedule-line-view', require('./views/payment-schedules/PaymentScheduleLineView.vue').default);

Vue.component('withholding-tax-table', require('./views/withholding-taxes/WithholdingTaxTable.vue').default);
Vue.component('withholding-tax-view', require('./views/withholding-taxes/WithholdingTaxView.vue').default);

Vue.component('withholding-tax-line-create', require('./views/withholding-taxes/WithholdingTaxLineCreate.vue').default);
Vue.component('withholding-tax-line-table', require('./views/withholding-taxes/WithholdingTaxLineTable.vue').default);
Vue.component('withholding-tax-line-view', require('./views/withholding-taxes/WithholdingTaxLineView.vue').default);

Vue.component('interest-adjustment-table', require('./views/interest-adjustments/InterestAdjustmentTable.vue').default);
Vue.component('interest-adjustment-view', require('./views/interest-adjustments/InterestAdjustmentView.vue').default);

Vue.component('client-select', require('./components/globals/ClientSelect.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import SetupMixin from './mixins/setup.js';

Vue.filter('currency', function(val){
    return val ?  accounting.formatNumber(val) : 0.00;
});

const app = {
	init() {
		this.setup();
	},

	setup() {
		new Vue({
			el: '#app',
			
			mixins: [ SetupMixin ],
		});
	}
}

app.init();
