<script setup lang="ts" generic="TData, TValue">
import type { ColumnDef, ColumnFiltersState } from '@tanstack/vue-table'
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table'

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'
import { Input } from '@/Components/ui/input'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import {
  Pagination,
  PaginationList,
  PaginationListItem,
} from '@/Components/ui/pagination'
import { Button } from '@/Components/ui/button'
import { valueUpdater } from '@/lib/utils'
import { ref, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Meta, Order } from '@/types'

const props = defineProps<{
  columns: ColumnDef<TData, TValue>[]
  data: TData[]
  meta: Meta
  filters: Pick<Order, 'seller' | 'status'>
  statuses: Array<string>
}>()

const columnFilters = ref<ColumnFiltersState>([
  {
    id: 'seller',
    value: props.filters.seller,
  },
  {
    id: 'status',
    value: props.filters.status,
  },
])

let filters = columnFilters.value.reduce(
  (acc, filter) => {
    acc[filter.id] = filter.value as string
    return acc
  },
  {} as Record<string, string>,
)

const table = useVueTable({
  get data() {
    return props.data
  },
  get columns() {
    return props.columns
  },
  onColumnFiltersChange: (updaterOrValue) =>
    valueUpdater(updaterOrValue, columnFilters),
  getCoreRowModel: getCoreRowModel(),
  state: {
    get columnFilters() {
      return columnFilters.value
    },
  },
})

watch(columnFilters, () => {
  filters = columnFilters.value.reduce(
    (acc, filter) => {
      acc[filter.id] = filter.value as string
      return acc
    },
    {} as Record<string, string>,
  )

  router.get(route('orders.index'), filters, {
    preserveScroll: true,
    preserveState: true,
  })
})
</script>

<template>
  <div class="flex items-center justify-between p-4 md:px-0">
    <div v-if="$page.props.auth.user.admin" class="flex items-center">
      <Input
        class="max-w-sm"
        placeholder="ابحث باسم البائع"
        :model-value="table.getColumn('seller')?.getFilterValue() as string"
        @update:model-value="table.getColumn('seller')?.setFilterValue($event)"
      />
    </div>
    <div class="flex items-center">
      <Select
        :model-value="table.getColumn('status')?.getFilterValue() as string"
        @update:model-value="table.getColumn('status')?.setFilterValue($event)"
      >
        <SelectTrigger>
          <SelectValue placeholder="اختر حالة الطلب" />
        </SelectTrigger>
        <SelectContent>
          <SelectGroup>
            <SelectItem value="all"> الكل </SelectItem>
            <SelectItem
              v-for="status in statuses"
              :key="status"
              :value="status"
            >
              {{ status }}
            </SelectItem>
          </SelectGroup>
        </SelectContent>
      </Select>
    </div>
  </div>
  <div class="rounded-md border">
    <Table>
      <TableHeader>
        <TableRow
          v-for="headerGroup in table.getHeaderGroups()"
          :key="headerGroup.id"
        >
          <TableHead
            v-for="header in headerGroup.headers"
            class="w-full text-nowrap text-right"
            :class="header.column.columnDef.meta?.class"
            :key="header.id"
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
            class="w-full text-nowrap text-right"
            :data-state="row.getIsSelected() ? 'selected' : undefined"
          >
            <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
              <FlexRender
                :render="cell.column.columnDef.cell"
                :props="cell.getContext()"
              />
            </TableCell>
          </TableRow>
        </template>
        <template v-else>
          <TableRow>
            <TableCell :colspan="columns.length" class="h-24 text-center">
              لا يوجد نتائج
            </TableCell>
          </TableRow>
        </template>
      </TableBody>
    </Table>
  </div>
  <div class="mt-3 flex items-center justify-between px-4 md:px-0">
    <Pagination
      v-slot="{ page }"
      :total="meta.total"
      :sibling-count="1"
      show-edges
      :default-page="meta.current_page"
    >
      <PaginationList v-slot="{ items }" class="flex items-center gap-1">
        <template v-for="(item, index) in items">
          <PaginationListItem
            v-if="item.type === 'page'"
            :key="index"
            :value="item.value"
            as-child
          >
            <Link
              :href="route('orders.index', { page: item.value, ...filters })"
              preserve-scroll
            >
              <Button
                class="h-10 w-10 p-0"
                :variant="item.value === page ? 'default' : 'outline'"
              >
                {{ item.value }}
              </Button>
            </Link>
          </PaginationListItem>
        </template>
      </PaginationList>
    </Pagination>

    <div class="text-sm">عدد النتائج {{ meta.total }}</div>
  </div>
</template>
