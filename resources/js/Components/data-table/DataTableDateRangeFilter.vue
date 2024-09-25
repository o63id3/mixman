<script setup lang="ts" generic="TData, TValue">
import type { Column } from '@tanstack/vue-table'
import DateRangePicker from '../DateRangePicker.vue'
import { computed } from 'vue'
import { DateRange } from 'radix-vue'
import { CalendarDate, DateValue } from '@internationalized/date'

interface DataTableDateRangeFilter {
  column?: Column<TData, TValue>
}

const props = defineProps<DataTableDateRangeFilter>()

function parseDateString(dateStr: string): CalendarDate | undefined {
  const [year, month, day] = dateStr.split('-').map(Number)
  return new CalendarDate(year, month, day)
}

function parseStringToRange(dateStr: string): DateRange {
  const [start, end] = dateStr ? dateStr.split(',') : []
  return {
    start: start ? parseDateString(start) : undefined,
    end: end ? parseDateString(end) : undefined,
  }
}

function formatDate(date: DateValue): string {
  return `${date.year}-${date.month.toString().padStart(2, '0')}-${date.day.toString().padStart(2, '0')}`
}

function formatRange(date: DateRange): string {
  return [
    date.start && formatDate(date.start),
    date.end && formatDate(date.end),
  ]
    .filter(Boolean)
    .join(',')
}

const dateRange = computed({
  get: () => parseStringToRange(props.column?.getFilterValue() as string),
  set: (value: DateRange) => {
    props.column?.setFilterValue(formatRange(value))
  },
})
</script>

<template>
  <DateRangePicker v-model="dateRange" />
</template>
