import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import RegionsRowActions from './Partials/RegionsRowActions.vue'
import { Region } from '@/types'

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
  },
  {
    id: 'actions',
    cell: ({ row }) => h(RegionsRowActions, { row }),
  },
]
