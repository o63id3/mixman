import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { Card } from '@/types'
import OrderCardsRowActions from './OrderCardsRowActions.vue'
import { formatMoney } from '@/lib/formatters'

export function columns(canDelete: boolean): ColumnDef<Card>[] {
  const columns: ColumnDef<Card>[] = [
    {
      accessorKey: 'name',
      header: ({ column }) =>
        h(DataTableColumnHeader, { column, title: 'الفئة' }),
      enableSorting: false,
      enableHiding: false,
    },
    {
      accessorKey: 'pivot.total_price_for_seller',
      header: ({ column }) =>
        h(DataTableColumnHeader, {
          column,
          title: 'اجمالي السعر للبائع',
        }),
      cell: ({ row }) =>
        `${formatMoney(row.original.pivot.total_price_for_seller)} شيكل`,
      enableSorting: false,
      enableHiding: false,
    },
    {
      accessorKey: 'pivot.number_of_packages',
      header: ({ column }) =>
        h(DataTableColumnHeader, {
          column,
          title: 'الرزم',
        }),
      enableSorting: false,
      enableHiding: false,
    },
    {
      accessorKey: 'pivot.number_of_cards_per_package',
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
      cell: ({ row }) => h(OrderCardsRowActions, { row }),
    })
  }

  return columns
}

export const summaryFields = [
  {
    key: 'pivot.total_price_for_seller',
    formatter: (value: number) => `${formatMoney(value)} شيكل`,
  },
]
