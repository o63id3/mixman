<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { columns, summaryFields } from './partners'
import { DataTable } from '@/Components/data-table'

import { Network } from '@/types'

defineProps<{
  network: Network
}>()
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          {{ network.name }}
          <span
            v-if="network.manager"
            class="text-xs font-normal tracking-wide"
          >
            ({{ network.manager.name }})
          </span>
        </h2>
      </div>
    </template>

    <div>
      <p class="px-4 text-sm font-medium tracking-wide"># الشركاء</p>
      <DataTable
        v-if="network.partners"
        :data="network.partners"
        :columns="columns(network)"
        :summaryFields="network.partners.length ? summaryFields : undefined"
      />
    </div>
  </AuthenticatedLayout>
</template>
