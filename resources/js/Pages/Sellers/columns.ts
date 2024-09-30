import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import RegionsRowActions from './Partials/SellersRowActions.vue'
import { User } from '@/types'
import { formatMoney } from '@/lib/money'
import { Link } from '@inertiajs/vue3'
import { active } from '@/types/enums'

export const columns: ColumnDef<User>[] = [
  //   {
  //     accessorKey: 'id',
  //     header: ({ column }) => h(DataTableColumnHeader, { column, title: '#' }),
  //     enableSorting: false,
  //     enableHiding: false,
  //   },
  {
    accessorKey: 'name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الاسم' }),
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `${route('sellers.edit', row.original.id)}`,
          class: 'hover:underline',
        },
        {
          default: () => row.getValue('name'),
        },
      ),
  },
  {
    accessorKey: 'region',
    accessorFn: (seller) => seller.region?.name,
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'المنطقة',
      }),
    enableSorting: false,
  },
  {
    accessorKey: 'seller_percentage',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'نسبة الربح',
      }),
    cell: ({ row }) => `%${Math.round(row.original.percentage * 100)}`,
  },
  {
    accessorKey: 'balance',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'صافي الحساب',
      }),
    cell: ({ row }) => `${formatMoney(row.getValue('balance'))} شيكل`,
  },
  {
    accessorKey: 'active',
    header: ({ column }) =>
      h(DataTableColumnHeader, {
        column,
        title: 'الحالة',
      }),
    cell: ({ row }) => {
      const value = active.find((a: any) => a.value === row.getValue('active'))

      if (!value) return null

      return h('div', { class: 'flex items-center' }, [
        value.icon &&
          h(value.icon, {
            class: 'mr-2 rtl:ml-2',
          }),
        h('span', { class: ' text-muted-foreground' }, value.label),
      ])
    },
  },
  //   {
  //     id: 'actions',
  //     cell: ({ row }) => h(RegionsRowActions, { row }),
  //   },
]
