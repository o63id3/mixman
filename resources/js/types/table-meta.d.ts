import { RowData } from '@tanstack/table-core'

declare module '@tanstack/table-core' {
  interface TableMeta<TData extends RowData> {
    class?: string
  }
}
