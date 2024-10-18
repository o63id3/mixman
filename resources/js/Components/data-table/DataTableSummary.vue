<script lang="ts">
export interface SummaryField {
  key: string
  formatter?: (value: any) => string
}
</script>

<script setup lang="ts" generic="TData">
import { computed, inject } from 'vue'

import { TableCell, TableRow } from '@/Components/ui/table'
import { getNestedProperty } from '@/lib/utils'

import type { Table } from '@tanstack/table-core'

interface SummaryField {
  key: string
  formatter?: (value: any) => string
}

interface DataTableSummaryProps {
  summaryFields?: SummaryField[]
}

const props = defineProps<DataTableSummaryProps>()
const table = inject('table') as Table<TData>

const summaries = computed(() => {
  return props.summaryFields?.map((field) => {
    const value = table
      .getRowModel()
      .rows.reduce(
        (sum, item) =>
          sum + (Number(getNestedProperty(item, `original.${field.key}`)) || 0),
        0,
      )

    return {
      ...field,
      value: field.formatter ? field.formatter(value) : value,
    }
  })
})
</script>

<template>
  <TableRow
    class="whitespace-nowrap text-nowrap bg-gray-100 text-right font-medium hover:bg-gray-100"
  >
    <TableCell
      v-if="summaries"
      v-for="header in table.getHeaderGroups()[0].headers"
      :key="header.id"
    >
      {{
        summaries.find((summary) => summary.key.replace('.', '_') === header.id)
          ?.value
      }}
    </TableCell>
  </TableRow>
</template>
