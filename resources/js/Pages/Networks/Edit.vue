<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { Link } from '@inertiajs/vue3'

import { formSchema } from './definitions'
import NetworkForm from './Partials/NetworkForm.vue'

import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'

import { columns, summaryFields } from './partners'
import { DataTable } from '@/Components/data-table'

import { Button } from '@/Components/ui/button'
import { toast } from '@/Components/ui/toast'
import { DeleteLink, SecondaryLink } from '@/Components/links'

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

    <UpdateFormLayout
      :form-schema="formSchema"
      :initial-values="initialValues"
      :route="route('networks.update', network.id)"
      @success="toast({ title: 'تم تعديل الشبكة' })"
      can-update
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

    <div class="mt-4">
      <div class="flex items-center justify-between px-4">
        <p class="text-sm font-medium tracking-wide"># الشركاء</p>
        <Link
          v-if="can.createPartner"
          :href="route('network.partners.create', props.network.id)"
        >
          <Button class="text-xs tracking-wide" size="xs"> إضافة شريك </Button>
        </Link>
      </div>
      <DataTable
        v-if="network.partners"
        :data="network.partners"
        :columns="columns(network, can.deletePartner, can.assignManager)"
        :summaryFields="network.partners.length ? summaryFields : undefined"
      />
    </div>
  </AuthenticatedLayout>
</template>
