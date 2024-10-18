<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import UpdateFormLayout from '@/Layouts/UpdateFormLayout.vue'

import { formSchema } from './definitions'
import NetworkForm from './Partials/NetworkForm.vue'

import { columns, summaryFields } from './partners'
import {
  DataTable,
  DataTableSummary,
  DataTableTable,
} from '@/Components/data-table'

import { Badge } from '@/Components/ui/badge'
import { toast } from '@/Components/ui/toast'
import { CreateLink, DeleteLink, SecondaryLink } from '@/Components/links'
import BreadcrumbsGenerator from '@/Components/BreadcrumbsGenerator.vue'

import { Network } from '@/types'

const props = defineProps<{
  network: Network
  can: {
    assignManager: boolean
    createPartner: boolean
    deletePartner: boolean
  }
}>()

const initialValues = {
  name: props.network.name,
  internet_price_per_week: props.network.internet_price_per_week,
}

const breadcrumbs = [
  {
    label: 'الرئيسة',
    route: route('dashboard'),
  },
  {
    label: 'الشبكات',
    route: route('networks.index'),
  },
  {
    label: props.network.name,
  },
]
</script>

<template>
  <AuthenticatedLayout>
    <template #secondaryHeader>
      <div class="flex items-center gap-2">
        <BreadcrumbsGenerator :breadcrumbs="breadcrumbs" />
        <Badge
          variant="outline"
          class="mr-auto sm:ml-0"
          :class="[network.active ? 'text-green-500' : 'text-red-500']"
        >
          {{ network.active ? 'فعالة' : 'معطلة' }}
        </Badge>
      </div>
    </template>

    <UpdateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('networks.update', network.id)"
    >
      <template #buttons>
        <DeleteLink
          v-if="network.active"
          :href="route('networks.deactivate', network.id)"
          @success="toast({ title: 'تم تعطيل الشبكة' })"
          method="POST"
        >
          تعطيل
        </DeleteLink>
        <SecondaryLink
          v-else
          method="DELETE"
          :href="route('networks.activate', network.id)"
          @success="toast({ title: 'تم تفعيل الشبكة' })"
        >
          تفعيل
        </SecondaryLink>
      </template>

      <NetworkForm :disabled="!true" />
    </UpdateFormLayout>

    <div class="mt-4 space-y-4">
      <div class="flex items-center justify-between px-4">
        <p class="text-sm font-medium tracking-wide"># الشركاء</p>
        <CreateLink
          v-if="can.createPartner"
          :href="route('network.partners.create', props.network.id)"
        >
          إضافة شريك
        </CreateLink>
      </div>
      <DataTable
        v-if="network.partners"
        :columns="columns(network, can.deletePartner, can.assignManager)"
        :data="network.partners"
        table-id="network.partners"
      >
        <DataTableTable>
          <DataTableSummary :summaryFields="summaryFields" />
        </DataTableTable>
      </DataTable>
    </div>
  </AuthenticatedLayout>
</template>
