<script setup lang="ts" generic="TData, TValue">
import type { Column } from '@tanstack/vue-table'
import DateRangePicker from '../DateRangePicker.vue'
import { Ref, ref, watch } from 'vue'
import { DateRange } from 'radix-vue'
import { CalendarDate, DateValue } from '@internationalized/date'

interface DataTableDateRangeFilter {
  column?: Column<TData, TValue>
}

const props = defineProps<DataTableDateRangeFilter>()

const dateRange = ref({
  start: undefined,
  end: undefined,
}) as Ref<DateRange>

const setRange = (range: DateRange) => {
  let dates = [
    range.start && formatDate(range.start),
    range.end && formatDate(range.end),
  ]
    .filter(Boolean)
    .join(',')

  console.log(dates)

  props.column?.setFilterValue(dates)
}

const setDateRange = () => {
  const dates = props.column?.getFilterValue() as string

  const [start, end] = dates ? dates.split(',') : []
  dateRange.value.start = start ? parseDateString(start) : undefined
  dateRange.value.end = end ? parseDateString(end) : undefined
}

watch(dateRange, setRange, { deep: true })
watch(() => props.column?.getFilterValue(), setDateRange, { immediate: true })

function parseDateString(dateStr: string): CalendarDate | undefined {
  const [year, month, day] = dateStr.split('-').map(Number)
  return new CalendarDate(year, month, day)
}

function formatDate(date: DateValue): string {
  return `${date.year}-${date.month.toString().padStart(2, '0')}-${date.day.toString().padStart(2, '0')}`
}
</script>

<template>
  <DateRangePicker v-model="dateRange" />
</template>
