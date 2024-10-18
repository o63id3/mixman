<script setup lang="ts" generic="TData">
import { computed, inject, onMounted, watch } from 'vue'
import { useI18n } from 'vue-i18n'

import { Button } from '@/Components/ui/button'
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { MixerHorizontalIcon } from '@radix-icons/vue'

const table = inject('table') as import('@tanstack/table-core').Table<TData>
const tableId = inject('tableId') as string

const { t } = useI18n()

const columns = computed(() =>
  table
    .getAllColumns()
    .filter(
      (column) =>
        typeof column.accessorFn !== 'undefined' && column.getCanHide(),
    ),
)

const saveColumnVisibility = () => {
  const visibilityState = table.getAllColumns().reduce(
    (acc, column) => {
      acc[column.id] = column.getIsVisible()
      return acc
    },
    {} as Record<string, boolean>,
  )

  localStorage.setItem(
    `${tableId}_tableColumnVisibility`,
    JSON.stringify(visibilityState),
  )
}

const loadColumnVisibility = () => {
  const savedVisibility = localStorage.getItem(
    `${tableId}_tableColumnVisibility`,
  )
  if (savedVisibility) {
    const parsedVisibility = JSON.parse(savedVisibility) as Record<
      string,
      boolean
    >
    table.getAllColumns().forEach((column) => {
      const isVisible = parsedVisibility[column.id]
      if (typeof isVisible === 'boolean') {
        column.toggleVisibility(isVisible)
      }
    })
  }
}

const toggleColumnVisibility = (columnId: string, isVisible: boolean) => {
  const column = table.getColumn(columnId)
  if (column) {
    column.toggleVisibility(isVisible)
    saveColumnVisibility()
  }
}

onMounted(() => {
  loadColumnVisibility()
})

watch(
  () => columns.value.map((column) => column.getIsVisible()),
  () => saveColumnVisibility(),
  { deep: true },
)

const getColumnName = (columnId: string) => {
  return t(`tables.${tableId}.columns.${columnId}`, columnId)
}
</script>

<template>
  <DropdownMenu v-if="table.getAllColumns().length">
    <DropdownMenuTrigger as-child>
      <Button
        variant="outline"
        size="sm"
        class="flex h-8 ltr:ml-auto rtl:mr-auto"
      >
        الحقول
        <MixerHorizontalIcon class="h-4 w-4 ltr:ml-2 rtl:mr-2" />
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end" class="w-[150px]">
      <DropdownMenuLabel>اظهار الحقول</DropdownMenuLabel>
      <DropdownMenuSeparator />

      <DropdownMenuCheckboxItem
        v-for="column in table.getAllColumns()"
        :key="column.id"
        :checked="column.getIsVisible()"
        @update:checked="(value) => toggleColumnVisibility(column.id, value)"
      >
        <span dir="rtl" class="rtl:truncate-rtl ltr:truncate">
          {{ getColumnName(column.id) }}
        </span>
      </DropdownMenuCheckboxItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
