import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import RegionsRowActions from './Partials/RegionsRowActions.vue'
import { Region } from '@/types'

export const columns: ColumnDef<Region>[] = [
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
    accessorKey: 'name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الاسم', class: 'text-right' }),
    cell: ({ row }) =>
      h('div', { class: 'text-nowrap text-right' }, row.getValue('name')),
  },
  {
    id: 'actions',
    cell: ({ row }) => h(RegionsRowActions, { row }),
  },
]
