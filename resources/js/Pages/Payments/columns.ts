import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import PaymentsRowActions from './Partials/PaymentsRowActions.vue'
import { Payment, User } from '@/types'
import { Link } from '@inertiajs/vue3'

export const columns: ColumnDef<Payment>[] = [
  {
    accessorKey: 'id',
    header: ({ column }) => h(DataTableColumnHeader, { column, title: '#' }),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: 'seller',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الاسم' }),
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `${route('payments.edit', row.getValue('id'))}`,
          class: 'hover:underline',
        },
        {
          default: () => row.getValue<User>('seller').name,
        },
      ),
    enableSorting: false,
  },
  {
    accessorKey: 'registerer',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'تم الاستلام بواسطة',
      }),
    cell: ({ row }) => row.getValue<User>('registerer')?.name,
    enableSorting: false,
    enableHiding: false,
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
  {
    id: 'actions',
    cell: ({ row }) => h(PaymentsRowActions, { row }),
  },
]
