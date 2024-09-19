import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import OrdersRowActions from './Partials/OrdersRowActions.vue'
import { Order, User } from '@/types'
import { orderStatues } from '@/types/enums'

export const columns: ColumnDef<Order>[] = [
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
    accessorKey: 'action',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'تم اتخاذ الاجراء بواسطة',
        class: 'text-right text-nowrap',
      }),
    cell: ({ row }) =>
      h(
        'div',
        { class: 'text-nowrap text-right' },
        row.getValue<User>('action')?.name,
      ),
  },
  {
    accessorKey: 'total_price_for_seller',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'اجمالي السعر للبائع',
        class: 'text-right text-nowrap',
      }),
    cell: ({ row }) => {
      const price = Number.parseFloat(row.getValue('total_price_for_seller'))
      const formatted = new Intl.NumberFormat('en-US').format(price)

      return h('div', { class: 'text-right text-nowrap' }, `${formatted} شيكل`)
    },
  },
  {
    accessorKey: 'total_price_for_consumer',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'اجمالي السعر للمستهلك',
        class: 'text-nowrap text-right',
      }),
    cell: ({ row }) => {
      const price = Number.parseFloat(row.getValue('total_price_for_consumer'))
      const formatted = new Intl.NumberFormat('en-US').format(price)

      return h('div', { class: 'text-right text-nowrap' }, `${formatted} شيكل`)
    },
  },
  {
    accessorKey: 'updated_at',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'التاريخ',
        class: 'text-right',
      }),
    cell: ({ row }) =>
      h('div', { class: 'text-right text-nowrap' }, row.getValue('updated_at')),
  },
  {
    id: 'actions',
    cell: ({ row }) => h(OrdersRowActions, { row }),
  },
]
