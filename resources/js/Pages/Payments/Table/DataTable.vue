<script setup lang="ts" generic="TData, TValue">
import type { ColumnDef, ColumnFiltersState } from '@tanstack/vue-table'
import {
  FlexRender,
  getCoreRowModel,
  getPaginationRowModel,
  useVueTable,
} from '@tanstack/vue-table'

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

const statuses = {
  all: 'الكل',
  P: 'جاري',
  C: 'مكتمل',
  X: 'لاغي',
}

const props = defineProps<{
  columns: ColumnDef<TData, TValue>[]
  data: TData[]
  meta: Meta
}>()

const table = useVueTable({
  get data() {
    return props.data
  },
  get columns() {
    return props.columns
  },
  getPaginationRowModel: getPaginationRowModel(),
  getCoreRowModel: getCoreRowModel(),
})
</script>

<template>
  <div class="rounded-md border">
    <Table>
      <TableHeader>
        <TableRow
          v-for="headerGroup in table.getHeaderGroups()"
          :key="headerGroup.id"
        >
          <TableHead
            v-for="header in headerGroup.headers"
            class="text-right"
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
              :href="route('orders.index', { page: item.value })"
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
