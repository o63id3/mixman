<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'
import { Badge } from '@/Components/ui/badge'

import { columns, summaryFields } from './partners'
import {
  DataTable,
  DataTableSummary,
  DataTableTable,
} from '@/Components/data-table'

import { Network } from '@/types'

defineProps<{
  network: Network
}>()
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <Breadcrumb>
        <BreadcrumbList>
          <BreadcrumbItem>
            <BreadcrumbLink :href="route('dashboard')">
              الرئيسة
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbLink :href="route('networks.index')">
              الشبكات
            </BreadcrumbLink>
          </BreadcrumbItem>
          <BreadcrumbSeparator />
          <BreadcrumbItem>
            <BreadcrumbPage>
              {{ network.name }}
              <span
                v-if="network.manager"
                class="text-xs font-normal tracking-wide"
              >
                ({{ network.manager.name }})
              </span>
              <Badge
                variant="outline"
                class="mr-auto sm:ml-0"
                :class="[network.active ? 'text-green-500' : 'text-red-500']"
              >
                {{ network.active ? 'فعالة' : 'معطلة' }}
              </Badge>
            </BreadcrumbPage>
          </BreadcrumbItem>
        </BreadcrumbList>
      </Breadcrumb>
    </template>

    <p class="px-4 text-sm font-medium tracking-wide"># الشركاء</p>
    <DataTable
      v-if="network.partners"
      class="mt-4"
      :columns="columns(network)"
      :data="network.partners"
      table-id="network.partners"
    >
      <DataTableTable>
        <DataTableSummary
          :summaryFields="network.partners.length ? summaryFields : undefined"
        />
      </DataTableTable>
    </DataTable>
  </AuthenticatedLayout>
</template>
