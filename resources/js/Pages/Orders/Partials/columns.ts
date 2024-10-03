import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { OrderItem } from '@/types'
import { formatMoney } from '@/lib/money'
import OrderItemsRowActions from './OrderItemsRowActions.vue'

export function columns(canDelete: boolean): ColumnDef<OrderItem>[] {
  const columns: ColumnDef<OrderItem>[] = [
    {
      accessorKey: 'card.name',
      header: ({ column }) =>
        h(DataTableColumnHeader, { column, title: 'الفئة' }),
      enableSorting: false,
      enableHiding: false,
    },
    {
      accessorKey: 'total_price_for_seller',
      header: ({ column }) =>
        h(DataTableColumnHeader, {
          column,
          title: 'اجمالي السعر للبائع',
        }),
      cell: ({ row }) =>
        `${formatMoney(row.getValue('total_price_for_seller'))} شيكل`,
      enableSorting: false,
      enableHiding: false,
    },
    {
      accessorKey: 'number_of_packages',
      header: ({ column }) =>
        h(DataTableColumnHeader, {
          column,
          title: 'الرزم',
        }),
      enableSorting: false,
      enableHiding: false,
    },
    {
      accessorKey: 'number_of_cards_per_package',
      header: ({ column }) =>
        h(DataTableColumnHeader, {
          column,
          title: 'الكروت في الرزمة الواحدة',
        }),
      enableSorting: false,
      enableHiding: false,
    },
  ]

  if (canDelete) {
    columns.push({
      id: 'actions',
      cell: ({ row }) => h(OrderItemsRowActions, { row }),
    })
  }

  return columns
}

export const summaryFields = [
  { key: 'id', label: '' },
  {
    key: 'total_price_for_seller',
    label: 'اجمالي السعر للبائع',
    formatter: (value: number) => `${formatMoney(value)} شيكل`,
  },
]
