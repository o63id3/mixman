import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import { DataTableColumnHeader } from '@/Components/data-table'
import { Report } from '@/types'
import { formatDate, formatMoney } from '@/lib/formatters'

export const columns: ColumnDef<Report>[] = [
  {
    accessorKey: 'id',
    header: '#',
    cell: ({ row }) =>
      h(
        'a',
        {
          target: '_blank',
          href: route('reports.show', row.original.id),
          class: 'hover:underline',
        },
        {
          default: () => row.original.id,
        },
      ),
    enableSorting: false,
  },
  {
    id: 'network',
    accessorKey: 'network.name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الشبكة' }),
    cell: ({ row }) =>
      h(
        'a',
        {
          target: '_blank',
          href: route('reports.show', row.original.id),
          class: 'hover:underline',
        },
        {
          default: () => row.original.network.name,
        },
      ),
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
    accessorKey: 'income_overflow',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'ربح فائض' }),
    cell: ({ row }) => `${formatMoney(row.original.income_overflow)} شيكل`,
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
