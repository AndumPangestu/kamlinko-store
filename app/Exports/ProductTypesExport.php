<?php

namespace App\Exports;

use App\Models\ProductType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductTypesExport implements FromCollection, WithMapping, WithHeadingRow, WithHeadings, WithStyles
{
    public function model(array $row)
    {
        return new ProductType([
            'Product ID' => $row['id'],
        ]);
    }

    public function headings(): array
    {
        return [
            'SKU',
            // 'Product ID',
            // 'Product Type ID',
            'Product Name',
            'Product Type Name',
            'Category',
            'Tag',
            'Brand',
            // 'Color',
            'Price',
            // 'Discount Fixed',
            // 'Discount Percentage',
            // 'Weight',
            'Stock',
            'Total Sales',
            // 'Product Description',
            // 'Product Long Description',
            // 'Product Type Description',
            'Highlight',
            'Views',
            'Created At',
            'Created By',
            // 'Updated At',
            // 'Updated By,'
        ];
    }

    public function map($productType): array
    {
        return [
            $productType->sku ?? null,
            // $productType->product->id ?? null,
            // $productType->id ?? null,
            $productType->product->name ?? null,
            $productType->name ?? null,
            $productType->product->category->name ?? null,
            $productType->product->tag->name ?? null,
            $productType->product->brand->name ?? null,
            // $productType->color ?? null,
            $productType->price == 0 ? '0' : $productType->price,
            // $productType->discount_fixed == 0 ? '0' : $productType->discount_fixed,
            // $productType->discount_percentage == 0 ? '0' : $productType->discount_percentage,
            // $productType->weight == 0 ? '0' : $productType->weight,
            $productType->stock == 0 ? '0' : $productType->stock,
            $productType->total_sales == 0 ? '0' : $productType->total_sales,
            // $productType->product->description ?? null,
            // $productType->product->long_description ?? null,
            // $productType->description ?? null,
            $productType->product->put_on_highlight !== null ? ($productType->product->put_on_highlight == 1 ? 'yes' : 'no') : null,
            $productType->product->views == 0 ? '0' : $productType->product->views,
            $productType->created_at ?? null,
            $productType->admin_create->name ?? null,
            // $productType->updated_at ?? null,
            // $productType->admin_update->name ?? null,
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
        return ProductType::all();
    }
}
