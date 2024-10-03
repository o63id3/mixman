import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { Data, Network, User } from '@/types'
import { Link } from '@inertiajs/vue3'
import NetworkPartnerActions from './Partials/NetworkPartnerActions.vue'

export function columns(
  network: Data<Network>,
  canDelete?: boolean,
  canAssignManager?: boolean,
): ColumnDef<User>[] {
  const columns: ColumnDef<User>[] = [
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
            network.data.manager?.id !== row.original.id && canAssignManager
              ? h(
                  Link,
                  {
                    method: 'post',
                    as: 'button',
                    href: `/networks/${network.data.id}/managers/${row.original.id}`,
                    class:
                      'hover:underline text-xs tracking-wide text-muted-foreground',
                  },
                  {
                    default: () => 'تعيين كمدير مالي',
                  },
                )
              : undefined,
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
  ]

  if (canDelete) {
    columns.push({
      id: 'actions',
      cell: ({ row }) => h(NetworkPartnerActions, { row, network }),
    })
  }

  return columns
}

export const summaryFields = [
  { key: 'id', label: '' },
  {
    key: 'share',
    label: 'total_share',
    formatter: (value: number) => `%${Math.round(value * 100)}`,
  },
]
