import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import PaymentsRowActions from './Partials/PaymentsRowActions.vue'
import { Payment, User } from '@/types'

export const columns: ColumnDef<Payment>[] = [
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
    accessorKey: 'registerer',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'تم الاستلام بواسطة',
        class: 'text-right text-nowrap',
      }),
    cell: ({ row }) =>
      h(
        'div',
        { class: 'text-nowrap text-right' },
        row.getValue<User>('registerer')?.name,
      ),
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
    cell: ({ row }) => h(PaymentsRowActions, { row }),
  },
]
