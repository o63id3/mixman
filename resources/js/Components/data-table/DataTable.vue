<script setup lang="ts" generic="TData, TValue">
import type {
  ColumnDef,
  ColumnFilter,
  ColumnFiltersState,
  ColumnSort,
  SortingState,
  VisibilityState,
} from '@tanstack/vue-table'
import { getCoreRowModel, useVueTable } from '@tanstack/vue-table'

import { computed, provide, ref, watch } from 'vue'
import { valueUpdater } from '@/lib/utils'
import { Filters, Links, Meta, Paginator } from '@/types'
import { router, usePage } from '@inertiajs/vue3'
import DataTablePagination from './DataTablePagination.vue'

interface DataTableProps {
  columns: ColumnDef<TData, TValue>[]
  data: TData[] | Paginator<TData>
  tableId: string
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
    if (props.data instanceof Array) {
      return props.data
    }

    return props.data.data
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

provide('table', table)
provide('tableId', props.tableId)

const meta = computed<Meta | undefined>(() =>
  props.data instanceof Array ? undefined : props.data.meta,
)

const links = computed<Links | undefined>(() =>
  props.data instanceof Array ? undefined : props.data.links,
)

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
  <div class="space-y-4">
    <slot />
    <DataTablePagination
      v-if="meta && links"
      :meta="meta"
      :links="links"
      :filters="initialFilters"
      :sorts="initialSorts"
    />
  </div>
</template>
