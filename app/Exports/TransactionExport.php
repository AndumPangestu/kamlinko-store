<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionExport implements FromCollection, WithMapping, WithHeadingRow, WithHeadings, WithStyles
{
    public function model(array $row)
    {
        return new Transaction([
            'ID' => $row['Transaction ID'],
            'Invoice Number' => $row['Invoice Number'],
            'Customer Name' => $row['Customer Name'],
            'Customer Email' => $row['Customer Email'],
            'Customer Phone' => $row['Customer Phone'],
            'Customer Address' => $row['Customer Address'],
            'Subdistrict' => $row['Subdistrict'],
            'City' => $row['City'],
            'Province' => $row['Province'],
            'ZIP' => $row['ZIP'],
            'Voucher Code' => $row['Voucher Code'],
            'Voucher Type' => $row['Voucher Type'],
            'Branch Store' => $row['Branch Store'],
            'Transaction Status' => $row['Transaction Status'],
            'Payment Method' => $row['Payment Method'],
            'Payment Date' => $row['Payment Date'],
            'Delivery Method' => $row['Delivery Method'],
            'Delivery Service' => $row['Delivery Service'],
            'Delivery Date' => $row['Delivery Date'],
            'Delivery Fee' => $row['Delivery Fee'],
            'Total Discount' => $row['Total Discount'],
            'Subtotal' => $row['Subtotal'],
            'Tax' => $row['Tax'],
            'Total' => $row['Total'],
            'Total Weight' => $row['Total Weight'],
            'Transaction Date' => $row['Transaction Date'],
            'Action Taken By' => $row['Action Taken By'],
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Invoice Number',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Customer Address',
            'Subdistrict',
            'City',
            'Province',
            'ZIP',
            'Voucher Code',
            'Voucher Type',
            'Branch Store',
            'Transaction Status',
            'Payment Method',
            'Payment Date',
            'Delivery Method',
            'Delivery Service',
            'Delivery Date',
            'Delivery Fee',
            'Total Discount',
            'Subtotal',
            'Tax',
            'Total',
            'Total Weight',
            'Transaction Date',
            'Action Taken By',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id ? $transaction->id : null,
            $transaction->invoice_number ? $transaction->invoice_number : null,
            $transaction->user ? $transaction->user->name : null,
            $transaction->user ? $transaction->user->email : null,
            $transaction->user ? $transaction->user->phone : null,
            $transaction->userAddress ? $transaction->userAddress->address : null,
            $transaction->userAddress ? $transaction->userAddress->subdistrict : null,
            $transaction->userAddress ? $transaction->userAddress->city : null,
            $transaction->userAddress ? $transaction->userAddress->province : null,
            $transaction->userAddress ? $transaction->userAddress->zip : null,
            $transaction->voucher ? $transaction->voucher->code : null,
            $transaction->voucher ? $transaction->voucher->type : null,
            $transaction->branch_store ? $transaction->branch_store : null,
            $transaction->transaction_status ? $transaction->transaction_status : null,
            $transaction->payment_method ? $transaction->payment_method : null,
            $transaction->payment_date ? $transaction->payment_date : null,
            $transaction->delivery_method ? $transaction->delivery_method : null,
            $transaction->delivery_service ? $transaction->delivery_service : null,
            $transaction->delivery_date ? $transaction->delivery_date : null,
            $transaction->delivery_fee ? $transaction->delivery_fee : null,
            $transaction->total_discount ? $transaction->total_discount : null,
            $transaction->subtotal ? $transaction->subtotal : null,
            $transaction->tax ? $transaction->tax : null,
            $transaction->total ? $transaction->total : null,
            $transaction->total_weight ? $transaction->total_weight : null,
            $transaction->created_at ? $transaction->created_at : null,
            $transaction->action_taken_by ? $transaction->admin->name : null,
        ];
    }

    /**
     * Apply styles to the worksheet.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Make the first row (heading row) bold
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Transaction::all();
    }
}
