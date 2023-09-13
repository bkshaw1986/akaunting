<?php

return [

    'name' => 'Double-Entry',
    'description' => 'Chart of Accounts, Manual Journal, General Ledger, and more',

    'accounting' => 'Accounting',
    'chart_of_accounts' => 'Chart of Account|Chart of Accounts',
    'coa' => 'COA',
    'journal_entry' => 'Manual Journal',
    'general_ledger' => 'General Ledger',
    'balance_sheet' => 'Balance Sheet',
    'trial_balance' => 'Trial Balance',
    'journal_report' => 'Manual Journal',
    'account' => 'Account',
    'debit' => 'Debit',
    'credit' => 'Credit',
    'total_type' => 'Total :type',
    'totals_balance' => 'Totals and Closing Balance',
    'balance_change' => 'Balance Change',
    'bank_cash' => 'Bank and Cash',
    'default_type' => 'Default :type',
    'current_year_earnings' => 'Current Year Earnings',
    'liabilities_equities' => 'Liabilities & Equities',
    'ledgers' => 'Ledger|Ledgers',
    'bank_accounts' => 'Account|Accounts',
    'tax_rates' => 'Tax Rate|Tax Rates',
    'edit_account' => 'Edit :type Account',
    'issued' => 'Issued',
    'sub' => 'Sub',
    'parents' => 'Parent|Parents',
    'journals' => 'Journal|Journals',
    'entries' => 'Entry|Entries',
    'search_keywords' => 'Default Chart of Accounts, Manual Journal Entry',
    'journals_description' => 'Journals are created with debit and credit entries to reflect in General Ledger.',

    'accounts' => [
        'receivable' => 'Accounts Receivable',
        'payable' => 'Accounts Payable',
        'sales' => 'Sales',
        'expenses' => 'General Expenses',
        'sales_discount' => 'Sales Discount',
        'purchase_discount' => 'Purchase Discount',
        'owners_contribution' => 'Owners Contribution',
        'payroll' => 'Payroll',
    ],

    'document' => [
        'detail' => 'An :class account is used for proper bookkeeping of your :type and to keep your reports accurate.',
    ],

    'empty' => [
        'journal_entry' => 'A journal entry is the act of keeping or making records of any transactions. The journal entry can consist of several recordings, each of which is either a debit or a credit.',
    ],

    'form_description' => [
        'manual_journal' => [
            'general' => 'Here you can enter the general information of manual journal such as date, number, currency, description, etc.',
            'items' => 'Here you can enter the items of manual journal such as account, debit, credit, etc.',
        ],
        'chart_of_accounts' => [
            'general' => 'Here you can enter all information related to a chart of account.',
        ],
    ],

];
