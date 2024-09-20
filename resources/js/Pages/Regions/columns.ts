import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import RegionsRowActions from './Partials/RegionsRowActions.vue'
import { Region } from '@/types'
import { Link } from '@inertiajs/vue3'

export const columns: ColumnDef<Region>[] = [
  {
    accessorKey: 'id',
    header: ({ column }) => h(DataTableColumnHeader, { column, title: '#' }),
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: 'name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الاسم' }),
    cell: ({ row }) =>
      h(
        Link,
        {
          href: `${route('regions.edit', row.getValue('id'))}`,
          class: 'hover:underline',
        },
        row.getValue('name'),
      ),
  },
  {
    id: 'actions',
    cell: ({ row }) => h(RegionsRowActions, { row }),
  },
]
