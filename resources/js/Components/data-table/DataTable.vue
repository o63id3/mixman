<script setup lang="ts" generic="TData, TValue">
import type {
  ColumnDef,
  ColumnFilter,
  ColumnFiltersState,
  ColumnSort,
  SortingState,
  VisibilityState,
} from '@tanstack/vue-table'
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table'

import { computed, ref, watch } from 'vue'
import { valueUpdater } from '@/lib/utils'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'
import DataTableSummary from './DataTableSummary.vue'
import type { SummaryField } from './DataTableSummary.vue'
import { Filters } from '@/types'
import { router, usePage } from '@inertiajs/vue3'

interface DataTableProps {
  columns: ColumnDef<TData, TValue>[]
  data: TData[]
  summaryFields?: SummaryField[]
  initialFilters?: Filters
  initialSorts?: string
}

const props = defineProps<DataTableProps>()

const columnVisibility = ref<VisibilityState>({})
const rowSelection = ref({})

const { columnFilters, sorting } = useFilteringAndSorting(
  props.initialFilters,
  props.initialSorts,
)

const table = useVueTable({
  get data() {
    return props.data
  },
  get columns() {
    return props.columns
  },
  state: {
    get sorting() {
      return sorting.value
    },
    get columnFilters() {
      return columnFilters.value
    },
    get columnVisibility() {
      return columnVisibility.value
    },
    get rowSelection() {
      return rowSelection.value
    },
  },
  onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
  onColumnFiltersChange: (updaterOrValue) =>
    valueUpdater(updaterOrValue, columnFilters),
  onColumnVisibilityChange: (updaterOrValue) =>
    valueUpdater(updaterOrValue, columnVisibility),
  onRowSelectionChange: (updaterOrValue) =>
    valueUpdater(updaterOrValue, rowSelection),
  getCoreRowModel: getCoreRowModel(),
})

function useFilteringAndSorting(
  initialFilters?: Filters,
  initialSorts?: string,
) {
  const columnFilters = ref<ColumnFiltersState>([])
  const sorting = ref<SortingState>([])

  if (initialFilters) {
    columnFilters.value = Object.entries(initialFilters).map(
      ([key, value]) => ({
        id: key,
        value: value,
      }),
    )
  }

  if (initialSorts) {
    sorting.value = initialSorts.split(',').map((sort) => ({
      desc: sort.charAt(0) === '-',
      id: sort.charAt(0) === '-' ? sort.substring(1) : sort,
    }))
  }

  const computedFilters = computed<Filters>(() => {
    const filters: Filters = {}
    columnFilters.value.forEach((filter: ColumnFilter) => {
      filters[filter.id] = filter.value
    })
    return filters
  })

  const computedSorts = computed<string | undefined>(() => {
    const sorts = sorting.value
      .map((sort: ColumnSort) => `${sort.desc ? '-' : ''}${sort.id}`)
      .join(',')
    return sorts.length ? sorts : undefined
  })

  watch([computedFilters, computedSorts], ([filters, sorts]) => {
    router.get(
      usePage().url.split('?')[0],
      { filter: filters, sort: sorts },
      { preserveState: true, preserveScroll: true },
    )
  })

  return {
    columnFilters,
    sorting,
  }
}
</script>

<template>
  <div class="mb-4 overflow-x-auto px-4 lg:px-0">
    <slot :table="table" name="toolBar" />
  </div>
  <div
    class="scrollbar-hide overflow-x-auto border bg-white shadow-sm lg:rounded-md"
  >
    <Table>
      <TableHeader class="bg-gray-100">
        <TableRow
          v-for="headerGroup in table.getHeaderGroups()"
          :key="headerGroup.id"
          class="whitespace-nowrap text-nowrap text-right"
        >
          <TableHead
            v-for="header in headerGroup.headers"
            :key="header.id"
            class="whitespace-nowrap text-nowrap text-right"
          >
            <FlexRender
              v-if="!header.isPlaceholder"
              :render="header.column.columnDef.header"
              :props="header.getContext()"
            />
          </TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <template v-if="table.getRowModel().rows?.length">
          <TableRow
            v-for="row in table.getRowModel().rows"
            :key="row.id"
            :data-state="row.getIsSelected() && 'selected'"
            class="whitespace-nowrap text-nowrap text-right"
          >
            <TableCell
              v-for="cell in row.getVisibleCells()"
              :key="cell.id"
              class="whitespace-nowrap text-nowrap text-right"
            >
              <FlexRender
                :render="cell.column.columnDef.cell"
                :props="cell.getContext()"
              />
            </TableCell>
          </TableRow>
        </template>

        <TableRow v-else>
          <TableCell :colspan="columns.length" class="h-24 text-center">
            لا يوجد نتائج.
          </TableCell>
        </TableRow>

        <DataTableSummary
          v-if="props.summaryFields"
          :data="props.data"
          :summaryFields="props.summaryFields"
          :columnsCount="props.columns.length"
        />
      </TableBody>
    </Table>
  </div>
</template>
