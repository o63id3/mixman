import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { Network } from '@/types'
import { Link } from '@inertiajs/vue3'
import { active, roles } from '@/types/enums'

export const columns: ColumnDef<Network>[] = [
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
          href: `${route('networks.edit', row.original.id)}`,
          class: 'hover:underline',
        },
        {
          default: () => row.getValue('name'),
        },
      ),
    enableSorting: false,
  },
  {
    accessorKey: 'manager',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'المدير المالي' }),
    cell: ({ row }) => row.original.manager?.name,
    enableSorting: false,
  },
  {
    accessorKey: 'internet_price_per_week',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'سعر الانترنت أسبوعيا' }),
    enableSorting: false,
  },
  //   {
  //     accessorKey: 'telegram',
  //     header: ({ column }) =>
  //       h(DataTableColumnHeader, { column, title: 'حساب التلغرام' }),
  //     enableSorting: false,
  //   },
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
]
