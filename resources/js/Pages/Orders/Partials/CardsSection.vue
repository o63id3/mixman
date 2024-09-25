<script setup lang="ts">
import { ref } from 'vue'

import { Button } from '@/Components/ui/button'

import AddItemsForm from './AddItemsForm.vue'

import { X } from 'lucide-vue-next'

import { Card, Order, OrderItem } from '@/types'
import { columns, summaryFields } from './columns'
import DataTable from '@/Components/data-table/DataTable.vue'

defineProps<{
  order: Order
  items: Array<OrderItem>
  cards: Array<Card>
  canAddItem: boolean
}>()

const addingForm = ref(false)
</script>

<template>
  <div>
    <div class="flex items-center justify-between px-4">
      <p class="text-sm font-medium tracking-wide"># الكروت</p>
      <Button
        class="text-xs tracking-wide"
        size="xs"
        :variant="addingForm ? 'outline' : 'default'"
        @click="addingForm = !addingForm"
        v-if="canAddItem"
      >
        <X v-if="addingForm" class="w-3 text-red-500" />
        <span v-else>إضافة رزم</span>
      </Button>
    </div>
    <AddItemsForm
      v-if="addingForm && canAddItem"
      class="mt-4 overflow-hidden bg-white p-6 text-gray-900 shadow-sm sm:rounded-lg"
      :cards="cards"
      :order="order"
      @success="addingForm = false"
    />
    <DataTable
      v-else
      :data="items"
      :columns="columns"
      :summaryFields="items.length ? summaryFields : undefined"
    />
  </div>
</template>
