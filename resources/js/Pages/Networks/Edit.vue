<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { Link } from '@inertiajs/vue3'

import { formSchema } from './definitions'
import NetworkForm from './Partials/NetworkForm.vue'

import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'

import { columns, summaryFields } from './partners'
import { DataTable } from '@/Components/data-table'

import { Button } from '@/Components/ui/button'
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'
import { Badge } from '@/Components/ui/badge'
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

    <div class="mt-4 space-y-4">
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
