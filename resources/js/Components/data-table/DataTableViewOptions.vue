<script setup lang="ts" generic="TData">
import type { Table } from '@tanstack/vue-table'
import { computed, onMounted, watch } from 'vue'
import { MixerHorizontalIcon } from '@radix-icons/vue'

import { Button } from '@/Components/ui/button'
import {
  DropdownMenu,
  DropdownMenuCheckboxItem,
  DropdownMenuContent,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'

interface DataTableViewOptionsProps {
  table: Table<TData>
}

const props = defineProps<DataTableViewOptionsProps>()
const { t } = useI18n()

const columns = computed(() =>
  props.table
    .getAllColumns()
    .filter(
      (column) =>
        typeof column.accessorFn !== 'undefined' && column.getCanHide(),
    ),
)

const saveColumnVisibility = () => {
  const visibilityState = props.table.getAllColumns().reduce(
    (acc, column) => {
      acc[column.id] = column.getIsVisible()
      return acc
    },
    {} as Record<string, boolean>,
  )

  localStorage.setItem(
    `${usePage().url}_tableColumnVisibility`,
    JSON.stringify(visibilityState),
  )
}

const loadColumnVisibility = () => {
  const savedVisibility = localStorage.getItem(
    `${usePage().url}_tableColumnVisibility`,
  )
  if (savedVisibility) {
    const parsedVisibility = JSON.parse(savedVisibility) as Record<
      string,
      boolean
    >
    props.table.getAllColumns().forEach((column) => {
      const isVisible = parsedVisibility[column.id]
      if (typeof isVisible === 'boolean') {
        column.toggleVisibility(isVisible)
      }
    })
  }
}

const toggleColumnVisibility = (columnId: string, isVisible: boolean) => {
  const column = props.table.getColumn(columnId)
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
  return t(`columns.${columnId}`, columnId)
}
</script>

<template>
  <DropdownMenu v-if="columns.length">
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
        v-for="column in columns"
        :key="column.id"
        :checked="column.getIsVisible()"
        @update:checked="(value) => toggleColumnVisibility(column.id, value)"
      >
        <span class="rtl:truncate-rtl ltr:truncate">{{
          getColumnName(column.id)
        }}</span>
      </DropdownMenuCheckboxItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
