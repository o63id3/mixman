import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { User } from '@/types'
import { Link } from '@inertiajs/vue3'

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
        'div',
        {
          class: 'flex gap-1 items-baseline',
        },
        [
          h('span', row.original.name),
          h(
            Link,
            {
              method: 'post',
              as: 'button',
              href: `/networks/1/managers/${row.original.id}`, // TODO: Fix the binding
              class:
                'hover:underline text-xs tracking-wide text-muted-foreground',
            },
            {
              default: () => 'تعيين كمدير مالي',
            },
          ),
        ],
      ),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: 'share',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الحصة' }),
    cell: ({ row }) => `%${Math.round(row.original.share * 100)}`,
    enableSorting: false,
    enableHiding: false,
  },
  //   {
  //     accessorKey: 'telegram',
  //     header: ({ column }) =>
  //       h(DataTableColumnHeader, { column, title: 'حساب التلغرام' }),
  //     enableSorting: false,
  //   },
]
