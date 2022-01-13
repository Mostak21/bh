<?php

namespace App;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExportGoogle implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            'id',
            'title',
            'description',
            'link',
            'condition',
            'price',
            'availability',
            'image link',
            'gtin',
            'mpn',
            'brand',
            'google product category',
            'meta_description',
        ];
    }

    /**
    * @var Product $product
    */
    public function map($product): array
    {
        $qty = 0;
        foreach ($product->stocks as $key => $stock) {
            $qty += $stock->qty;
        }
		if(!empty($product->brand->name)){$product_brand_name=$product->brand->name;}
		else{$product_brand_name="NULL";}
        return [
            $product->id,
            $product->name,
            $product->meta_description,
            "https://brandhook.com.bd/product/".$product->slug,
			"New",
			"BDT ".$product->unit_price,
			"in_stock",
			uploaded_asset($product->photos),
			" ",
			" ",
            $product_brand_name,
            $product->category->name,
            $product->meta_description,
        ];
    }
}
