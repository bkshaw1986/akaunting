<?php

namespace Modules\DoubleEntry\Reports;

use App\Abstracts\Report;
use App\Utilities\Date;
use Modules\DoubleEntry\Models\Account;
use Modules\DoubleEntry\Models\DEClass;
use Modules\DoubleEntry\Models\Journal;

class BalanceSheet extends Report
{
    public $default_name = 'double-entry::general.balance_sheet';

    public $category = 'general.accounting';

    public $icon = 'balance';

    public $total_liabilities_equity = 0;

    public function getGrandTotal()
    {
        return trans('general.na');
    }

    public function setViews()
    {
        parent::setViews();

        $this->views['show'] = 'double-entry::balance_sheet.show';
        $this->views['detail'] = 'double-entry::balance_sheet.detail';
        $this->views['detail.table.header'] = 'double-entry::balance_sheet.table.header';
        $this->views['detail.table.body'] = 'double-entry::balance_sheet.table.body';
        $this->views['detail.table.row'] = 'double-entry::balance_sheet.table.row';
        $this->views['detail.table.footer'] = 'double-entry::balance_sheet.table.footer';
    }

    public function setData()
    {
        $accounts = [];
        $liabilities = 0;

        $report_at = $this->getSearchStringValue('report_at', Date::today()->toDateString());

        if (is_array($report_at)) {
            $report_at = $report_at[1];
        }

        $end_date = Date::createFromFormat('Y-m-d', $report_at)
            ->endOfDay();

        $start_date = Date::createFromTimestamp(0)->startOfDay();

        $basis = $this->getSearchStringValue('basis', 'accrual');

        $classes = DEClass::whereNotIn('name', ['double-entry::classes.income', 'double-entry::classes.expenses'])
            ->with(['types', 'types.accounts' => function ($query) use ($start_date, $end_date) {
                $query->whereHas('ledgers', function ($query) use ($start_date, $end_date) {
                    $query->whereBetween('issued_at', [$start_date, $end_date]);
                });
            }])->get();

        foreach ($classes as $class) {
            $class->total = 0;

            foreach ($class->types as $type) {
                $type->total = 0;

                if ($type->name == 'double-entry::types.equity') {
                    $account = $this->calculateCurrentYearEarnings($start_date, $end_date, $basis);
                    $accounts[$type->id][] = $account;
                    $type->total += $account->de_balance;
                    $class->total += $account->de_balance;
                }

                foreach ($type->accounts as $account) {
                    $account->start_date = $start_date;
                    $account->end_date = $end_date;
                    $balance = $account->balance_without_subaccounts;

                    if (
                        $type->name == 'double-entry::types.equity' ||
                        $class->name == 'double-entry::classes.liabilities'
                    ) {
                        $balance = $balance * -1;
                    }

                    $account->de_balance = $balance;
                    $type->total += $balance;
                    $class->total += $balance;

                    $accounts[$type->id][] = $account;
                }
            }

            if ($class->name == 'double-entry::classes.liabilities') {
                $liabilities = $class->total;
            }

            if ($class->name == 'double-entry::classes.equity') {
                $this->total_liabilities_equity = $liabilities + $class->total;
            }
        }

        $this->de_classes = $classes;
        $this->de_accounts = $accounts;
    }

    public function getFields()
    {
        return [];
    }

    protected function calculateCurrentYearEarnings($start_date, $end_date, $basis)
    {
        $income = DEClass::where('name', 'double-entry::classes.income')
            ->first()
            ->accounts()
            ->whereHas('ledgers', function ($query) use ($start_date, $end_date, $basis) {
                $query->whereBetween('issued_at', [$start_date, $end_date]);

                if (isset($basis) && $basis == 'cash') {
                    $query->where(function ($query) use ($basis) {
                        $query->where('ledgerable_type', Transaction::class)
                            ->OrWhereHasMorph('ledgerable', [
                                Journal::class,
                            ], function ($query) use ($basis) {
                                $query->where('basis', $basis);
                            });
                    });
                }
            })
            ->get()
            ->map(function ($account) use ($start_date, $end_date) {
                $account->start_date = $start_date;
                $account->end_date = $end_date;

                return $account;
            })
            ->sum(function ($account) {
                return $account->balance_without_subaccounts;
            });

        $expense = DEClass::where('name', 'double-entry::classes.expenses')
            ->first()
            ->accounts()
            ->whereHas('ledgers', function ($query) use ($start_date, $end_date) {
                $query->whereBetween('issued_at', [$start_date, $end_date]);

                if (isset($basis) && $basis == 'cash') {
                    $query->where(function ($query) use ($basis) {
                        $query->where('ledgerable_type', Transaction::class)
                            ->OrWhereHasMorph('ledgerable', [
                                Journal::class,
                            ], function ($query) use ($basis) {
                                $query->where('basis', $basis);
                            });
                    });
                }
            })
            ->get()
            ->map(function ($account) use ($start_date, $end_date) {
                $account->start_date = $start_date;
                $account->end_date = $end_date;

                return $account;
            })
            ->sum(function ($account) {
                return $account->balance_without_subaccounts;
            });

        $earning = new Account();
        $earning->name = trans('double-entry::general.current_year_earnings');
        $earning->de_balance = abs($income) - $expense;

        return $earning;
    }
}
