<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderExcelCreator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection,WithHeadings,WithColumnWidths
{

    public function collection()
    {
        return OrderExcelCreator::all();
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 15,
            'C' => 20,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 20,
            'I' => 100,
            'J' => 20,
            'K' => 20,
            'L' => 20,
            'M' => 20,
            'N' => 20,
            'O' => 20,
            'P' => 20,
            'Q' => 20,
            'R' => 20,
            'S' => 20,
        ];
    }

    public function headings(): array
    {
        return [
            "row_id",
            "order_id",
            "نام و نام خانوادگی مشتری",
            "کدملی مشتری",
            "موبایل مشتری",
            "تلفن مشتری",
            "شماره سفارش",
            "تاریخ سفارش",
            "سفارشات",
          	"رنگ ها",
            "قیمت واحد کالا(تومان)",
            "اقلام همراه",
            "قیمت اقلام همراه",
            "تعداد",
            "جمع هر مدل کالا(تومان)",
            "مبلغ سفارش(تومان)",
            "هزینه حمل",
            "آدرس مشتری",
        ];
    }
}
