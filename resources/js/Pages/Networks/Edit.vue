<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { Network } from '@/types'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import NetworkForm from './Partials/NetworkForm.vue'
import { columns, summaryFields } from './partners'
import DataTable from '@/Components/data-table/DataTable.vue'
import Button from '@/Components/ui/button/Button.vue'
import { useSubmit } from '@/Components/Composables/submit'
import { toast } from '@/Components/ui/toast'
import DeleteLink from '@/Components/links/DeleteLink.vue'
import SecondaryLink from '@/Components/links/SecondaryLink.vue'

const props = defineProps<{
  network: Network
  can: {
    assignManager: boolean
    createPartner: boolean
    deletePartner: boolean
  }
}>()

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    internet_price_per_week: z.any().optional(),
  }),
)

const { handleSubmit, setErrors } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.network.name,
    internet_price_per_week: props.network.internet_price_per_week,
  },
})

const { submit, loading } = useSubmit(
  route('networks.update', props.network.id),
  undefined,
  setErrors,
  'تم تعديل الشبكة',
  'patch',
)
const onSubmit = handleSubmit(submit)
</script>

<template>
  <Head title="Networks" />

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

    <UpdateFormLayout @submit="onSubmit" :loading="loading" :can-update="true">
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
