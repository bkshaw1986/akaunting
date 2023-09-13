@if($class->footer_totals[$table_key]['debit'] > 0 || $class->footer_totals[$table_key]['credit'] > 0)
<thead>
    <tr class="border-b border-purple">
        <th class="ltr:text-left rtl:text-right text-xl text-purple font-bold uppercase" colspan="{{ count($class->dates) + 2 }}">{{ $table_name }}</th>
    </tr>
</thead>
@endif
