<script lang="ts">
export interface SummaryField {
  key: string
  label: string
  formatter?: (value: any) => string
}
</script>

<script setup lang="ts">
import { computed } from 'vue'
import { TableCell, TableRow } from '@/Components/ui/table'

interface SummaryField {
  key: string
  label: string
  formatter?: (value: any) => string
}

interface DataTableSummaryProps {
  data: any[]
  summaryFields: SummaryField[]
  columnsCount: number
}

const props = defineProps<DataTableSummaryProps>()

const summaries = computed(() => {
  return props.summaryFields.map((field) => ({
    ...field,
    value:
      field.key === 'id'
        ? field.label
        : props.data.reduce((sum, item) => sum + (item[field.key] || 0), 0), // FIX: nested objects
  }))
})
</script>

<template>
  <TableRow class="bg-gray-100 font-medium hover:bg-gray-100">
    <TableCell
      v-for="(summary, index) in summaries"
      :key="summary.key"
      :class="[
        'whitespace-nowrap text-nowrap text-right',
        index === 0 ? 'font-bold' : '',
      ]"
    >
      {{
        summary.key === 'id'
          ? summary.label
          : summary.formatter
            ? summary.formatter(summary.value)
            : summary.value
      }}
    </TableCell>
    <TableCell
      v-for="(_, index) in Array(props.columnsCount - summaries.length)"
      :key="`empty-${index}`"
      class="whitespace-nowrap text-nowrap"
    ></TableCell>
  </TableRow>
</template>
