<script setup lang="ts">
import { Order } from '@/types'
import AddFilesForm from './AddFilesForm.vue'
import { columns } from './filesColumns'
import { ref } from 'vue'
import { Button } from '@/Components/ui/button'
import { X } from 'lucide-vue-next'
import { DataTable, DataTableTable } from '@/Components/data-table'

defineProps<{
  order: Order
}>()

const adding = ref<boolean>(false)
</script>

<template>
  <div>
    <div class="flex items-center justify-between px-4">
      <p class="text-sm font-medium tracking-wide"># المرفقات</p>
      <Button
        class="text-xs tracking-wide"
        size="xs"
        :variant="adding ? 'outline' : 'default'"
        @click="adding = !adding"
      >
        <X v-if="adding" class="w-3 text-red-500" />
        <span v-else>إضافة مرفقات</span>
      </Button>
    </div>

    <div class="mt-4">
      <AddFilesForm v-if="adding" :order="order" @success="adding = false" />
      <DataTable
        v-else
        :columns="columns"
        :data="order.files"
        table-id="order.files"
      >
        <DataTableTable />
      </DataTable>
    </div>
  </div>
</template>
