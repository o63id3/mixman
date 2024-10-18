<script setup lang="ts" generic="TData">
import { FlexRender } from '@tanstack/vue-table'

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'
import { inject } from 'vue'

const table = inject('table') as import('@tanstack/table-core').Table<TData>
</script>

<template>
  <div
    class="overflow-x-auto border-y bg-white shadow-sm lg:rounded-md lg:border-x"
  >
    <Table>
      <TableHeader class="bg-gray-100">
        <TableRow
          v-for="headerGroup in table.getHeaderGroups()"
          :key="headerGroup.id"
        >
          <TableHead
            v-for="header in headerGroup.headers"
            :key="header.id"
            class="whitespace-nowrap text-nowrap"
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
          >
            <TableCell
              v-for="cell in row.getVisibleCells()"
              :key="cell.id"
              class="whitespace-nowrap text-nowrap"
            >
              <FlexRender
                :render="cell.column.columnDef.cell"
                :props="cell.getContext()"
              />
            </TableCell>
          </TableRow>
        </template>

        <TableRow v-else>
          <TableCell
            :colspan="table.getAllColumns().length"
            class="h-24 text-center"
          >
            لا يوجد نتائج.
          </TableCell>
        </TableRow>

        <slot />
      </TableBody>
    </Table>
  </div>
</template>
