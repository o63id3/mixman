<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

import { Data, Network } from '@/types'
import { columns, summaryFields } from './partners'
import DataTable from '@/Components/data-table/DataTable.vue'

defineProps<{
  network: Data<Network>
}>()
</script>

<template>
  <Head title="Networks" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          {{ network.data.name }}
          <span
            v-if="network.data.manager"
            class="text-xs font-normal tracking-wide"
          >
            ({{ network.data.manager.name }})
          </span>
        </h2>
      </div>
    </template>

    <div>
      <p class="px-4 text-sm font-medium tracking-wide"># الشركاء</p>
      <DataTable
        v-if="network.data.partners"
        :data="network.data.partners"
        :columns="columns(network)"
        :summaryFields="
          network.data.partners.length ? summaryFields : undefined
        "
      />
    </div>
  </AuthenticatedLayout>
</template>
