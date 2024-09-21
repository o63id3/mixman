import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { OrderItem } from '@/types'
import { formatMoney } from '@/lib/money'
import OrderItemsRowActions from './Partials/OrderItemsRowActions.vue'

export const columns: ColumnDef<OrderItem>[] = [
  {
    accessorKey: 'id',
    header: ({ column }) => h(DataTableColumnHeader, { column, title: '#' }),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: 'card.name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الفئة' }),
  },
  {
    accessorKey: 'number_of_packages',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'الرزم',
      }),
  },
  {
    accessorKey: 'number_of_cards_per_package',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'الكروت في الرزمة الواحدة',
      }),
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
  },
  {
    id: 'actions',
    cell: ({ row }) => h(OrderItemsRowActions, { row }),
  },
]

export const summaryFields = [
  { key: 'id', label: '' },
  { key: 'id', label: '' },
  { key: 'id', label: '' },
  { key: 'id', label: '' },
  {
    key: 'total_price_for_seller',
    label: 'اجمالي السعر للبائع',
    formatter: (value: number) => `${formatMoney(value)} شيكل`,
  },
]
