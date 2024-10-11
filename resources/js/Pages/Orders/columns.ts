import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { Order } from '@/types'
import { orderStatues } from '@/types/enums'
import { Link } from '@inertiajs/vue3'
import { formatDate, formatMoney } from '@/lib/formatters'

export const columns: ColumnDef<Order>[] = [
  {
    accessorKey: 'user',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'المستفيد' }),
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `${route(`orders.${row.original.can.update ? 'edit' : 'show'}`, row.original.id)}`,
          class: 'hover:underline',
        },
        {
          default: () => row.original.user.name,
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
    accessorKey: 'network',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الشبكة' }),
    cell: ({ row }) => row.original.network.name,
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
    cell: ({ row }) => formatDate(row.original.updated_at_date, false),
    enableSorting: false,
  },
]
