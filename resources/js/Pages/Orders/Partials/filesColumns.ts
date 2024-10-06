import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'

import DataTableColumnHeader from '@/Components/data-table/DataTableColumnHeader.vue'
import { OrderFile } from '@/types'
import OrderFilesRowActions from './OrderFilesRowActions.vue'
import { Link } from '@inertiajs/vue3'

function returnFileSize(number: number) {
  if (number < 1e3) {
    return `${number} bytes`
  } else if (number >= 1e3 && number < 1e6) {
    return `${(number / 1e3).toFixed(1)} كيلو بايت`
  } else {
    return `${(number / 1e6).toFixed(1)} ميغا بايت`
  }
}

export const columns: ColumnDef<OrderFile>[] = [
  {
    accessorKey: 'original_file_name',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'اسم الملف' }),
    cell: ({ row }) =>
      row.original.can.download
        ? h(
            Link,
            {
              href: route('order-files.download', row.original.id),
              class: 'hover:underline',
            },
            { default: () => row.original.original_file_name },
          )
        : row.original.original_file_name,
    enableSorting: false,
    enableHiding: false,
  },
  {
    accessorKey: 'size',
    header: ({ column }) =>
      h(DataTableColumnHeader, { column, title: 'الحجم' }),
    cell: ({ row }) => returnFileSize(row.original.size),
    enableSorting: false,
    enableHiding: false,
  },
  {
    id: 'actions',
    cell: ({ row }) => h(OrderFilesRowActions, { row }),
  },
]
