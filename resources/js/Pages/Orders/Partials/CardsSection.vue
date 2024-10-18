<script setup lang="ts">
import { ref } from 'vue'

import {
  DataTable,
  DataTableTable,
  DataTableSummary,
} from '@/Components/data-table'
import {
  ToggleArea,
  ToggleAreaToggle,
  ToggleAreaSheet,
} from '@/Components/toggle-area'
import { ToggleButton } from '@/Components/buttons'

import AddItemsForm from './AddItemsForm.vue'
import { columns, summaryFields } from './columns'

import { Card, Order } from '@/types'

defineProps<{
  order: Order
  cards?: Array<Card>
  canAddItem: boolean
}>()

const adding = ref(false)
</script>

<template>
  <ToggleArea v-model="adding" class="space-y-4">
    <div class="flex items-center justify-between px-4">
      <p class="text-sm font-medium tracking-wide"># الكروت</p>
      <ToggleAreaToggle v-if="canAddItem && cards">
        <ToggleButton v-model="adding">إضافة رزم</ToggleButton>
      </ToggleAreaToggle>
    </div>

    <ToggleAreaSheet first>
      <DataTable
        :columns="columns(order, canAddItem)"
        :data="order.cards"
        table-id="order.cards"
      >
        <DataTableTable>
          <DataTableSummary :summaryFields="summaryFields" />
        </DataTableTable>
      </DataTable>
    </ToggleAreaSheet>

    <ToggleAreaSheet>
      <AddItemsForm
        v-if="canAddItem && cards"
        :cards="cards"
        :order="order"
        @success="adding = false"
      />
    </ToggleAreaSheet>
  </ToggleArea>
</template>
