import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { OrderCard } from '@/types'
import OrderCardsRowActions from './OrderCardsRowActions.vue'
import { formatMoney } from '@/lib/formatters'

export function columns(canDelete: boolean): ColumnDef<OrderCard>[] {
  const columns: ColumnDef<OrderCard>[] = [
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
      cell: ({ row }) => h(OrderCardsRowActions, { row }),
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
