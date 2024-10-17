<script setup lang="ts">
import { ref } from 'vue'

import { Button } from '@/Components/ui/button'
import { X } from 'lucide-vue-next'

import { Card, Order } from '@/types'
import { columns, summaryFields } from './columns'
import AddItemsForm from './AddItemsForm.vue'
import {
  DataTable,
  DataTableTable,
  DataTableSummary,
} from '@/Components/data-table'

defineProps<{
  order: Order
  cards?: Array<Card>
  canAddItem: boolean
}>()

const adding = ref(false)
</script>

<template>
  <div>
    <div class="flex items-center justify-between px-4">
      <p class="text-sm font-medium tracking-wide"># الكروت</p>
      <Button
        class="text-xs tracking-wide"
        size="xs"
        :variant="adding ? 'outline' : 'default'"
        @click="adding = !adding"
        v-if="canAddItem"
      >
        <X v-if="adding" class="w-3 text-red-500" />
        <span v-else>إضافة رزم</span>
      </Button>
    </div>
    <div class="mt-4">
      <AddItemsForm
        v-if="adding && canAddItem && cards"
        :cards="cards"
        :order="order"
        @success="adding = false"
      />
      <DataTable
        v-else
        :columns="columns(order, canAddItem)"
        :data="order.cards"
        table-id="order.cards"
      >
        <DataTableTable>
          <DataTableSummary :summaryFields="summaryFields" />
        </DataTableTable>
      </DataTable>
    </div>
  </div>
</template>
