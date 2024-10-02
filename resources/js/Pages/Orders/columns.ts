import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import OrdersRowActions from './Partials/OrdersRowActions.vue'
import { Order, User } from '@/types'
import { orderStatues } from '@/types/enums'
import { formatMoney } from '@/lib/money'
import { Link } from '@inertiajs/vue3'

export const columns: ColumnDef<Order>[] = [
  //   {
  //     accessorKey: 'id',
  //     header: ({ column }) => h(DataTableColumnHeader, { column, title: '#' }),
  //     enableSorting: false,
  //     enableHiding: false,
  //   },
  {
    accessorKey: 'orderer',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الاسم' }),
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `${route('orders.edit', row.original.id)}`,
          class: 'hover:underline',
        },
        {
          default: () => row.original.orderer.name,
        },
      ),
    enableSorting: false,
  },
  {
    accessorKey: 'manager',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'مدير الطلب' }),
    cell: ({ row }) => row.original.manager.name,
    enableSorting: false,
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
  //   {
  //     accessorKey: 'action',
  //     accessorFn: (order) => order.action?.name,
  //     header: ({ column }) =>
  //       h(DataTableColumnHeader, {
  //         column,
  //         title: 'تم اتخاذ الاجراء بواسطة',
  //       }),
  //     enableSorting: false,
  //   },
  {
    accessorKey: 'total_price_for_seller',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'اجمالي السعر',
      }),
    cell: ({ row }) =>
      row.original.total_price_for_seller
        ? `${formatMoney(row.getValue('total_price_for_seller'))} شيكل`
        : '-',
  },
  {
    accessorKey: 'total_price_for_consumer',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'اجمالي السعر للمستهلك',
      }),
    cell: ({ row }) =>
      row.original.total_price_for_consumer
        ? `${formatMoney(row.getValue('total_price_for_consumer'))} شيكل`
        : '-',
    enableSorting: false,
  },
  {
    accessorKey: 'updated_at',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'التاريخ',
      }),
    enableSorting: false,
  },
  //   {
  //     id: 'actions',
  //     cell: ({ row }) => h(OrdersRowActions, { row }),
  //   },
]
