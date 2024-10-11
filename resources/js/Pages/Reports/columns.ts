import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { Report } from '@/types'
import { formatDate, formatMoney } from '@/lib/formatters'

export const columns: ColumnDef<Report>[] = [
  {
    id: 'network',
    accessorKey: 'network.name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الشبكة' }),
    enableSorting: false,
  },
  {
    accessorKey: 'total_payments_amount',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'مجموع الدفعات' }),
    cell: ({ row }) =>
      `${formatMoney(row.original.total_payments_amount)} شيكل`,
    enableSorting: false,
  },
  {
    accessorKey: 'total_expenses_amount',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'مجموع النفقات' }),
    cell: ({ row }) =>
      `${formatMoney(row.original.total_expenses_amount)} شيكل`,
    enableSorting: false,
  },
  {
    accessorKey: 'network_income',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'صافي الربح' }),
    cell: ({ row }) => `${formatMoney(row.original.network_income)} شيكل`,
    enableSorting: false,
  },
  {
    accessorKey: 'start_from_date',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'من تاريخ',
      }),
    cell: ({ row }) => formatDate(row.original.start_from_date),
  },
  {
    accessorKey: 'end_at_date',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'حتى تاريخ',
      }),
    cell: ({ row }) => formatDate(row.original.end_at_date),
  },
  {
    accessorKey: 'created_at_date',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'تم الإعداد بتاريخ',
      }),
    cell: ({ row }) => formatDate(row.original.created_at_date),
  },
]
