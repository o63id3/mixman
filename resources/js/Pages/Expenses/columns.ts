import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { Expense } from '@/types'
import { Link } from '@inertiajs/vue3'
import { formatDate } from '@/lib/formatters'

export const columns: ColumnDef<Expense>[] = [
  {
    accessorKey: 'description',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الوصف' }),
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `${route(`expenses.${row.original.can.update ? 'edit' : 'show'}`, row.original.id)}`,
          class: 'hover:underline',
        },
        {
          default: () => row.original.description,
        },
      ),
    enableSorting: false,
  },
  {
    accessorKey: 'user',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'تم الادخال بواسطة',
      }),
    cell: ({ row }) => row.original.user.name,
    enableSorting: false,
  },
  {
    accessorKey: 'network',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'الشبكة',
      }),
    cell: ({ row }) => row.original.network.name,
    enableSorting: false,
  },
  {
    accessorKey: 'amount',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'المبلغ',
      }),
    cell: ({ row }) => `${row.getValue('amount')} شيكل`,
  },
  {
    accessorKey: 'created_at',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'التاريخ',
      }),
    cell: ({ row }) => formatDate(row.original.created_at_date, false),
  },
]
