import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import PaymentsRowActions from './Partials/PaymentsRowActions.vue'
import { Payment, User } from '@/types'
import { Link } from '@inertiajs/vue3'

export const columns: ColumnDef<Payment>[] = [
  //   {
  //     accessorKey: 'id',
  //     header: ({ column }) => h(DataTableColumnHeader, { column, title: '#' }),
  //     enableSorting: false,
  //     enableHiding: false,
  //   },
  {
    accessorKey: 'recipient',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الاسم' }),
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `${route('payments.edit', row.original.id)}`,
          class: 'hover:underline',
        },
        {
          default: () => row.original.recipient.name,
        },
      ),
    enableSorting: false,
  },
  {
    accessorKey: ' user',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'المستلم',
      }),
    cell: ({ row }) => row.original.user.name,
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
  },
  //   {
  //     id: 'actions',
  //     cell: ({ row }) => h(PaymentsRowActions, { row }),
  //   },
]
