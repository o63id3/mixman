import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import TransactionsRowActions from './Partials/TransactionsRowActions.vue'
import { Transaction, User } from '@/types'
import { orderStatues, transactionTypes } from '@/types/enums'

export const columns: ColumnDef<Transaction>[] = [
  {
    accessorKey: 'id',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: '#', class: 'text-right' }),
    cell: ({ row }) =>
      h('div', { class: 'text-nowrap text-right' }, row.getValue('id')),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: 'seller',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الاسم', class: 'text-right' }),
    cell: ({ row }) =>
      h(
        'div',
        { class: 'text-nowrap text-right' },
        row.getValue<User>('seller').name,
      ),
  },
  {
    accessorKey: 'type',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'نوع الحركة',
        class: 'text-right',
      }),
    cell: ({ row }) => {
      const type = transactionTypes.find(
        (type: any) => type.value === row.getValue('type'),
      )

      if (!type) return null

      return h('div', { class: 'flex items-center text-nowrap text-right' }, [
        type.icon &&
          h(type.icon, {
            class: 'mr-2 rtl:ml-2',
          }),
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
        class: 'text-right',
      }),
    cell: ({ row }) => {
      const status = orderStatues.find(
        (status: any) => status.value === row.getValue('status'),
      )

      if (!status) return null

      return h('div', { class: 'flex items-center text-nowrap text-right' }, [
        status.icon &&
          h(status.icon, {
            class: 'mr-2 rtl:ml-2',
          }),
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
        class: 'text-right text-nowrap',
      }),
    cell: ({ row }) => {
      const amount = Number.parseFloat(row.getValue('amount'))
      const formatted = new Intl.NumberFormat('en-US').format(amount)

      return h('div', { class: 'text-right text-nowrap' }, `${formatted} شيكل`)
    },
  },
  {
    accessorKey: 'created_at',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'التاريخ',
        class: 'text-right',
      }),
    cell: ({ row }) =>
      h('div', { class: 'text-right text-nowrap' }, row.getValue('created_at')),
  },
  {
    id: 'actions',
    cell: ({ row }) => h(TransactionsRowActions, { row }),
  },
]
