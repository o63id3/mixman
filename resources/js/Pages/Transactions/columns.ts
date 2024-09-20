import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import TransactionsRowActions from './Partials/TransactionsRowActions.vue'
import { Transaction, User } from '@/types'
import { orderStatues, transactionTypes } from '@/types/enums'
import { formatMoney } from '@/lib/money'
import { Link } from '@inertiajs/vue3'

export const columns: ColumnDef<Transaction>[] = [
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
          href: `${route(`${row.getValue('type')}s.edit`, row.getValue('id'))}`,
          class: 'hover:underline',
        },
        row.getValue<User>('seller').name,
      ),
  },
  {
    accessorKey: 'type',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'نوع الحركة',
      }),
    cell: ({ row }) => {
      const type = transactionTypes.find(
        (type: any) => type.value === row.getValue('type'),
      )

      if (!type) return null

      return h('div', { class: 'flex items-center gap-2' }, [
        type.icon && h(type.icon),
        h('span', { class: ' text-muted-foreground' }, type.label),
      ])
    },
  },
  {
    accessorKey: 'status',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'الحالة',
      }),
    cell: ({ row }) => {
      const status = orderStatues.find(
        (status: any) => status.value === row.getValue('status'),
      )

      if (!status) return null

      return h('div', { class: 'flex items-center gap-2' }, [
        status.icon && h(status.icon),
        h('span', { class: ' text-muted-foreground' }, status.label),
      ])
    },
  },
  {
    accessorKey: 'amount',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'المبلغ',
      }),
    cell: ({ row }) => `${formatMoney(row.getValue('amount'))} شيكل`,
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
    cell: ({ row }) => h(TransactionsRowActions, { row }),
  },
]
