<script setup lang="ts">
import { ref } from 'vue'

import { DataTable, DataTableTable } from '@/Components/data-table'
import {
  ToggleArea,
  ToggleAreaToggle,
  ToggleAreaSheet,
} from '@/Components/toggle-area'
import { ToggleButton } from '@/Components/buttons'

import AddFilesForm from './AddFilesForm.vue'
import { columns } from './filesColumns'

import { Order } from '@/types'

defineProps<{
  order: Order
}>()

const adding = ref<boolean>(false)
</script>

<template>
  <ToggleArea v-model="adding" class="space-y-4">
    <div class="flex items-center justify-between px-4">
      <p class="text-sm font-medium tracking-wide"># المرفقات</p>
      <ToggleAreaToggle>
        <ToggleButton v-model="adding">إضافة مرفقات</ToggleButton>
      </ToggleAreaToggle>
    </div>

    <ToggleAreaSheet first>
      <DataTable :columns="columns" :data="order.files" table-id="order.files">
        <DataTableTable />
      </DataTable>
    </ToggleAreaSheet>

    <ToggleAreaSheet>
      <AddFilesForm :order="order" @success="adding = false" />
    </ToggleAreaSheet>
  </ToggleArea>
</template>
