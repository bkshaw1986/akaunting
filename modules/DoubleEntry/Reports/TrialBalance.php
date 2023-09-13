<?php

namespace Modules\DoubleEntry\Reports;

use App\Abstracts\Report;
use App\Utilities\Date;
use Illuminate\Support\Str;
use Modules\DoubleEntry\Models\Account;
use Modules\DoubleEntry\Models\DEClass;

class TrialBalance extends Report
{
    public $default_name = 'double-entry::general.trial_balance';

    public $category = 'general.accounting';

    public $icon = 'balance';

    public $type = 'detail';

    public $column_name_width = 'w-8/12';

    public $column_value_width = 'w-2/12';

    public $content_footer_total = ['debit' => 0, 'credit' => 0];

    public $no_records = false;

    public function getGrandTotal()
    {
        if (!$this->loaded) {
            $this->load();
        }

        $total = (double) $this->footer_totals['debit'] - (double) $this->footer_totals['credit'];

        $total = money($total, setting('default.currency'), true)->format();

        return $total;
    }

    public function setViews()
    {
        parent::setViews();

        $this->views['detail'] = 'double-entry::trial_balance.detail';
        $this->views['detail.content.header'] = 'double-entry::trial_balance.content.header';
        $this->views['detail.content.footer'] = 'double-entry::trial_balance.content.footer';
        $this->views['detail.table.header'] = 'double-entry::trial_balance.table.header';
        $this->views['detail.table.body'] = 'double-entry::trial_balance.table.body';
        $this->views['detail.table.row'] = 'double-entry::trial_balance.table.row';
        $this->views['detail.table.footer'] = 'double-entry::trial_balance.table.footer';
    }

    public function setTables()
    {
        $this->tables = DEClass::has('accounts.ledgers')
            ->get(['name'])
            ->mapWithKeys(function ($class) {
                $class->name = trans($class->name);

                return [Str::lower($class->name) => $class->name];
            })
            ->all();
    }

    public function setDates()
    {
        foreach ($this->tables as $table_key => $table_name) {
            $this->footer_totals[$table_key]['debit'] = 0;
            $this->footer_totals[$table_key]['credit'] = 0;
        }
    }

    public function setData()
    {
        $query = $this->applyFilters(Account::query());

        $accounts = $query->get();

        if ($accounts->isEmpty()) {
            $this->no_records = true;

            return;
        }

        $accounts = $this->setDateInterval($accounts);

        foreach ($this->tables as $table_key => $table_name) {
            foreach ($this->row_values[$table_key] as $id => $value) {
                $account = $accounts->where('id', $id)->first();

                if (is_null($account)) {
                    continue;
                }

                $balance = $account->balance_without_subaccounts;

                if ($balance < 0) {
                    $this->row_values[$table_key][$id]['debit'] = 0;
                    $this->row_values[$table_key][$id]['credit'] = abs($balance);
                    $this->footer_totals[$table_key]['credit'] += abs($balance);
                }

                if ($balance > 0) {
                    $this->row_values[$table_key][$id]['debit'] = $balance;
                    $this->row_values[$table_key][$id]['credit'] = 0;
                    $this->footer_totals[$table_key]['debit'] += $balance;
                }

                $this->row_names[$table_key][$id] = $account->name_linked_general_ledger;
            }

            $this->content_footer_total['debit'] += $this->footer_totals[$table_key]['debit'];
            $this->content_footer_total['credit'] += $this->footer_totals[$table_key]['credit'];
        }
    }

    public function getFields()
    {
        return [];
    }

    public function setDateInterval($accounts)
    {
        $report_at = $this->getSearchStringValue('report_at', Date::today()->toDateString());

        if (is_array($report_at)) {
            $report_at = $report_at[0];
        }

        $end_date = Date::createFromFormat('Y-m-d', $report_at)
            ->endOfDay();

        $start_date = Date::createFromTimestamp(0)->startOfDay();

        $accounts->map(function ($account) use ($start_date, $end_date) {
            $account->start_date = $start_date;
            $account->end_date = $end_date;

            return $account;
        });

        return $accounts;
    }
}
