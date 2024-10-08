<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { Head } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'
import DeleteLink from '@/Components/links/DeleteLink.vue'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'

import { AlertCircle } from 'lucide-vue-next'

import OrderForm from './Partials/OrderForm.vue'
import CardsSection from './Partials/CardsSection.vue'
import { useSubmit } from '@/Composables/submit'

import { Card, Order, User } from '@/types'
import FilesSection from './Partials/FilesSection.vue'

const props = defineProps<{
  order: Order
  users?: Array<User>
  cards?: Array<Card>
  can: {
    update: boolean
    delete: boolean
    items: {
      create: boolean
    }
    files: {
      create: boolean
    }
  }
}>()

const formSchema = toTypedSchema(
  z.object({
    orderer_id: z.number({ message: 'هذا الحقل مطلوب' }),
    status: z.string({ message: 'هذا الحقل مطلوب' }),
    notes: z.string().optional(),
    files: z.array(z.string()).optional(),
  }),
)

const { handleSubmit, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
  initialValues: {
    orderer_id: props.order.orderer.id,
    status: props.order.status,
    notes: props.order.notes ?? undefined,
  },
})

const { submit, loading } = useSubmit(
  route('orders.update', props.order.id),
  undefined,
  setErrors,
  'تم تعديل الطلب',
  'patch',
)
const onSubmit = handleSubmit(submit)
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        طلب رقم: {{ order.id }}
        <span class="text-xs font-normal tracking-wide">
          ({{ order.manager.name }})
        </span>
      </h2>
    </template>

    <Alert
      v-if="order.can.update && order.status !== 'معلق'"
      class="rounded-none sm:rounded-xl"
      variant="destructive"
    >
      <AlertCircle class="h-4 w-4" />
      <AlertTitle>انتباه!</AlertTitle>
      <AlertDescription>
        يمكنك تعديل أو حذف هذا الطلب حتى الساعة
        <span class="font-bold">11:59</span>!
      </AlertDescription>
    </Alert>

    <UpdateFormLayout
      class="mt-4"
      @submit="onSubmit"
      :loading="loading"
      :can-update="can.update"
    >
      <template #buttons>
        <DeleteLink
          v-if="can.delete"
          :href="route('orders.destroy', order.id)"
          @success="toast({ title: 'تم حذف الطلب' })"
        >
          حذف
        </DeleteLink>
      </template>

      <OrderForm
        hidden-cards
        :disabled="!can.update"
        :order="order"
        :users="users"
        :cards="cards"
        :selected="values.orderer_id"
        @select="(selected: number) => setFieldValue('orderer_id', selected)"
      />
    </UpdateFormLayout>

    <CardsSection
      class="mt-4"
      editing
      :can-update="can.update"
      :items="order.items"
      :order="order"
      :cards="cards"
      :can-add-item="can.items.create"
    />

    <FilesSection v-if="can.files.create" class="mt-4" :order="order" />
  </AuthenticatedLayout>
</template>
